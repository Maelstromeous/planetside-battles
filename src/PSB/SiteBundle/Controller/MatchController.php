<?php

namespace PSB\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class MatchController extends Controller
{
    public function matchShowAction($matchID)
    {
        $match = $this->getMatchStatsAction($matchID);

        if ($match) {
            return $this->render('PSBSiteBundle::Site/Matches/match.html.twig', array('match' => $match));
        }

        return $this->render('PSBSiteBundle::Site/Common/errornotfound.html.twig');
    }

    public function matchListAction()
    {
        $em = $this->getDoctrine()->getManager();

        $matches = $em->getRepository('PSBAdminBundle:Matches')->findBy(array(), array('id' => "DESC"));

        $data = [];

        foreach ($matches as $match) {
            $match->getServersmashdata();
            $match->getVods()->getValues();
            $match->getArticles()->getValues();

            $data[] = $match;
        }

        return $this->render('PSBSiteBundle::Site/Matches/matches.html.twig', array('matches' => $data));
    }

    public function getMatchStatsAction($matchID) // Cacheable query
    {
        $em = $this->getDoctrine()->getManager();
        $match = $em->getRepository('PSBAdminBundle:Matches')->find($matchID);

        $encoders = array(new JsonEncoder());
        $normalizer = new GetSetMethodNormalizer();
        $normalizer->setIgnoredAttributes(array('match'));
        $serializer = new Serializer(array($normalizer), $encoders);


        $supplimental["WeaponData"] = $serializer->serialize($this->getSupplimentalData("weapon_data"), 'json');
        $supplimental["FacilityData"] = $serializer->serialize($this->getSupplimentalData("facility_data"), 'json');
        $supplimental["FacilityDataInverse"] =
        $serializer->serialize(
            $this->getSupplimentalData("facility_data_inverse"),
            'json'
        );
        $supplimental["VehicleData"] = $serializer->serialize($this->getSupplimentalData("vehicle_data"), 'json');
        // Grab sup data. Pass it the serializer so we don't have to initiate again.

        $match->supplimental = $supplimental; // Assign the supplimental stats to a key

        $metrics["matchInfo"] = $serializer->serialize($match->getServersmashdata(), 'json');

        $metrics["statsPlayers"] = $serializer->serialize($match->getStatsPlayers()->getValues(), 'json');
        $metrics["statsOutfits"] = $serializer->serialize($match->getStatsOutfits()->getValues(), 'json');
        $metrics["statsFactions"] = $serializer->serialize($match->getStatsFactions(), 'json');
        $metrics["statsVehicles"] = $serializer->serialize($match->getStatsVehicles()->getValues(), 'json');
        $metrics["statsWeapons"] = $serializer->serialize($match->getStatsWeaponsTotals()->getValues(), 'json');

        $chars = $match->getCharacters()->getValues();

        $metrics["statsCharacters"] = null;

        if (! empty($chars)) {
            foreach ($chars as $char) {
                $dataChars[$char->getCharName()] = array(
                    "charName" => $char->getCharName(),
                    "playerName" => $char->getPlayerName(),
                    "serverID" => $char->getServerID(),
                    "outfitTag" => $char->getOutfitTag()
                );
            }

            $metrics["statsCharacters"] = $serializer->serialize($dataChars, 'json');
        }

        $mapInitialStats = $match->getStatsmapinitial();

        if ($mapInitialStats != null) {
            $mapInitialStats = $match->getStatsmapinitial()->getValues();
        }

        $metrics["initialMap"] = null;

        if (!empty($mapInitialStats)) {
            foreach ($mapInitialStats as $record) {
                $initialMap[$record->getFacilityID()] = $record;
            }

            $metrics["initialMap"] = $serializer->serialize($initialMap, 'json');
        }

        $mapStats = $match->getStatsmap();

        $metrics["mapStats"] = "nodata";

        if (!empty($mapStats)) {
            foreach ($mapStats as $record) {
                $mapRecords[] = array(
                    'facilityID' => $record->getFacilityid(),
                    'facilityOwner' => $record->getFacilityowner()
                    );
            }

            if (! empty($mapRecords)) {
                $metrics["mapStats"] = $serializer->serialize($mapRecords, 'json');
            }
        }

        $match->getStatscombathistory()->getValues();
        $match->getStatsMap()->getValues(); // Need to have these unserialized so that we can loop through it in twig.

        $match->metrics = $metrics; // Assign stats to it's own key
        $match->supplimental = $supplimental; // Assign stats to it's own key

        $articles = $match->getArticles()->getValues();
        $match->getVods()->getValues();

        foreach ($articles as $article) {
            $article->getAuthor()->getName(); // Initialize the author object
        }

        $matchsettings = $match->getServersmashdata();

        $match->matchsettings = array(
            "server1Faction" => $this->translateFactions($matchsettings->getServer1faction()),
            "server2Faction" => $this->translateFactions($matchsettings->getServer2faction()),
            "server3Faction" => $this->translateFactions($matchsettings->getServer3faction()),
        );

        $match->matchsettings["neutFaction"] = null;

        if ($match->matchsettings["server3Faction"] == null) { // If theres a neutral faction
            $VS = 0;
            $NC = 0;
            $TR = 0;
            $neut = null;

            foreach ($match->matchsettings as $faction) {
                switch ($faction) {
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

            if ($VS == 0) {
                $neut = "vs";
            } elseif ($NC == 0) {
                $neut = "nc";
            } elseif ($TR == 0) {
                $neut = "tr";
            }

            $match->matchsettings["neutFaction"] = $neut;
        }

        return $match;
    }

    public function translateFactions($faction)
    {
        if ($faction == 1) {
            return "vs";
        } elseif ($faction == 2) {
            return "nc";
        } elseif ($faction == 3) {
            return "tr";
        } elseif ($faction == null) {
            return null;
        }
        return "UNKNOWN";
    }

    public function getSupplimentalData($table)
    {
        $inverse = 0;

        if ($table == "facility_data_inverse") {
            $inverse = 1;
            $table = "facility_data";
        }

        $query = $this->get('doctrine')->getManager('datastore')->getConnection()
        ->prepare("SELECT t1.* FROM {$table} t1");
        $query->execute();

        $data = $query->fetchAll();

        if ($table == "weapon_data") {
            foreach ($data as $weapon) {
                $return[$weapon["weaponID"]] = $weapon;
            }
        } elseif ($table == "vehicle_data") {
            foreach ($data as $vehicle) {
                $return[$vehicle["vehicleID"]] = $vehicle;
            }
        } elseif ($inverse == 1) {
            foreach ($data as $facility) {
                $key = intval($facility["facilityMapID"]);
                $return[$key]["facilityID"] = intval($facility["facilityID"]);
                $return[$key]["facilityMapID"] = intval($facility["facilityMapID"]);
            }
        } elseif ($table == "facility_data") {
            foreach ($data as $facility) {
                $return[$facility["facilityID"]] = $facility;
            }
        }

        return $return;
    }
}
