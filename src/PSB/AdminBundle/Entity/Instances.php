<?php

namespace PSB\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Instances
 *
 * @ORM\Table(name="ws_instances")
 * @ORM\Entity
 */
class Instances
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Matches", inversedBy="instance")
     * @ORM\JoinColumn(name="resultID", referencedColumnName="id")
     */
    private $match;

    /**
    * @ORM\Column(name="world", type="smallint", nullable=false)
    */
    private $world;

    /**
    * @ORM\Column(name="zone", type="smallint", nullable=false)
    */
    private $zone;

    /**
    * @ORM\Column(name="started", type="integer", nullable=false)
    */
    private $started;

    /**
    * @ORM\Column(name="endtime", type="integer", nullable=false)
    */
    private $endtime;

    /**
    * @ORM\Column(name="type", type="smallint", nullable=false)
    */
    private $type;

    /**
    * @ORM\Column(name="controlVS", type="smallint", nullable=false)
    */
    private $controlVS;

    /**
    * @ORM\Column(name="controlNC", type="smallint", nullable=false)
    */
    private $controlNC;

    /**
    * @ORM\Column(name="controlTR", type="smallint", nullable=false)
    */
    private $controlTR;

    /**
    * @ORM\Column(name="event", type="boolean")
    */
    private $event;

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
     * @param integer $world
     * @return Instances
     */
    public function setWorld($world)
    {
        $this->world = $world;

        return $this;
    }

    /**
     * Get world
     *
     * @return integer 
     */
    public function getWorld()
    {
        return $this->world;
    }

    /**
     * Set zone
     *
     * @param integer $zone
     * @return Instances
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
     * Set started
     *
     * @param integer $started
     * @return Instances
     */
    public function setStarted($started)
    {
        $this->started = $started;

        return $this;
    }

    /**
     * Get started
     *
     * @return integer 
     */
    public function getStarted()
    {
        return $this->started;
    }

    /**
     * Set endtime
     *
     * @param integer $endtime
     * @return Instances
     */
    public function setEndtime($endtime)
    {
        $this->endtime = $endtime;

        return $this;
    }

    /**
     * Get endtime
     *
     * @return integer 
     */
    public function getEndtime()
    {
        return $this->endtime;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Instances
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set controlVS
     *
     * @param integer $controlVS
     * @return Instances
     */
    public function setControlVS($controlVS)
    {
        $this->controlVS = $controlVS;

        return $this;
    }

    /**
     * Get controlVS
     *
     * @return integer 
     */
    public function getControlVS()
    {
        return $this->controlVS;
    }

    /**
     * Set controlNC
     *
     * @param integer $controlNC
     * @return Instances
     */
    public function setControlNC($controlNC)
    {
        $this->controlNC = $controlNC;

        return $this;
    }

    /**
     * Get controlNC
     *
     * @return integer 
     */
    public function getControlNC()
    {
        return $this->controlNC;
    }

    /**
     * Set controlTR
     *
     * @param integer $controlTR
     * @return Instances
     */
    public function setControlTR($controlTR)
    {
        $this->controlTR = $controlTR;

        return $this;
    }

    /**
     * Get controlTR
     *
     * @return integer 
     */
    public function getControlTR()
    {
        return $this->controlTR;
    }

    /**
     * Set event
     *
     * @param boolean $event
     * @return Instances
     */
    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return boolean 
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set match
     *
     * @param \PSB\AdminBundle\Entity\Matches $match
     * @return Instances
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
