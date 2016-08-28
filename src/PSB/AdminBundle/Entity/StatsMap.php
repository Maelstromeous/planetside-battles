<?php

namespace PSB\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StatsMap
 *
 * @ORM\Table(name="ws_map")
 * @ORM\Entity
 */
class StatsMap
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
     * @ORM\ManyToOne(targetEntity="Matches", inversedBy="statsmap")
     * @ORM\JoinColumn(name="resultID", referencedColumnName="id")
     */
    private $match;

    /**
     * @ORM\Column(name="timestamp", type="string", length=10)
    */
    private $timestamp;

    /**
     * @ORM\Column(name="facilityID", type="string", length=7)
    */
    private $facilityID;

    /**
     * @ORM\Column(name="facilityOwner", type="string", length=1)
    */
    private $facilityOwner;

    /**
     * @ORM\Column(name="facilityOldOwner", type="string", length=1)
    */
    private $facilityOldOwner;

    /**
     * @ORM\Column(name="durationHeld", type="string", length=8)
    */
    private $durationHeld;

    /**
     * @ORM\Column(name="defence", type="boolean")
    */
    private $defence;

    /**
     * @ORM\Column(name="controlVS", type="string", length=3)
    */
    private $controlVS;

    /**
     * @ORM\Column(name="controlNC", type="string", length=3)
    */
    private $controlNC;

    /**
     * @ORM\Column(name="controlTR", type="string", length=3)
    */
    private $controlTR;

    /**
     * @ORM\Column(name="zone", type="string", length=2)
    */
    private $zone;

    /**
     * @ORM\Column(name="world", type="string", length=2)
    */
    private $world;

    /**
     * @ORM\Column(name="outfitCaptured", type="string", length=20)
    */
    private $outfitCaptured;

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
     * @param string $timestamp
     * @return StatsMap
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return string 
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set facilityID
     *
     * @param string $facilityID
     * @return StatsMap
     */
    public function setFacilityID($facilityID)
    {
        $this->facilityID = $facilityID;

        return $this;
    }

    /**
     * Get facilityID
     *
     * @return string 
     */
    public function getFacilityID()
    {
        return $this->facilityID;
    }

    /**
     * Set facilityOwner
     *
     * @param string $facilityOwner
     * @return StatsMap
     */
    public function setFacilityOwner($facilityOwner)
    {
        $this->facilityOwner = $facilityOwner;

        return $this;
    }

    /**
     * Get facilityOwner
     *
     * @return string 
     */
    public function getFacilityOwner()
    {
        return $this->facilityOwner;
    }

    /**
     * Set facilityOldOwner
     *
     * @param string $facilityOldOwner
     * @return StatsMap
     */
    public function setFacilityOldOwner($facilityOldOwner)
    {
        $this->facilityOldOwner = $facilityOldOwner;

        return $this;
    }

    /**
     * Get facilityOldOwner
     *
     * @return string 
     */
    public function getFacilityOldOwner()
    {
        return $this->facilityOldOwner;
    }

    /**
     * Set durationHeld
     *
     * @param string $durationHeld
     * @return StatsMap
     */
    public function setDurationHeld($durationHeld)
    {
        $this->durationHeld = $durationHeld;

        return $this;
    }

    /**
     * Get durationHeld
     *
     * @return string 
     */
    public function getDurationHeld()
    {
        return $this->durationHeld;
    }

    /**
     * Set defence
     *
     * @param boolean $defence
     * @return StatsMap
     */
    public function setDefence($defence)
    {
        $this->defence = $defence;

        return $this;
    }

    /**
     * Get defence
     *
     * @return boolean 
     */
    public function getDefence()
    {
        return $this->defence;
    }

    /**
     * Set controlVS
     *
     * @param string $controlVS
     * @return StatsMap
     */
    public function setControlVS($controlVS)
    {
        $this->controlVS = $controlVS;

        return $this;
    }

    /**
     * Get controlVS
     *
     * @return string 
     */
    public function getControlVS()
    {
        return $this->controlVS;
    }

    /**
     * Set controlNC
     *
     * @param string $controlNC
     * @return StatsMap
     */
    public function setControlNC($controlNC)
    {
        $this->controlNC = $controlNC;

        return $this;
    }

    /**
     * Get controlNC
     *
     * @return string 
     */
    public function getControlNC()
    {
        return $this->controlNC;
    }

    /**
     * Set controlTR
     *
     * @param string $controlTR
     * @return StatsMap
     */
    public function setControlTR($controlTR)
    {
        $this->controlTR = $controlTR;

        return $this;
    }

    /**
     * Get controlTR
     *
     * @return string 
     */
    public function getControlTR()
    {
        return $this->controlTR;
    }

    /**
     * Set zone
     *
     * @param string $zone
     * @return StatsMap
     */
    public function setZone($zone)
    {
        $this->zone = $zone;

        return $this;
    }

    /**
     * Get zone
     *
     * @return string 
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * Set world
     *
     * @param string $world
     * @return StatsMap
     */
    public function setWorld($world)
    {
        $this->world = $world;

        return $this;
    }

    /**
     * Get world
     *
     * @return string 
     */
    public function getWorld()
    {
        return $this->world;
    }

    /**
     * Set outfitCaptured
     *
     * @param string $outfitCaptured
     * @return StatsMap
     */
    public function setOutfitCaptured($outfitCaptured)
    {
        $this->outfitCaptured = $outfitCaptured;

        return $this;
    }

    /**
     * Get outfitCaptured
     *
     * @return string 
     */
    public function getOutfitCaptured()
    {
        return $this->outfitCaptured;
    }

    /**
     * Set match
     *
     * @param \PSB\AdminBundle\Entity\Matches $match
     * @return StatsMap
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
