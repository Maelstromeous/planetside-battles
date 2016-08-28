<?php

namespace PSB\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CombatHistory
 *
 * @ORM\Table(name="ws_combat_history")
 * @ORM\Entity
 */
class CombatHistory
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
     * @ORM\ManyToOne(targetEntity="Matches", inversedBy="statscombathistory")
     * @ORM\JoinColumn(name="resultID", referencedColumnName="id")
     */
    private $match;

    /**
    * @ORM\Column(name="timestamp", type="integer", length=11)
    */
    private $timestamp;

    /**
    * @ORM\Column(name="killsVS", type="smallint", nullable=false)
    */
    private $killsVS;

    /**
    * @ORM\Column(name="killsNC", type="smallint", nullable=false)
    */
    private $killsNC;

    /**
    * @ORM\Column(name="killsTR", type="smallint", nullable=false)
    */
    private $killsTR;

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
     * Set timestamp
     *
     * @param integer $timestamp
     * @return CombatHistory
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return integer 
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set killsVS
     *
     * @param integer $killsVS
     * @return CombatHistory
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
     * @return CombatHistory
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
     * @return CombatHistory
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
     * Set match
     *
     * @param \PSB\AdminBundle\Entity\Matches $match
     * @return CombatHistory
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
