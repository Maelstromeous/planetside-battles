<?php

namespace PSB\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 *
 * @ORM\Table(name="ws_events")
 * @ORM\Entity
 */
class Events
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
     * @var integer
     *
     * @ORM\Column(name="startTime", type="string", length=20, nullable=false)
     */
    private $startTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="endTime", type="integer", length=20, nullable=false)
     */
    private $endTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint", nullable=false)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="approved", type="smallint", nullable=false)
     */
    private $approved;

    /**
     * @var integer
     *
     * @ORM\Column(name="processed", type="smallint", nullable=true)
     */
    private $processed;

    /**
     * @var integer
     *
     * @ORM\Column(name="map", type="integer", nullable=false)
     */
    private $map;

    /**
     * @var integer
     *
     * @ORM\Column(name="finished", type="integer", nullable=false)
     */
    private $finished;

    /**
     * @var integer
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var integer
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="world", type="integer", nullable=false)
     */
    private $world;

    /**
     * @var integer
     *
     * @ORM\Column(name="zone", type="smallint", nullable=false)
     */
    private $zone;

    /**
     * @var integer
     *
     * @ORM\Column(name="server1", type="smallint", nullable=true)
     */
    private $server1;

    /**
     * @var integer
     *
     * @ORM\Column(name="server1Faction", type="smallint", nullable=true)
     */
    private $server1faction;

    /**
     * @var integer
     *
     * @ORM\Column(name="server2", type="smallint", nullable=true)
     */
    private $server2;

    /**
     * @var integer
     *
     * @ORM\Column(name="server2Faction", type="smallint", nullable=true)
     */
    private $server2faction;

    /**
     * @var integer
     *
     * @ORM\Column(name="server3", type="smallint", nullable=true)
     */
    private $server3;

    /**
     * @var integer
     *
     * @ORM\Column(name="server3Faction", type="smallint", nullable=true)
     */
    private $server3faction;

    /**
     * @ORM\OneToOne(targetEntity="Matches", inversedBy="eventdata")
     * @ORM\JoinColumn(name="resultID", referencedColumnName="id")
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
     * Set startTime
     *
     * @param string $startTime
     * @return Events
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return string 
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endTime
     *
     * @param integer $endTime
     * @return Events
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get endTime
     *
     * @return integer 
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Events
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
     * Set approved
     *
     * @param integer $approved
     * @return Events
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;

        return $this;
    }

    /**
     * Get approved
     *
     * @return integer 
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * Set processed
     *
     * @param integer $processed
     * @return Events
     */
    public function setProcessed($processed)
    {
        $this->processed = $processed;

        return $this;
    }

    /**
     * Get processed
     *
     * @return integer 
     */
    public function getProcessed()
    {
        return $this->processed;
    }

    /**
     * Set map
     *
     * @param integer $map
     * @return Events
     */
    public function setMap($map)
    {
        $this->map = $map;

        return $this;
    }

    /**
     * Get map
     *
     * @return integer 
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * Set finished
     *
     * @param integer $finished
     * @return Events
     */
    public function setFinished($finished)
    {
        $this->finished = $finished;

        return $this;
    }

    /**
     * Get finished
     *
     * @return integer 
     */
    public function getFinished()
    {
        return $this->finished;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Events
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Events
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set world
     *
     * @param integer $world
     * @return Events
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
     * @return Events
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
     * Set server1
     *
     * @param integer $server1
     * @return Events
     */
    public function setServer1($server1)
    {
        $this->server1 = $server1;

        return $this;
    }

    /**
     * Get server1
     *
     * @return integer 
     */
    public function getServer1()
    {
        return $this->server1;
    }

    /**
     * Set server1faction
     *
     * @param integer $server1faction
     * @return Events
     */
    public function setServer1faction($server1faction)
    {
        $this->server1faction = $server1faction;

        return $this;
    }

    /**
     * Get server1faction
     *
     * @return integer 
     */
    public function getServer1faction()
    {
        return $this->server1faction;
    }

    /**
     * Set server2
     *
     * @param integer $server2
     * @return Events
     */
    public function setServer2($server2)
    {
        $this->server2 = $server2;

        return $this;
    }

    /**
     * Get server2
     *
     * @return integer 
     */
    public function getServer2()
    {
        return $this->server2;
    }

    /**
     * Set server2faction
     *
     * @param integer $server2faction
     * @return Events
     */
    public function setServer2faction($server2faction)
    {
        $this->server2faction = $server2faction;

        return $this;
    }

    /**
     * Get server2faction
     *
     * @return integer 
     */
    public function getServer2faction()
    {
        return $this->server2faction;
    }

    /**
     * Set server3
     *
     * @param integer $server3
     * @return Events
     */
    public function setServer3($server3)
    {
        $this->server3 = $server3;

        return $this;
    }

    /**
     * Get server3
     *
     * @return integer 
     */
    public function getServer3()
    {
        return $this->server3;
    }

    /**
     * Set server3faction
     *
     * @param integer $server3faction
     * @return Events
     */
    public function setServer3faction($server3faction)
    {
        $this->server3faction = $server3faction;

        return $this;
    }

    /**
     * Get server3faction
     *
     * @return integer 
     */
    public function getServer3faction()
    {
        return $this->server3faction;
    }

    /**
     * Set match
     *
     * @param \PSB\AdminBundle\Entity\Matches $match
     * @return Events
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
