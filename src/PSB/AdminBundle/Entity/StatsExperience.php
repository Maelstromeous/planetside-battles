<?php

namespace PSB\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StatsExperience
 *
 * @ORM\Table(name="ws_xp")
 * @ORM\Entity
 */
class StatsExperience
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
     * @var string
     *
     * @ORM\Column(name="playerID", type="string", length=20, nullable=false)
     */
    private $playerID;

    /**
     * @var string
     *
     * @ORM\Column(name="xpID", type="string", length=3, nullable=false)
     */
    private $xpID;

    /**
     * @var string
     *
     * @ORM\Column(name="occurances", type="integer", nullable=false)
     */
    private $occurances;

    /**
     * @ORM\ManyToOne(targetEntity="Matches", inversedBy="statsexperience")
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
     * Set playerID
     *
     * @param string $playerID
     * @return StatsExperience
     */
    public function setPlayerID($playerID)
    {
        $this->playerID = $playerID;

        return $this;
    }

    /**
     * Get playerID
     *
     * @return string 
     */
    public function getPlayerID()
    {
        return $this->playerID;
    }

    /**
     * Set xpID
     *
     * @param string $xpID
     * @return StatsExperience
     */
    public function setXpID($xpID)
    {
        $this->xpID = $xpID;

        return $this;
    }

    /**
     * Get xpID
     *
     * @return string 
     */
    public function getXpID()
    {
        return $this->xpID;
    }

    /**
     * Set occurances
     *
     * @param integer $occurances
     * @return StatsExperience
     */
    public function setOccurances($occurances)
    {
        $this->occurances = $occurances;

        return $this;
    }

    /**
     * Get occurances
     *
     * @return integer 
     */
    public function getOccurances()
    {
        return $this->occurances;
    }

    /**
     * Set match
     *
     * @param \PSB\AdminBundle\Entity\Matches $match
     * @return StatsExperience
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
