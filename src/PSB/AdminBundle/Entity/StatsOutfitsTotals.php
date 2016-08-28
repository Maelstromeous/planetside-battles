<?php

namespace PSB\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StatsOutfitsTotals
 *
 * @ORM\Table(name="ws_outfits_total")
 * @ORM\Entity
 */
class StatsOutfitsTotals
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
     * @ORM\Column(name="outfitID", type="string", length=32)
    */
    private $outfitID;

    /**
     * @ORM\Column(name="outfitName", type="string", length=32)
    */
    private $outfitName;

    /**
     * @ORM\Column(name="outfitTag", type="string", length=5, nullable=true)
    */
    private $outfitTag;

    /**
     * @ORM\Column(name="outfitFaction", type="smallint")
    */
    private $outfitFaction;

    /**
     * @ORM\Column(name="outfitKills", type="integer")
    */
    private $outfitKills;

    /**
     * @ORM\Column(name="outfitDeaths", type="integer")
    */
    private $outfitDeaths;

    /**
     * @ORM\Column(name="outfitTKs", type="integer")
    */
    private $outfitTKs;

    /**
     * @ORM\Column(name="outfitSuicides", type="integer")
    */
    private $outfitSuicides;

    /**
     * @ORM\Column(name="outfitServer", type="string", length=2)
    */
    private $outfitServer;

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
     * Set outfitName
     *
     * @param string $outfitName
     * @return StatsOutfitsTotals
     */
    public function setOutfitName($outfitName)
    {
        $this->outfitName = $outfitName;

        return $this;
    }

    /**
     * Get outfitName
     *
     * @return string 
     */
    public function getOutfitName()
    {
        return $this->outfitName;
    }

    /**
     * Set outfitTag
     *
     * @param string $outfitTag
     * @return StatsOutfitsTotals
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
     * Set outfitFaction
     *
     * @param integer $outfitFaction
     * @return StatsOutfitsTotals
     */
    public function setOutfitFaction($outfitFaction)
    {
        $this->outfitFaction = $outfitFaction;

        return $this;
    }

    /**
     * Get outfitFaction
     *
     * @return integer 
     */
    public function getOutfitFaction()
    {
        return $this->outfitFaction;
    }

    /**
     * Set outfitKills
     *
     * @param integer $outfitKills
     * @return StatsOutfitsTotals
     */
    public function setOutfitKills($outfitKills)
    {
        $this->outfitKills = $outfitKills;

        return $this;
    }

    /**
     * Get outfitKills
     *
     * @return integer 
     */
    public function getOutfitKills()
    {
        return $this->outfitKills;
    }

    /**
     * Set outfitDeaths
     *
     * @param integer $outfitDeaths
     * @return StatsOutfitsTotals
     */
    public function setOutfitDeaths($outfitDeaths)
    {
        $this->outfitDeaths = $outfitDeaths;

        return $this;
    }

    /**
     * Get outfitDeaths
     *
     * @return integer 
     */
    public function getOutfitDeaths()
    {
        return $this->outfitDeaths;
    }

    /**
     * Set outfitTKs
     *
     * @param integer $outfitTKs
     * @return StatsOutfitsTotals
     */
    public function setOutfitTKs($outfitTKs)
    {
        $this->outfitTKs = $outfitTKs;

        return $this;
    }

    /**
     * Get outfitTKs
     *
     * @return integer 
     */
    public function getOutfitTKs()
    {
        return $this->outfitTKs;
    }

    /**
     * Set outfitSuicides
     *
     * @param integer $outfitSuicides
     * @return StatsOutfitsTotals
     */
    public function setOutfitSuicides($outfitSuicides)
    {
        $this->outfitSuicides = $outfitSuicides;

        return $this;
    }

    /**
     * Get outfitSuicides
     *
     * @return integer 
     */
    public function getOutfitSuicides()
    {
        return $this->outfitSuicides;
    }

    /**
     * Set outfitServer
     *
     * @param string $outfitServer
     * @return StatsOutfitsTotals
     */
    public function setOutfitServer($outfitServer)
    {
        $this->outfitServer = $outfitServer;

        return $this;
    }

    /**
     * Get outfitServer
     *
     * @return string 
     */
    public function getOutfitServer()
    {
        return $this->outfitServer;
    }

    /**
     * Set outfitID
     *
     * @param string $outfitID
     * @return StatsOutfitsTotals
     */
    public function setOutfitID($outfitID)
    {
        $this->outfitID = $outfitID;

        return $this;
    }

    /**
     * Get outfitID
     *
     * @return string 
     */
    public function getOutfitID()
    {
        return $this->outfitID;
    }
}
