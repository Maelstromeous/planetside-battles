<?php
namespace PSB\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TournamentController extends Controller
{
    public function tournament2015Action()
    {
        $data = $this->getTournamentData();

        return $this->render('PSBSiteBundle::Site/tournament2015.html.twig', array('data' => $data));
    }

    public function getTournamentData($kek = false)
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('PSBAdminBundle:Matches')
            ->createQueryBuilder('c')
            ->where('c.id >= :id AND c.id <= :idHigh')
            ->setParameter('id', 46)
            ->setParameter('idHigh', 55);

        $qb2 = $em->getRepository('PSBAdminBundle:Matches')
            ->createQueryBuilder('c')
            ->where('c.id >= :id AND c.id <= :idHigh')
            ->setParameter('id', 56)
            ->setParameter('idHigh', 57);

        $qb3 = $em->getRepository('PSBAdminBundle:Matches')
            ->createQueryBuilder('c')
            ->where('c.id >= :id')
            ->setParameter('id', 58);

        $query = $qb->getQuery();
        $query2 = $qb2->getQuery();
        $query3 = $qb3->getQuery();

        $data['roundRobin'] = $query->getResult();
        $data['semi'] = $query2->getResult();
        $data['championship'] = $query3->getResult();

        $data['all'] = array_merge($data['roundRobin'], $data['semi'], $data['championship']);

        $now = date('U');

        $servers = array(1, 10, 13, 17, 25);

        foreach ($servers as $server) {
            $data['standings'][$server] = array
            (
                'server'   => $server,
                'wins'     => 0,
                'draws'    => 0,
                'losses'   => 0,
                'score'    => 0,
                'kills'    => 0,
                'deaths'   => 0,
                'captures' => 0,
                'plays'    => 0
            );
        }

        $data['twitch'] = false;

        foreach ($data['all'] as $match => $info) {
            $twitchDeadlineStart = $info->getStarttime() - 600; // 10 mins before start
            $twitchDeadlineEnd = $info->getEndtime() + 600; // 10 mins after end

            if ($now > $twitchDeadlineStart && $now < $twitchDeadlineEnd) {
                $data['twitch'] = true;
            }

            $matchInfo = $info->getServersmashdata();

            $server1 = $matchInfo->getServer1();
            $server2 = $matchInfo->getServer2();
            $factions[1] = $matchInfo->getServer1faction();
            $factions[2] = $matchInfo->getServer2faction();
            $winner = $matchInfo->getWinner();

            $map = $info->getStatsmap()->getValues();

            if ($winner != null) { // If the match is finished
                if ($winner == $server1) {
                    $factions['winner'] = $factions[1];
                    $factions['looser'] = $factions[2];
                    $looser = $server2;
                } elseif ($winner == $server2) {
                    $factions['winner'] = $factions[2];
                    $factions['looser']  = $factions[1];
                    $looser = $server1;
                }

                $lastMap = end($map);
                $factionStats = $info->getStatsfactions();

                $captures['VS'] = 0;
                $captures['NC'] = 0;
                $captures['TR'] = 0;

                $defences['VS'] = 0;
                $defences['NC'] = 0;
                $defences['TR'] = 0;

                foreach ($map as $capture) {
                    $defence = $capture->getDefence();

                    if ($defence == false) { // If a cap
                        $facCapturedBy = $capture->getFacilityowner();

                        if ($facCapturedBy == 1) {
                            $captures['VS']++;
                        } elseif ($facCapturedBy == 2) {
                            $captures['NC']++;
                        } elseif ($facCapturedBy == 3) {
                            $captures['TR']++;
                        }
                    }
                }

                $scores['controlVS'] = $lastMap->getControlvs();
                $scores['controlNC'] = $lastMap->getControlnc();
                $scores['controlTR'] = $lastMap->getControltr();

                $kills['VS'] = $factionStats->getKillsvs();
                $kills['NC'] = $factionStats->getKillsnc();
                $kills['TR'] = $factionStats->getKillstr();

                $deaths['VS'] = $factionStats->getDeathsvs();
                $deaths['NC'] = $factionStats->getDeathsnc();
                $deaths['TR'] = $factionStats->getDeathstr();

                $winningFaction = $this->factionConvert($factions['winner']);
                $loosingFaction = $this->factionConvert($factions['looser']);

                $Wkey = 'control'.$winningFaction;
                $Lkey = 'control'.$loosingFaction;
                $winningScore = $scores[$Wkey];
                $loosingScore = $scores[$Lkey];

                $winnerKills = $kills[$winningFaction];
                $winnerDeaths = $deaths[$winningFaction];

                $looserKills = $kills[$loosingFaction];
                $looserDeaths = $deaths[$loosingFaction];

                $winnerCaptures = $captures[$winningFaction];
                $winnerDefences = $defences[$winningFaction];

                $looserCaptures = $captures[$loosingFaction];
                $looserDefences = $defences[$loosingFaction];

                $data['standings'][$winner]['wins']++;
                $data['standings'][$looser]['losses']++;
                $data['standings'][$winner]['score'] = $data['standings'][$winner]['score'] + $winningScore;
                $data['standings'][$looser]['score'] = $data['standings'][$looser]['score'] + $loosingScore;

                $data['standings'][$winner]['kills'] = $data['standings'][$winner]['kills'] + $winnerKills;
                $data['standings'][$winner]['deaths'] = $data['standings'][$winner]['deaths'] + $winnerDeaths;

                $data['standings'][$looser]['kills'] = $data['standings'][$looser]['kills'] + $looserKills;
                $data['standings'][$looser]['deaths'] = $data['standings'][$looser]['deaths'] + $looserDeaths;

                $data['standings'][$winner]['captures'] = $data['standings'][$winner]['captures'] + $winnerCaptures;
                $data['standings'][$looser]['captures'] = $data['standings'][$looser]['captures'] + $looserCaptures;

                $data['standings'][$winner]['plays']++;
                $data['standings'][$looser]['plays']++;
            }

            if ($info->getStarttime() < $now) {
                unset($data['all'][$match]);
            }
        }

        if (! empty($kek)) {
            $data['standings'][10]['score'] = $data['standings'][10]['score'] + 37;
            $data['standings'][1]['score'] = $data['standings'][1]['score'] - 37;
        }

        $data['sortedStandings'] = $data['standings'];
        usort($data['sortedStandings'], array($this, "serverSort"));

        return $data;
    }

    public function serverSort($a, $b)
    {
        if ($a['wins'] == $b['wins']) {
            return ($a['score'] > $b['score']) ? -1 : 1;
        }
        return ($a['wins'] > $b['wins']) ? -1 : 1;
    }

    public function factionConvert($faction)
    {
        if ($faction == 1) {
            return "VS";
        } elseif ($faction == 2) {
            return "NC";
        } elseif ($faction == 3) {
            return "TR";
        }

        return "????";
    }
}
