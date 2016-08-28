<?php

namespace PSB\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StatsCombat
 *
 * @ORM\Table(name="ws_combat")
 * @ORM\Entity
 */
class StatsCombat
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
     * @ORM\ManyToOne(targetEntity="Matches", inversedBy="combatraw")
     * @ORM\JoinColumn(name="resultID", referencedColumnName="id")
     */
    private $match;

    /**
     * @ORM\Column(name="timestamp", type="integer", nullable=false)
     */
    private $timestamp;

    /**
     * @ORM\Column(name="worldID", type="smallint", nullable=false)
     */
    private $worldID;

    /**
     * @ORM\Column(name="attackerID", type="string", nullable=true)
     */
    private $attackerID;

    /**
     * @ORM\Column(name="attackerName", type="string", length=50, nullable=true)
     */
    private $attackerName;

    /**
     * @ORM\Column(name="attackerOutfit", type="string", length=20, nullable=true)
     */
    private $attackerOutfit;

    /**
     * @ORM\Column(name="attackerFaction", type="smallint", nullable=true)
     */
    private $attackerFaction;

    /**
     * @ORM\Column(name="attackerLoadout", type="smallint", nullable=true)
     */
    private $attackerLoadout;

    /**
     * @ORM\Column(name="victimID", type="string", length=20, nullable=true)
     */
    private $victimID;

    /**
     * @ORM\Column(name="victimName", type="string", length=50, nullable=true)
     */
    private $victimName;

    /**
     * @ORM\Column(name="victimOutfit", type="string", length=20, nullable=true)
     */
    private $victimOutfit;

    /**
     * @ORM\Column(name="victimFaction", type="smallint", nullable=true)
     */
    private $victimFaction;

    /** 
     * @ORM\Column(name="victimLoadout", type="smallint", nullable=true)
     */
    private $victimLoadout;

    /**
     * @ORM\Column(name="weaponID", type="smallint", nullable=true)
     */
    private $weaponID;

    /**
     * @ORM\Column(name="vehicleID", type="smallint", nullable=true)
     */
    private $vehicleID;

    /**
     * @ORM\Column(name="headshot", type="boolean", nullable=true)
     */
    private $headshot;

    /**
     * @ORM\Column(name="zone", type="smallint", nullable=true)
     */
    private $zone;

    /**
     * @ORM\Column(name="teamkill", type="boolean", nullable=true)
     */
    private $teamkill;

    /**
     * @ORM\Column(name="suicide", type="boolean", nullable=true)
     */
    private $suicide;

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
     * Set timestamp
     *
     * @param integer $timestamp
     * @return StatsCombat
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return integer 
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set worldID
     *
     * @param integer $worldID
     * @return StatsCombat
     */
    public function setWorldID($worldID)
    {
        $this->worldID = $worldID;

        return $this;
    }

    /**
     * Get worldID
     *
     * @return integer 
     */
    public function getWorldID()
    {
        return $this->worldID;
    }

    /**
     * Set attackerID
     *
     * @param string $attackerID
     * @return StatsCombat
     */
    public function setAttackerID($attackerID)
    {
        $this->attackerID = $attackerID;

        return $this;
    }

    /**
     * Get attackerID
     *
     * @return string 
     */
    public function getAttackerID()
    {
        return $this->attackerID;
    }

    /**
     * Set attackerName
     *
     * @param string $attackerName
     * @return StatsCombat
     */
    public function setAttackerName($attackerName)
    {
        $this->attackerName = $attackerName;

        return $this;
    }

    /**
     * Get attackerName
     *
     * @return string 
     */
    public function getAttackerName()
    {
        return $this->attackerName;
    }

    /**
     * Set attackerOutfit
     *
     * @param string $attackerOutfit
     * @return StatsCombat
     */
    public function setAttackerOutfit($attackerOutfit)
    {
        $this->attackerOutfit = $attackerOutfit;

        return $this;
    }

    /**
     * Get attackerOutfit
     *
     * @return string 
     */
    public function getAttackerOutfit()
    {
        return $this->attackerOutfit;
    }

    /**
     * Set attackerFaction
     *
     * @param integer $attackerFaction
     * @return StatsCombat
     */
    public function setAttackerFaction($attackerFaction)
    {
        $this->attackerFaction = $attackerFaction;

        return $this;
    }

    /**
     * Get attackerFaction
     *
     * @return integer 
     */
    public function getAttackerFaction()
    {
        return $this->attackerFaction;
    }

    /**
     * Set attackerLoadout
     *
     * @param integer $attackerLoadout
     * @return StatsCombat
     */
    public function setAttackerLoadout($attackerLoadout)
    {
        $this->attackerLoadout = $attackerLoadout;

        return $this;
    }

    /**
     * Get attackerLoadout
     *
     * @return integer 
     */
    public function getAttackerLoadout()
    {
        return $this->attackerLoadout;
    }

    /**
     * Set victimID
     *
     * @param string $victimID
     * @return StatsCombat
     */
    public function setVictimID($victimID)
    {
        $this->victimID = $victimID;

        return $this;
    }

    /**
     * Get victimID
     *
     * @return string 
     */
    public function getVictimID()
    {
        return $this->victimID;
    }

    /**
     * Set victimName
     *
     * @param string $victimName
     * @return StatsCombat
     */
    public function setVictimName($victimName)
    {
        $this->victimName = $victimName;

        return $this;
    }

    /**
     * Get victimName
     *
     * @return string 
     */
    public function getVictimName()
    {
        return $this->victimName;
    }

    /**
     * Set victimOutfit
     *
     * @param string $victimOutfit
     * @return StatsCombat
     */
    public function setVictimOutfit($victimOutfit)
    {
        $this->victimOutfit = $victimOutfit;

        return $this;
    }

    /**
     * Get victimOutfit
     *
     * @return string 
     */
    public function getVictimOutfit()
    {
        return $this->victimOutfit;
    }

    /**
     * Set victimFaction
     *
     * @param integer $victimFaction
     * @return StatsCombat
     */
    public function setVictimFaction($victimFaction)
    {
        $this->victimFaction = $victimFaction;

        return $this;
    }

    /**
     * Get victimFaction
     *
     * @return integer 
     */
    public function getVictimFaction()
    {
        return $this->victimFaction;
    }

    /**
     * Set victimLoadout
     *
     * @param integer $victimLoadout
     * @return StatsCombat
     */
    public function setVictimLoadout($victimLoadout)
    {
        $this->victimLoadout = $victimLoadout;

        return $this;
    }

    /**
     * Get victimLoadout
     *
     * @return integer 
     */
    public function getVictimLoadout()
    {
        return $this->victimLoadout;
    }

    /**
     * Set weaponID
     *
     * @param integer $weaponID
     * @return StatsCombat
     */
    public function setWeaponID($weaponID)
    {
        $this->weaponID = $weaponID;

        return $this;
    }

    /**
     * Get weaponID
     *
     * @return integer 
     */
    public function getWeaponID()
    {
        return $this->weaponID;
    }

    /**
     * Set vehicleID
     *
     * @param integer $vehicleID
     * @return StatsCombat
     */
    public function setVehicleID($vehicleID)
    {
        $this->vehicleID = $vehicleID;

        return $this;
    }

    /**
     * Get vehicleID
     *
     * @return integer 
     */
    public function getVehicleID()
    {
        return $this->vehicleID;
    }

    /**
     * Set headshot
     *
     * @param boolean $headshot
     * @return StatsCombat
     */
    public function setHeadshot($headshot)
    {
        $this->headshot = $headshot;

        return $this;
    }

    /**
     * Get headshot
     *
     * @return boolean 
     */
    public function getHeadshot()
    {
        return $this->headshot;
    }

    /**
     * Set zone
     *
     * @param integer $zone
     * @return StatsCombat
     */
    public function setZone($zone)
    {
        $this->zone = $zone;

        return $this;
    }

    /**
     * Get zone
     *
     * @return integer 
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * Set teamkill
     *
     * @param boolean $teamkill
     * @return StatsCombat
     */
    public function setTeamkill($teamkill)
    {
        $this->teamkill = $teamkill;

        return $this;
    }

    /**
     * Get teamkill
     *
     * @return boolean 
     */
    public function getTeamkill()
    {
        return $this->teamkill;
    }

    /**
     * Set suicide
     *
     * @param boolean $suicide
     * @return StatsCombat
     */
    public function setSuicide($suicide)
    {
        $this->suicide = $suicide;

        return $this;
    }

    /**
     * Get suicide
     *
     * @return boolean 
     */
    public function getSuicide()
    {
        return $this->suicide;
    }

    /**
     * Set match
     *
     * @param \PSB\AdminBundle\Entity\Matches $match
     * @return StatsCombat
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
