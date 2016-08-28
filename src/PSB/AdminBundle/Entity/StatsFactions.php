<?php

namespace PSB\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * StatsFactions
 *
 * @ORM\Table(name="ws_factions")
 * @ORM\Entity
 */
class StatsFactions
{
    /**
     * @ORM\OneToOne(targetEntity="Matches", inversedBy="statsfactions")
     * @ORM\Id
     * @ORM\JoinColumn(name="resultID", referencedColumnName="id")
     */
    private $match; 

    /**
     * @var integer
     *
     * @ORM\Column(name="killsVS", type="integer", nullable=true)
     */
    protected $killsVS;

    /**
     * @var integer
     *
     * @ORM\Column(name="killsNC", type="integer", nullable=true)
     */
    protected $killsNC;

    /**
     * @var integer
     *
     * @ORM\Column(name="killsTR", type="integer", nullable=true)
     */
    protected $killsTR;

    /**
     * @var integer
     *
     * @ORM\Column(name="deathsVS", type="integer", nullable=true)
     */
    protected $deathsVS;

    /**
     * @var integer
     *
     * @ORM\Column(name="deathsNC", type="integer", nullable=true)
     */
    protected $deathsNC;

    /**
     * @var integer
     *
     * @ORM\Column(name="deathsTR", type="integer", nullable=true)
     */
    protected $deathsTR;

    /**
     * @var integer
     *
     * @ORM\Column(name="teamKillsVS", type="integer", nullable=true)
     */
    protected $teamKillsVS;

    /**
     * @var integer
     *
     * @ORM\Column(name="teamKillsNC", type="integer", nullable=true)
     */
    protected $teamKillsNC;

    /**
     * @var integer
     *
     * @ORM\Column(name="teamKillsTR", type="integer", nullable=true)
     */
    protected $teamKillsTR;

    /**
     * @var integer
     *
     * @ORM\Column(name="suicidesVS", type="integer", nullable=true)
     */
    protected $suicidesVS;

    /**
     * @var integer
     *
     * @ORM\Column(name="suicidesNC", type="integer", nullable=true)
     */
    protected $suicidesNC;

    /**
     * @var integer
     *
     * @ORM\Column(name="suicidesTR", type="integer", nullable=true)
     */
    protected $suicidesTR;

    /**
     * @var integer
     *
     * @ORM\Column(name="totalKills", type="integer", nullable=true)
     */
    protected $totalKills;

    /**
     * @var integer
     *
     * @ORM\Column(name="totalDeaths", type="integer", nullable=true)
     */
    protected $totalDeaths;

    /**
     * @var integer
     *
     * @ORM\Column(name="totalTKs", type="integer", nullable=true)
     */
    protected $totalTKs;

    /**
     * @var integer
     *
     * @ORM\Column(name="totalSuicides", type="integer", nullable=true)
     */
    protected $totalSuicides;

    /**
     * Set killsVS
     *
     * @param integer $killsVS
     * @return StatsFactions
     */
    public function setKillsVS($killsVS)
    {
        $this->killsVS = $killsVS;

        return $this;
    }

    /**
     * Get killsVS
     *
     * @return integer 
     */
    public function getKillsVS()
    {
        return $this->killsVS;
    }

    /**
     * Set killsNC
     *
     * @param integer $killsNC
     * @return StatsFactions
     */
    public function setKillsNC($killsNC)
    {
        $this->killsNC = $killsNC;

        return $this;
    }

    /**
     * Get killsNC
     *
     * @return integer 
     */
    public function getKillsNC()
    {
        return $this->killsNC;
    }

    /**
     * Set killsTR
     *
     * @param integer $killsTR
     * @return StatsFactions
     */
    public function setKillsTR($killsTR)
    {
        $this->killsTR = $killsTR;

        return $this;
    }

    /**
     * Get killsTR
     *
     * @return integer 
     */
    public function getKillsTR()
    {
        return $this->killsTR;
    }

    /**
     * Set deathsVS
     *
     * @param integer $deathsVS
     * @return StatsFactions
     */
    public function setDeathsVS($deathsVS)
    {
        $this->deathsVS = $deathsVS;

        return $this;
    }

    /**
     * Get deathsVS
     *
     * @return integer 
     */
    public function getDeathsVS()
    {
        return $this->deathsVS;
    }

    /**
     * Set deathsNC
     *
     * @param integer $deathsNC
     * @return StatsFactions
     */
    public function setDeathsNC($deathsNC)
    {
        $this->deathsNC = $deathsNC;

        return $this;
    }

    /**
     * Get deathsNC
     *
     * @return integer 
     */
    public function getDeathsNC()
    {
        return $this->deathsNC;
    }

    /**
     * Set deathsTR
     *
     * @param integer $deathsTR
     * @return StatsFactions
     */
    public function setDeathsTR($deathsTR)
    {
        $this->deathsTR = $deathsTR;

        return $this;
    }

    /**
     * Get deathsTR
     *
     * @return integer 
     */
    public function getDeathsTR()
    {
        return $this->deathsTR;
    }

    /**
     * Set teamKillsVS
     *
     * @param integer $teamKillsVS
     * @return StatsFactions
     */
    public function setTeamKillsVS($teamKillsVS)
    {
        $this->teamKillsVS = $teamKillsVS;

        return $this;
    }

    /**
     * Get teamKillsVS
     *
     * @return integer 
     */
    public function getTeamKillsVS()
    {
        return $this->teamKillsVS;
    }

    /**
     * Set teamKillsNC
     *
     * @param integer $teamKillsNC
     * @return StatsFactions
     */
    public function setTeamKillsNC($teamKillsNC)
    {
        $this->teamKillsNC = $teamKillsNC;

        return $this;
    }

    /**
     * Get teamKillsNC
     *
     * @return integer 
     */
    public function getTeamKillsNC()
    {
        return $this->teamKillsNC;
    }

    /**
     * Set teamKillsTR
     *
     * @param integer $teamKillsTR
     * @return StatsFactions
     */
    public function setTeamKillsTR($teamKillsTR)
    {
        $this->teamKillsTR = $teamKillsTR;

        return $this;
    }

    /**
     * Get teamKillsTR
     *
     * @return integer 
     */
    public function getTeamKillsTR()
    {
        return $this->teamKillsTR;
    }

    /**
     * Set suicidesVS
     *
     * @param integer $suicidesVS
     * @return StatsFactions
     */
    public function setSuicidesVS($suicidesVS)
    {
        $this->suicidesVS = $suicidesVS;

        return $this;
    }

    /**
     * Get suicidesVS
     *
     * @return integer 
     */
    public function getSuicidesVS()
    {
        return $this->suicidesVS;
    }

    /**
     * Set suicidesNC
     *
     * @param integer $suicidesNC
     * @return StatsFactions
     */
    public function setSuicidesNC($suicidesNC)
    {
        $this->suicidesNC = $suicidesNC;

        return $this;
    }

    /**
     * Get suicidesNC
     *
     * @return integer 
     */
    public function getSuicidesNC()
    {
        return $this->suicidesNC;
    }

    /**
     * Set suicidesTR
     *
     * @param integer $suicidesTR
     * @return StatsFactions
     */
    public function setSuicidesTR($suicidesTR)
    {
        $this->suicidesTR = $suicidesTR;

        return $this;
    }

    /**
     * Get suicidesTR
     *
     * @return integer 
     */
    public function getSuicidesTR()
    {
        return $this->suicidesTR;
    }

    /**
     * Set totalKills
     *
     * @param integer $totalKills
     * @return StatsFactions
     */
    public function setTotalKills($totalKills)
    {
        $this->totalKills = $totalKills;

        return $this;
    }

    /**
     * Get totalKills
     *
     * @return integer 
     */
    public function getTotalKills()
    {
        return $this->totalKills;
    }

    /**
     * Set totalDeaths
     *
     * @param integer $totalDeaths
     * @return StatsFactions
     */
    public function setTotalDeaths($totalDeaths)
    {
        $this->totalDeaths = $totalDeaths;

        return $this;
    }

    /**
     * Get totalDeaths
     *
     * @return integer 
     */
    public function getTotalDeaths()
    {
        return $this->totalDeaths;
    }

    /**
     * Set totalTKs
     *
     * @param integer $totalTKs
     * @return StatsFactions
     */
    public function setTotalTKs($totalTKs)
    {
        $this->totalTKs = $totalTKs;

        return $this;
    }

    /**
     * Get totalTKs
     *
     * @return integer 
     */
    public function getTotalTKs()
    {
        return $this->totalTKs;
    }

    /**
     * Set totalSuicides
     *
     * @param integer $totalSuicides
     * @return StatsFactions
     */
    public function setTotalSuicides($totalSuicides)
    {
        $this->totalSuicides = $totalSuicides;

        return $this;
    }

    /**
     * Get totalSuicides
     *
     * @return integer 
     */
    public function getTotalSuicides()
    {
        return $this->totalSuicides;
    }

    /**
     * Set match
     *
     * @param \PSB\AdminBundle\Entity\Matches $match
     * @return StatsFactions
     */
    public function setMatch(\PSB\AdminBundle\Entity\Matches $match)
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
