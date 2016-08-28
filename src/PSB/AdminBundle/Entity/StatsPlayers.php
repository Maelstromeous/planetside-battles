<?php

namespace PSB\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Articles
 *
 * @ORM\Table(name="ws_players")
 * @ORM\Entity
 */
class StatsPlayers
{
    /**
     * @var integer
     *
     * @ORM\Column(name="dataID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="playerID", type="string", length=20, nullable=false)
    */
    private $playerID;

   /**
    * @ORM\ManyToOne(targetEntity="Matches", inversedBy="statsplayers")
    * @ORM\JoinColumn(name="resultID", referencedColumnName="id")
    */
   private $match;

   /**
    * @ORM\Column(name="playerName", type="string", length=24, nullable=false)
   */
   private $playerName;

   /**
    * @ORM\Column(name="playerOutfit", type="string", length=20, nullable=false)
   */
   private $playerOutfit;

   /**
    * @ORM\Column(name="playerFaction", type="smallint", nullable=false)
   */
   private $playerFaction;

   /**
    * @ORM\Column(name="playerKills", type="smallint", nullable=false)
   */
   private $playerKills;

   /**
    * @ORM\Column(name="playerDeaths", type="smallint", nullable=false)
   */
   private $playerDeaths;

   /**
    * @ORM\Column(name="playerTeamKills", type="smallint", nullable=false)
   */
   private $playerTeamKills;

   /**
    * @ORM\Column(name="playerSuicides", type="smallint", nullable=false)
   */
   private $playerSuicides;

   /**
    * @ORM\Column(name="headshots", type="smallint", nullable=false)
   */
   private $headshots;

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
     * Set playerID
     *
     * @param string $playerID
     *
     * @return StatsPlayers
     */
    public function setPlayerID($playerID)
    {
        $this->playerID = $playerID;

        return $this;
    }

    /**
     * Get playerID
     *
     * @return string
     */
    public function getPlayerID()
    {
        return $this->playerID;
    }

    /**
     * Set playerName
     *
     * @param string $playerName
     *
     * @return StatsPlayers
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
     * @return StatsPlayers
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
     * Set playerFaction
     *
     * @param integer $playerFaction
     *
     * @return StatsPlayers
     */
    public function setPlayerFaction($playerFaction)
    {
        $this->playerFaction = $playerFaction;

        return $this;
    }

    /**
     * Get playerFaction
     *
     * @return integer
     */
    public function getPlayerFaction()
    {
        return $this->playerFaction;
    }

    /**
     * Set playerKills
     *
     * @param integer $playerKills
     *
     * @return StatsPlayers
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
     * @return StatsPlayers
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
     * @return StatsPlayers
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
     * @return StatsPlayers
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
     * Set headshots
     *
     * @param integer $headshots
     *
     * @return StatsPlayers
     */
    public function setHeadshots($headshots)
    {
        $this->headshots = $headshots;

        return $this;
    }

    /**
     * Get headshots
     *
     * @return integer
     */
    public function getHeadshots()
    {
        return $this->headshots;
    }

    /**
     * Set match
     *
     * @param \PSB\AdminBundle\Entity\Matches $match
     *
     * @return StatsPlayers
     */
    public function setMatch(\PSB\AdminBundle\Entity\Matches $match = null)
    {
        $this->match = $match;

        return $this;
    }

    /**
     * Get match
     *
     * @return \PSB\AdminBundle\Entity\Matches
     */
    public function getMatch()
    {
        return $this->match;
    }
}
