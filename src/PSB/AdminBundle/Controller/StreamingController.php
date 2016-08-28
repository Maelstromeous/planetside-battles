<?php

namespace PSB\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class StreamingController extends Controller
{
	public function indexAction()
	{
		//dump($this->getUser());
		return $this->render('PSBAdminBundle:Default:index.html.twig');
	}

	public function streamingAction($id)
	{
		$match = $this->getMatchStatsAction($id);

		//dump($match);

		if ($match)
		{
			return $this->render('PSBAdminBundle:Streaming:streaming.html.twig', array('match' => $match));
		}
	}

	public function getMatchStatsAction($matchID) // Cacheable query
	{
		$redis = $this->get('snc_redis.cache');

		$key = "result_".$matchID;
		$match = $redis->get($key); // Attempt to get from cache first

		if (empty($match))
		{
			//dump("SETTING CACHE");
			$em = $this->getDoctrine()->getManager();
			$match = $em->getRepository('PSBAdminBundle:Matches')->find($matchID);

			$encoders = array(new JsonEncoder());
			$normalizer = new GetSetMethodNormalizer();
			$normalizer->setIgnoredAttributes(array('match'));
			$serializer = new Serializer(array($normalizer), $encoders);

			$metrics["matchInfo"] = $serializer->serialize($match->getServersmashdata(), 'json');

			$metrics["statsPlayers"] = $serializer->serialize($match->getStatsPlayers()->getValues(), 'json');
			$metrics["statsOutfits"] = $serializer->serialize($match->getStatsOutfits()->getValues(), 'json');
			$metrics["statsWeapons"] = $serializer->serialize($match->getStatsWeaponsTotals()->getValues(), 'json');

			$chars = $match->getCharacters()->getValues();

			if (!empty($chars))
			{
				foreach($chars as $char)
				{
					$dataChars[$char->getCharName()] = array(
						"charName" => $char->getCharName(),
						"playerName" => $char->getPlayerName(),
						"serverID" => $char->getServerID(),
						"outfitTag" => $char->getOutfitTag()
						);
				}

				$metrics["statsCharacters"] = $serializer->serialize($dataChars, 'json');
			}
			else
			{
				$metrics["statsCharacters"] = NULL;
			}

			$metrics["statsFactions"] = $serializer->serialize($match->getStatsFactions(), 'json');
			$metrics["statsVehicles"] = $serializer->serialize($match->getStatsVehicles()->getValues(), 'json');

			$match->getStatscombathistory()->getValues();
			$map = $match->getStatsMap()->getValues(); // Need to have these unserialized so that we can loop through it in twig.

			$supplimental["WeaponData"] = $serializer->serialize($this->getSupplimentalData("weapon_data"), 'json');
			$supplimental["FacilityData"] = $serializer->serialize($this->getSupplimentalData("facility_data"), 'json');
			$supplimental["VehicleData"] = $serializer->serialize($this->getSupplimentalData("vehicle_data"), 'json');
			// Grab sup data. Pass it the serializer so we don't have to initiate again.

			$match->facilitySupData = $this->getSupplimentalData("facility_data"); // So it is accessable by PHP.

			$articles = $match->getArticles()->getValues();
			$VODs = $match->getVods()->getValues();

			$matchsettings = $match->getServersmashdata();

			$match->matchsettings = array(
				"server1Faction" => $this->translateFactions($matchsettings->getServer1faction()),
				"server2Faction" => $this->translateFactions($matchsettings->getServer2faction()),
				"server3Faction" => $this->translateFactions($matchsettings->getServer3faction()),
			);

			if ($match->matchsettings["server3Faction"] == NULL) // If theres a neutral faction
			{
				$VS = 0;
				$NC = 0;
				$TR = 0;
				$neut = NULL;

				foreach($match->matchsettings as $faction)
				{
					switch($faction)
					{
						case "vs":
							$VS = 1;
							break;
						case "nc":
							$NC = 1;
							break;
						case "tr":
							$TR = 1;
							break;
					}
				}

				if ($VS == 0)
				{
					$neut = "vs";
				}
				else if ($NC == 0)
				{
					$neut = "nc";
				}
				else if ($TR == 0)
				{
					$neut = "tr";
				}

				/*$neutUpper = strtoupper($neut);
				$key = "getControl".$neutUpper;

				$lastmap = end($map);

				$neutPer = end($lastmap->$key());

				dump($neutPer);*/

				$match->matchsettings["neutFaction"] = $neut;
				//$match->matchsettings["neutPer"] = $neutPer;
			}

			//dump($match->matchsettings);

			$match->metrics = $metrics; // Assign stats to it's own key
			$match->supplimental = $supplimental; //Assign stats to it's own key

			$data = serialize($match);

			// Commit Match Entity and Stats to Redis for caching
			//$set = $redis->setex($key, 60, $data);
		}
		else
		{
			$match = unserialize($match);
		}

		//dump($match);

		return $match;
	}

	public function translateFactions($faction)
	{
		if ($faction == 1) { return "vs"; }
		else if ($faction == 2) { return "nc"; }
		else if ($faction == 3) { return "tr"; }
		else if ($faction == NULL) { return NULL; }
		return "UNKNOWN";
	}

	public function getSupplimentalData($table)
	{
		$query = $this->get('doctrine')->getManager('datastore')->getConnection()
		->prepare("SELECT t1.* FROM {$table} t1");
		$query->execute();

		$data = $query->fetchAll();

		if ($table == "weapon_data")
		{
			foreach($data as $weapon)
			{
				$return[$weapon["weaponID"]] = $weapon;
			}
		}
		else if ($table == "vehicle_data")
		{
			foreach($data as $vehicle)
			{
				$return[$vehicle["vehicleID"]] = $vehicle;
			}
		}
		else if ($table == "facility_data")
		{
			foreach($data as $facility)
			{
				$return[$facility["facilityID"]] = $facility;
			}
		}

		return $return;
	}
}
