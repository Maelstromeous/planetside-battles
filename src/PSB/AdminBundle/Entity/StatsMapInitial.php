<?php

namespace PSB\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StatsMapInitial
 *
 * @ORM\Table(name="ws_map_initial")
 * @ORM\Entity
 */
class StatsMapInitial
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
     * @ORM\ManyToOne(targetEntity="Matches", inversedBy="statsmapinitial")
     * @ORM\JoinColumn(name="resultID", referencedColumnName="id")
     */
    private $match;

    /**
     * @ORM\Column(name="worldID", type="string", length=2)
    */
    private $world;

    /**
     * @ORM\Column(name="zoneID", type="string", length=2)
    */
    private $zone;

    /**
     * @ORM\Column(name="facilityID", type="string", length=7)
    */
    private $facilityID;

    /**
     * @ORM\Column(name="facilityTypeID", type="string", length=2)
    */
    private $facilityTypeID;

    /**
     * @ORM\Column(name="facilityOwner", type="string", length=1)
    */
    private $facilityOwner;

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
     * Set world
     *
     * @param string $world
     * @return StatsMapInitial
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
     * Set zone
     *
     * @param string $zone
     * @return StatsMapInitial
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
     * Set facilityID
     *
     * @param string $facilityID
     * @return StatsMapInitial
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
     * Set facilityTypeID
     *
     * @param string $facilityTypeID
     * @return StatsMapInitial
     */
    public function setFacilityTypeID($facilityTypeID)
    {
        $this->facilityTypeID = $facilityTypeID;

        return $this;
    }

    /**
     * Get facilityTypeID
     *
     * @return string 
     */
    public function getFacilityTypeID()
    {
        return $this->facilityTypeID;
    }

    /**
     * Set facilityOwner
     *
     * @param string $facilityOwner
     * @return StatsMapInitial
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
     * Set match
     *
     * @param \PSB\AdminBundle\Entity\Matches $match
     * @return StatsMapInitial
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
