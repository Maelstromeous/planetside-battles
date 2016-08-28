<?php

namespace PSB\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Matches
 *
 * @ORM\Table(name="Matches")
 * @ORM\Entity
 */
class Matches
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    protected $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer", nullable=false)
     */
    protected $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="startTime", type="integer", nullable=false)
     */
    protected $startTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="endTime", type="integer", nullable=false)
     */
    protected $endTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="server", type="integer", nullable=true)
     */
    protected $server;

    /**
     * @var integer
     *
     * @ORM\Column(name="continent", type="integer", nullable=false)
     */
    protected $continent;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="statsAvailable", type="boolean", options={"default" = 1})
     */
    protected $statsAvailable;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\OneToMany(targetEntity="Articles", mappedBy="match")
    */
    protected $articles;

    /**
     * @ORM\OneToMany(targetEntity="VODs", mappedBy="match")
    */
    protected $vods;

    // STATS

    /**
     * @ORM\OneToOne(targetEntity="StatsFactions", mappedBy="match")
    */
    protected $statsfactions;

    /**
     * @ORM\OneToMany(targetEntity="StatsPlayers", mappedBy="match")
    */
    protected $statsplayers;

    /**
     * @ORM\OneToMany(targetEntity="StatsMap", mappedBy="match")
    */
    protected $statsmap;

    /**
     * @ORM\OneToMany(targetEntity="StatsMapInitial", mappedBy="match")
    */
    protected $statsmapinitial;

    /**
     * @ORM\OneToMany(targetEntity="StatsOutfits", mappedBy="match")
    */
    protected $statsoutfits;

    /**
     * @ORM\OneToMany(targetEntity="StatsVehiclesTotals", mappedBy="match")
    */
    protected $statsvehicles;

    /**
     * @ORM\OneToMany(targetEntity="StatsWeapons", mappedBy="match")
    */
    protected $statsweapons;

    /**
     * @ORM\OneToMany(targetEntity="StatsWeaponsTotals", mappedBy="match")
    */
    protected $statsweaponstotals;

    /**
     * @ORM\OneToMany(targetEntity="StatsExperience", mappedBy="match")
    */
    protected $statsexperience;

    /**
     * @ORM\OneToMany(targetEntity="CombatHistory", mappedBy="match")
    */
    protected $statscombathistory;

    /**
     * @ORM\OneToOne(targetEntity="MatchesServerSmash", mappedBy="match")
    */
    protected $serversmashdata;

    /**
     * @ORM\OneToMany(targetEntity="SSPlayers", mappedBy="match")
    */
    protected $characters;

    /**
     * @ORM\OneToOne(targetEntity="Instances", mappedBy="match")
    */
    protected $instance;

    /**
     * @ORM\OneToOne(targetEntity="Events", mappedBy="match")
    */
    protected $eventdata;

    public function __toString()
    {
        return "TO COMPLETE";
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->vods = new \Doctrine\Common\Collections\ArrayCollection();
        $this->statsplayers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->statsmap = new \Doctrine\Common\Collections\ArrayCollection();
        $this->statsmapinitial = new \Doctrine\Common\Collections\ArrayCollection();
        $this->statsoutfits = new \Doctrine\Common\Collections\ArrayCollection();
        $this->statsvehicles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->statsweapons = new \Doctrine\Common\Collections\ArrayCollection();
        $this->statsweaponstotals = new \Doctrine\Common\Collections\ArrayCollection();
        $this->statsexperience = new \Doctrine\Common\Collections\ArrayCollection();
        $this->statscombathistory = new \Doctrine\Common\Collections\ArrayCollection();
        $this->characters = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set status
     *
     * @param integer $status
     * @return Matches
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Matches
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
     * Set startTime
     *
     * @param integer $startTime
     * @return Matches
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return integer
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endTime
     *
     * @param integer $endTime
     * @return Matches
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
     * Set server
     *
     * @param integer $server
     * @return Matches
     */
    public function setServer($server)
    {
        $this->server = $server;

        return $this;
    }

    /**
     * Get server
     *
     * @return integer
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Set continent
     *
     * @param integer $continent
     * @return Matches
     */
    public function setContinent($continent)
    {
        $this->continent = $continent;

        return $this;
    }

    /**
     * Get continent
     *
     * @return integer
     */
    public function getContinent()
    {
        return $this->continent;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Matches
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
     * Set statsAvailable
     *
     * @param boolean $statsAvailable
     * @return Matches
     */
    public function setStatsAvailable($statsAvailable)
    {
        $this->statsAvailable = $statsAvailable;

        return $this;
    }

    /**
     * Get statsAvailable
     *
     * @return boolean
     */
    public function getStatsAvailable()
    {
        return $this->statsAvailable;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Matches
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
     * Add articles
     *
     * @param \PSB\AdminBundle\Entity\Articles $articles
     * @return Matches
     */
    public function addArticle(\PSB\AdminBundle\Entity\Articles $articles)
    {
        $this->articles[] = $articles;

        return $this;
    }

    /**
     * Remove articles
     *
     * @param \PSB\AdminBundle\Entity\Articles $articles
     */
    public function removeArticle(\PSB\AdminBundle\Entity\Articles $articles)
    {
        $this->articles->removeElement($articles);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * Add vods
     *
     * @param \PSB\AdminBundle\Entity\VODs $vods
     * @return Matches
     */
    public function addVod(\PSB\AdminBundle\Entity\VODs $vods)
    {
        $this->vods[] = $vods;

        return $this;
    }

    /**
     * Remove vods
     *
     * @param \PSB\AdminBundle\Entity\VODs $vods
     */
    public function removeVod(\PSB\AdminBundle\Entity\VODs $vods)
    {
        $this->vods->removeElement($vods);
    }

    /**
     * Get vods
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVods()
    {
        return $this->vods;
    }

    /**
     * Set statsfactions
     *
     * @param \PSB\AdminBundle\Entity\StatsFactions $statsfactions
     * @return Matches
     */
    public function setStatsfactions(\PSB\AdminBundle\Entity\StatsFactions $statsfactions = null)
    {
        $this->statsfactions = $statsfactions;

        return $this;
    }

    /**
     * Get statsfactions
     *
     * @return \PSB\AdminBundle\Entity\StatsFactions
     */
    public function getStatsfactions()
    {
        return $this->statsfactions;
    }

    /**
     * Add statsplayers
     *
     * @param \PSB\AdminBundle\Entity\StatsPlayers $statsplayers
     * @return Matches
     */
    public function addStatsplayer(\PSB\AdminBundle\Entity\StatsPlayers $statsplayers)
    {
        $this->statsplayers[] = $statsplayers;

        return $this;
    }

    /**
     * Remove statsplayers
     *
     * @param \PSB\AdminBundle\Entity\StatsPlayers $statsplayers
     */
    public function removeStatsplayer(\PSB\AdminBundle\Entity\StatsPlayers $statsplayers)
    {
        $this->statsplayers->removeElement($statsplayers);
    }

    /**
     * Get statsplayers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStatsplayers()
    {
        return $this->statsplayers;
    }

    /**
     * Add statsmap
     *
     * @param \PSB\AdminBundle\Entity\StatsMap $statsmap
     * @return Matches
     */
    public function addStatsmap(\PSB\AdminBundle\Entity\StatsMap $statsmap)
    {
        $this->statsmap[] = $statsmap;

        return $this;
    }

    /**
     * Remove statsmap
     *
     * @param \PSB\AdminBundle\Entity\StatsMap $statsmap
     */
    public function removeStatsmap(\PSB\AdminBundle\Entity\StatsMap $statsmap)
    {
        $this->statsmap->removeElement($statsmap);
    }

    /**
     * Get statsmap
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStatsmap()
    {
        return $this->statsmap;
    }

    /**
     * Add statsmapinitial
     *
     * @param \PSB\AdminBundle\Entity\StatsMapInitial $statsmapinitial
     * @return Matches
     */
    public function addStatsmapinitial(\PSB\AdminBundle\Entity\StatsMapInitial $statsmapinitial)
    {
        $this->statsmapinitial[] = $statsmapinitial;

        return $this;
    }

    /**
     * Remove statsmapinitial
     *
     * @param \PSB\AdminBundle\Entity\StatsMapInitial $statsmapinitial
     */
    public function removeStatsmapinitial(\PSB\AdminBundle\Entity\StatsMapInitial $statsmapinitial)
    {
        $this->statsmapinitial->removeElement($statsmapinitial);
    }

    /**
     * Get statsmapinitial
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStatsmapinitial()
    {
        return $this->statsmapinitial;
    }

    /**
     * Add statsoutfits
     *
     * @param \PSB\AdminBundle\Entity\StatsOutfits $statsoutfits
     * @return Matches
     */
    public function addStatsoutfit(\PSB\AdminBundle\Entity\StatsOutfits $statsoutfits)
    {
        $this->statsoutfits[] = $statsoutfits;

        return $this;
    }

    /**
     * Remove statsoutfits
     *
     * @param \PSB\AdminBundle\Entity\StatsOutfits $statsoutfits
     */
    public function removeStatsoutfit(\PSB\AdminBundle\Entity\StatsOutfits $statsoutfits)
    {
        $this->statsoutfits->removeElement($statsoutfits);
    }

    /**
     * Get statsoutfits
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStatsoutfits()
    {
        return $this->statsoutfits;
    }

    /**
     * Add statsvehicles
     *
     * @param \PSB\AdminBundle\Entity\StatsVehiclesTotals $statsvehicles
     * @return Matches
     */
    public function addStatsvehicle(\PSB\AdminBundle\Entity\StatsVehiclesTotals $statsvehicles)
    {
        $this->statsvehicles[] = $statsvehicles;

        return $this;
    }

    /**
     * Remove statsvehicles
     *
     * @param \PSB\AdminBundle\Entity\StatsVehiclesTotals $statsvehicles
     */
    public function removeStatsvehicle(\PSB\AdminBundle\Entity\StatsVehiclesTotals $statsvehicles)
    {
        $this->statsvehicles->removeElement($statsvehicles);
    }

    /**
     * Get statsvehicles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStatsvehicles()
    {
        return $this->statsvehicles;
    }

    /**
     * Add statsweapons
     *
     * @param \PSB\AdminBundle\Entity\StatsWeapons $statsweapons
     * @return Matches
     */
    public function addStatsweapon(\PSB\AdminBundle\Entity\StatsWeapons $statsweapons)
    {
        $this->statsweapons[] = $statsweapons;

        return $this;
    }

    /**
     * Remove statsweapons
     *
     * @param \PSB\AdminBundle\Entity\StatsWeapons $statsweapons
     */
    public function removeStatsweapon(\PSB\AdminBundle\Entity\StatsWeapons $statsweapons)
    {
        $this->statsweapons->removeElement($statsweapons);
    }

    /**
     * Get statsweapons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStatsweapons()
    {
        return $this->statsweapons;
    }

    /**
     * Add statsweaponstotals
     *
     * @param \PSB\AdminBundle\Entity\StatsWeaponsTotals $statsweaponstotals
     * @return Matches
     */
    public function addStatsweaponstotal(\PSB\AdminBundle\Entity\StatsWeaponsTotals $statsweaponstotals)
    {
        $this->statsweaponstotals[] = $statsweaponstotals;

        return $this;
    }

    /**
     * Remove statsweaponstotals
     *
     * @param \PSB\AdminBundle\Entity\StatsWeaponsTotals $statsweaponstotals
     */
    public function removeStatsweaponstotal(\PSB\AdminBundle\Entity\StatsWeaponsTotals $statsweaponstotals)
    {
        $this->statsweaponstotals->removeElement($statsweaponstotals);
    }

    /**
     * Get statsweaponstotals
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStatsweaponstotals()
    {
        return $this->statsweaponstotals;
    }

    /**
     * Add statsexperience
     *
     * @param \PSB\AdminBundle\Entity\StatsExperience $statsexperience
     * @return Matches
     */
    public function addStatsexperience(\PSB\AdminBundle\Entity\StatsExperience $statsexperience)
    {
        $this->statsexperience[] = $statsexperience;

        return $this;
    }

    /**
     * Remove statsexperience
     *
     * @param \PSB\AdminBundle\Entity\StatsExperience $statsexperience
     */
    public function removeStatsexperience(\PSB\AdminBundle\Entity\StatsExperience $statsexperience)
    {
        $this->statsexperience->removeElement($statsexperience);
    }

    /**
     * Get statsexperience
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStatsexperience()
    {
        return $this->statsexperience;
    }

    /**
     * Add statscombathistory
     *
     * @param \PSB\AdminBundle\Entity\CombatHistory $statscombathistory
     * @return Matches
     */
    public function addStatscombathistory(\PSB\AdminBundle\Entity\CombatHistory $statscombathistory)
    {
        $this->statscombathistory[] = $statscombathistory;

        return $this;
    }

    /**
     * Remove statscombathistory
     *
     * @param \PSB\AdminBundle\Entity\CombatHistory $statscombathistory
     */
    public function removeStatscombathistory(\PSB\AdminBundle\Entity\CombatHistory $statscombathistory)
    {
        $this->statscombathistory->removeElement($statscombathistory);
    }

    /**
     * Get statscombathistory
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStatscombathistory()
    {
        return $this->statscombathistory;
    }

    /**
     * Set serversmashdata
     *
     * @param \PSB\AdminBundle\Entity\MatchesServerSmash $serversmashdata
     * @return Matches
     */
    public function setServersmashdata(\PSB\AdminBundle\Entity\MatchesServerSmash $serversmashdata = null)
    {
        $this->serversmashdata = $serversmashdata;

        return $this;
    }

    /**
     * Get serversmashdata
     *
     * @return \PSB\AdminBundle\Entity\MatchesServerSmash
     */
    public function getServersmashdata()
    {
        return $this->serversmashdata;
    }

    /**
     * Add characters
     *
     * @param \PSB\AdminBundle\Entity\SSPlayers $characters
     * @return Matches
     */
    public function addCharacter(\PSB\AdminBundle\Entity\SSPlayers $characters)
    {
        $this->characters[] = $characters;

        return $this;
    }

    /**
     * Remove characters
     *
     * @param \PSB\AdminBundle\Entity\SSPlayers $characters
     */
    public function removeCharacter(\PSB\AdminBundle\Entity\SSPlayers $characters)
    {
        $this->characters->removeElement($characters);
    }

    /**
     * Get characters
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCharacters()
    {
        return $this->characters;
    }

    /**
     * Set instance
     *
     * @param \PSB\AdminBundle\Entity\Instances $instance
     * @return Matches
     */
    public function setInstance(\PSB\AdminBundle\Entity\Instances $instance = null)
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get instance
     *
     * @return \PSB\AdminBundle\Entity\Instances
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Set eventdata
     *
     * @param \PSB\AdminBundle\Entity\Events $eventdata
     * @return Matches
     */
    public function setEventdata(\PSB\AdminBundle\Entity\Events $eventdata = null)
    {
        $this->eventdata = $eventdata;

        return $this;
    }

    /**
     * Get eventdata
     *
     * @return \PSB\AdminBundle\Entity\Events
     */
    public function getEventdata()
    {
        return $this->eventdata;
    }
}
