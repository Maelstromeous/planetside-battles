<?php

namespace PSB\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StatsPlayersTotals
 *
 * @ORM\Table(name="ws_players_total")
 * @ORM\Entity
 */
class StatsPlayersTotals
{
    /**
     * @var integer
     *
     * @ORM\Column(name="playerID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="playerName", type="string", length=24)
    */
    private $playerName;

    /**
     * @ORM\Column(name="playerOutfit", type="string", length=50)
    */
    private $playerOutfit;

    /**
     * @ORM\Column(name="playerKills", type="integer")
    */
    private $playerKills;

    /**
     * @ORM\Column(name="playerDeaths", type="integer")
    */
    private $playerDeaths;

    /**
     * @ORM\Column(name="playerTeamKills", type="integer")
    */
    private $playerTeamKills;

    /**
     * @ORM\Column(name="playerSuicides", type="integer")
    */
    private $playerSuicides;

    /**
     * @ORM\Column(name="playerFaction", type="string", length=1)
    */
    private $playerFaction;

    /**
     * @ORM\Column(name="headshots", type="string")
    */
    private $headshots;

    /**
     * @ORM\Column(name="playerServer", type="smallint")
    */
    private $playerServer;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set playerName
     *
     * @param string $playerName
     *
     * @return StatsPlayersTotals
     */
    public function setPlayerName($playerName)
    {
        $this->playerName = $playerName;

        return $this;
    }

    /**
     * Get playerName
     *
     * @return string
     */
    public function getPlayerName()
    {
        return $this->playerName;
    }

    /**
     * Set playerOutfit
     *
     * @param string $playerOutfit
     *
     * @return StatsPlayersTotals
     */
    public function setPlayerOutfit($playerOutfit)
    {
        $this->playerOutfit = $playerOutfit;

        return $this;
    }

    /**
     * Get playerOutfit
     *
     * @return string
     */
    public function getPlayerOutfit()
    {
        return $this->playerOutfit;
    }

    /**
     * Set playerKills
     *
     * @param integer $playerKills
     *
     * @return StatsPlayersTotals
     */
    public function setPlayerKills($playerKills)
    {
        $this->playerKills = $playerKills;

        return $this;
    }

    /**
     * Get playerKills
     *
     * @return integer
     */
    public function getPlayerKills()
    {
        return $this->playerKills;
    }

    /**
     * Set playerDeaths
     *
     * @param integer $playerDeaths
     *
     * @return StatsPlayersTotals
     */
    public function setPlayerDeaths($playerDeaths)
    {
        $this->playerDeaths = $playerDeaths;

        return $this;
    }

    /**
     * Get playerDeaths
     *
     * @return integer
     */
    public function getPlayerDeaths()
    {
        return $this->playerDeaths;
    }

    /**
     * Set playerTeamKills
     *
     * @param integer $playerTeamKills
     *
     * @return StatsPlayersTotals
     */
    public function setPlayerTeamKills($playerTeamKills)
    {
        $this->playerTeamKills = $playerTeamKills;

        return $this;
    }

    /**
     * Get playerTeamKills
     *
     * @return integer
     */
    public function getPlayerTeamKills()
    {
        return $this->playerTeamKills;
    }

    /**
     * Set playerSuicides
     *
     * @param integer $playerSuicides
     *
     * @return StatsPlayersTotals
     */
    public function setPlayerSuicides($playerSuicides)
    {
        $this->playerSuicides = $playerSuicides;

        return $this;
    }

    /**
     * Get playerSuicides
     *
     * @return integer
     */
    public function getPlayerSuicides()
    {
        return $this->playerSuicides;
    }

    /**
     * Set playerFaction
     *
     * @param string $playerFaction
     *
     * @return StatsPlayersTotals
     */
    public function setPlayerFaction($playerFaction)
    {
        $this->playerFaction = $playerFaction;

        return $this;
    }

    /**
     * Get playerFaction
     *
     * @return string
     */
    public function getPlayerFaction()
    {
        return $this->playerFaction;
    }

    /**
     * Set headshots
     *
     * @param string $headshots
     *
     * @return StatsPlayersTotals
     */
    public function setHeadshots($headshots)
    {
        $this->headshots = $headshots;

        return $this;
    }

    /**
     * Get headshots
     *
     * @return string
     */
    public function getHeadshots()
    {
        return $this->headshots;
    }

    /**
     * Set playerServer
     *
     * @param integer $playerServer
     *
     * @return StatsPlayersTotals
     */
    public function setPlayerServer($playerServer)
    {
        $this->playerServer = $playerServer;

        return $this;
    }

    /**
     * Get playerServer
     *
     * @return integer
     */
    public function getPlayerServer()
    {
        return $this->playerServer;
    }
}
