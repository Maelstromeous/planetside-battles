<?php

namespace PSB\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * StatsVehiclesTotals
 *
 * @ORM\Table(name="ws_vehicles_totals", uniqueConstraints={@UniqueConstraint(name="vehicleResultTotals", columns={"vehicleID", "resultID"})})
 * @ORM\Entity
 */
class StatsVehiclesTotals
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
     * @ORM\ManyToOne(targetEntity="Matches", inversedBy="statsvehicles")
     * @ORM\JoinColumn(name="resultID", referencedColumnName="id")
     */
    private $match;

    /**
     * @ORM\Column(name="vehicleID", type="smallint")
    */
    private $vehicleID;

    /**
     * @ORM\Column(name="killCount", type="smallint")
    */
    private $killCount;

    /**
     * @ORM\Column(name="killICount", type="smallint")
    */
    private $killICount;

    /**
     * @ORM\Column(name="killVCount", type="smallint")
    */
    private $killVCount;

    /**
     * @ORM\Column(name="deathCount", type="smallint")
    */
    private $deathCount;

    /**
     * @ORM\Column(name="deathICount", type="smallint")
    */
    private $deathICount;

    /**
     * @ORM\Column(name="deathVCount", type="smallint")
    */
    private $deathVCount;

    /**
     * @ORM\Column(name="bails", type="smallint")
    */
    private $bails;

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
     * Set vehicleID
     *
     * @param integer $vehicleID
     * @return StatsVehiclesTotals
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
     * Set killCount
     *
     * @param integer $killCount
     * @return StatsVehiclesTotals
     */
    public function setKillCount($killCount)
    {
        $this->killCount = $killCount;

        return $this;
    }

    /**
     * Get killCount
     *
     * @return integer
     */
    public function getKillCount()
    {
        return $this->killCount;
    }

    /**
     * Set killICount
     *
     * @param integer $killICount
     * @return StatsVehiclesTotals
     */
    public function setKillICount($killICount)
    {
        $this->killICount = $killICount;

        return $this;
    }

    /**
     * Get killICount
     *
     * @return integer
     */
    public function getKillICount()
    {
        return $this->killICount;
    }

    /**
     * Set killVCount
     *
     * @param integer $killVCount
     * @return StatsVehiclesTotals
     */
    public function setKillVCount($killVCount)
    {
        $this->killVCount = $killVCount;

        return $this;
    }

    /**
     * Get killVCount
     *
     * @return integer
     */
    public function getKillVCount()
    {
        return $this->killVCount;
    }

    /**
     * Set deathCount
     *
     * @param integer $deathCount
     * @return StatsVehiclesTotals
     */
    public function setDeathCount($deathCount)
    {
        $this->deathCount = $deathCount;

        return $this;
    }

    /**
     * Get deathCount
     *
     * @return integer
     */
    public function getDeathCount()
    {
        return $this->deathCount;
    }

    /**
     * Set deathICount
     *
     * @param integer $deathICount
     * @return StatsVehiclesTotals
     */
    public function setDeathICount($deathICount)
    {
        $this->deathICount = $deathICount;

        return $this;
    }

    /**
     * Get deathICount
     *
     * @return integer
     */
    public function getDeathICount()
    {
        return $this->deathICount;
    }

    /**
     * Set deathVCount
     *
     * @param integer $deathVCount
     * @return StatsVehiclesTotals
     */
    public function setDeathVCount($deathVCount)
    {
        $this->deathVCount = $deathVCount;

        return $this;
    }

    /**
     * Get deathVCount
     *
     * @return integer
     */
    public function getDeathVCount()
    {
        return $this->deathVCount;
    }

    /**
     * Set bails
     *
     * @param integer $bails
     * @return StatsVehiclesTotals
     */
    public function setBails($bails)
    {
        $this->bails = $bails;

        return $this;
    }

    /**
     * Get bails
     *
     * @return integer
     */
    public function getBails()
    {
        return $this->bails;
    }

    /**
     * Set match
     *
     * @param \PSB\AdminBundle\Entity\Matches $match
     * @return StatsVehiclesTotals
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
