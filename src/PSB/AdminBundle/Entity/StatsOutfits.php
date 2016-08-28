<?php

namespace PSB\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StatsOutfits
 *
 * @ORM\Table(name="ws_outfits")
 * @ORM\Entity
 */
class StatsOutfits
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
     * @ORM\ManyToOne(targetEntity="Matches", inversedBy="statsoutfits")
     * @ORM\JoinColumn(name="resultID", referencedColumnName="id")
     */
    private $match; 

    /**
     * @ORM\Column(name="outfitID", type="string", length=18)
    */
    private $outfitID;

    /**
     * @ORM\Column(name="outfitName", type="string", length=100)
    */
    private $outfitName;

    /**
     * @ORM\Column(name="outfitTag", type="string", length=5, nullable=true)
    */
    private $outfitTag;

    /**
     * @ORM\Column(name="outfitFaction", type="string", length=1)
    */
    private $outfitFaction;

    /**
     * @ORM\Column(name="outfitKills", type="smallint")
    */
    private $outfitKills;

    /**
    * @ORM\Column(name="outfitDeaths", type="smallint")
   */
   private $outfitDeaths;

   /**
    * @ORM\Column(name="outfitTKs", type="smallint")
   */
   private $outfitTKs;

   /**
    * @ORM\Column(name="outfitSuicides", type="smallint")
   */
   private $outfitSuicides;


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
     * Set outfitID
     *
     * @param string $outfitID
     * @return StatsOutfits
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

    /**
     * Set outfitName
     *
     * @param string $outfitName
     * @return StatsOutfits
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
     * @return StatsOutfits
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
     * @param string $outfitFaction
     * @return StatsOutfits
     */
    public function setOutfitFaction($outfitFaction)
    {
        $this->outfitFaction = $outfitFaction;

        return $this;
    }

    /**
     * Get outfitFaction
     *
     * @return string 
     */
    public function getOutfitFaction()
    {
        return $this->outfitFaction;
    }

    /**
     * Set outfitKills
     *
     * @param integer $outfitKills
     * @return StatsOutfits
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
     * @return StatsOutfits
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
     * @return StatsOutfits
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
     * @return StatsOutfits
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
     * Set match
     *
     * @param \PSB\AdminBundle\Entity\Matches $match
     * @return StatsOutfits
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
