<?php

namespace PSB\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OutfitsTotal
 */
class OutfitsTotal
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $outfitName;

    /**
     * @var string
     */
    private $outfitTag;

    /**
     * @var tinyint
     */
    private $outfitFaction;

    /**
     * @var integer
     */
    private $outfitKills;

    /**
     * @var integer
     */
    private $outfitDeaths;

    /**
     * @var integer
     */
    private $outfitTKs;

    /**
     * @var integer
     */
    private $outfitSuicides;

    /**
     * @var string
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
     * @return OutfitsTotal
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
     * @return OutfitsTotal
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
     * @param \tinyint $outfitFaction
     * @return OutfitsTotal
     */
    public function setOutfitFaction(\tinyint $outfitFaction)
    {
        $this->outfitFaction = $outfitFaction;

        return $this;
    }

    /**
     * Get outfitFaction
     *
     * @return \tinyint 
     */
    public function getOutfitFaction()
    {
        return $this->outfitFaction;
    }

    /**
     * Set outfitKills
     *
     * @param integer $outfitKills
     * @return OutfitsTotal
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
     * @return OutfitsTotal
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
     * @return OutfitsTotal
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
     * @return OutfitsTotal
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
     * @return OutfitsTotal
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
}
