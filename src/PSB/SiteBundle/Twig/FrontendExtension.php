<?php

namespace PSB\SiteBundle\Twig;

class FrontendExtension extends \Twig_Extension
{
	public function getFilters()
	{
		return array(
			new \Twig_SimpleFilter('matchType', array($this, 'matchType')),
			new \Twig_SimpleFilter('serverName', array($this, 'serverName')),
			new \Twig_SimpleFilter('zoneName', array($this, 'zoneName')),
		);
	}

	public function matchType($matchType)
	{
		switch($matchType)
		{
			case "1":
			{
				return "ServerSmash";
			}
			case "2":
			{
				return "OvO";
			}
			case "3":
			{
				return "PSBL";
			}
			case "4":
			{
				return "ArmorSide";
			}
		}

		return false;
	}

	public function serverName($server, $date = NULL)
	{
		if (empty($server))
		{
			return "UNKNOWN";
		}

		if ($date === NULL) { $date = time(); }
		$merge = 1405814400;

		switch ($server)
		{
			case "1":
			case "Connery":
			{
				return "Connery";
			}
			case "9":
			case "Woodman":
			{
				return "Woodman";
			}
			case "10":
			case "Miller":
			{
				return "Miller";
			}
			case "13":
			case "Cobalt":
			{
				return "Cobalt";
			}
			case "17":
			case "Emerald":
			{
				if ($date < $merge)
				{
					return "Mattherson";
				}
				return "Emerald";
			}
			case "18":
			case "Waterson":
			{
				return "Waterson";
			}
			case "19":
			case "Jaeger":
			{
				return "Jaeger";
			}
			case "25":
			case "Briggs":
			{
				return "Briggs";
			}
		}

		return $server;
	}

	public function zoneName($zone)
	{
		switch ($zone)
		{
			case "2":
			case "Indar":
			{
				return "Indar";
			}
			case "4":
			case "Hossin":
			{
				return "Hossin";
			}
			case "6":
			case "Amerish":
			{
				return "Amerish";
			}
			case "8":
			case "Esamir":
			{
				return "Esamir";
			}
			case "14":
			case "Koltyr":
			{
				return "Koltyr";
			}
		}

		return "UNKNOWN!";
	}

	public function getName()
	{
		return 'twig.extension';
	}
}