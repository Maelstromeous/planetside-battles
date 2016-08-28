<?php

namespace PSB\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SSPLayers
 *
 * @ORM\Table(name="ss_players")
 * @ORM\Entity
 */
class SSPlayers
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
     * @ORM\Column(name="charName", type="string", length=24)
     */
    private $charName;

    /**
     * @ORM\Column(name="playerName", type="string", length=24)
     */
    private $playerName;

    /**
     * @ORM\Column(name="serverID", type="integer", length=2, nullable=true)
     */
    private $serverID;

    /**
     * @ORM\Column(name="outfitTag", type="string", length=5, nullable=true)
     */
    private $outfitTag;

    /**
     * @ORM\ManyToOne(targetEntity="Matches", inversedBy="characters")
     * @ORM\JoinColumn(name="matchID", referencedColumnName="id")
     */
    private $match; 

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
     * Set charName
     *
     * @param string $charName
     * @return SSPlayers
     */
    public function setCharName($charName)
    {
        $this->charName = $charName;

        return $this;
    }

    /**
     * Get charName
     *
     * @return string 
     */
    public function getCharName()
    {
        return $this->charName;
    }

    /**
     * Set playerName
     *
     * @param string $playerName
     * @return SSPlayers
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
     * Set serverID
     *
     * @param integer $serverID
     * @return SSPlayers
     */
    public function setServerID($serverID)
    {
        $this->serverID = $serverID;

        return $this;
    }

    /**
     * Get serverID
     *
     * @return integer 
     */
    public function getServerID()
    {
        return $this->serverID;
    }

    /**
     * Set outfitTag
     *
     * @param string $outfitTag
     * @return SSPlayers
     */
    public function setOutfitTag($outfitTag)
    {
        $this->outfitTag = $outfitTag;

        return $this;
    }

    /**
     * Get outfitTag
     *
     * @return string 
     */
    public function getOutfitTag()
    {
        return $this->outfitTag;
    }

    /**
     * Set match
     *
     * @param \PSB\AdminBundle\Entity\Matches $match
     * @return SSPlayers
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
