<?php

namespace PSB\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * StatsWeaponsTotals
 *
 * @ORM\Table(name="ws_weapons", uniqueConstraints={@UniqueConstraint(name="weaponResult", columns={"playerID", "weaponID", "resultID"})})
 * @ORM\Entity
 */
class StatsWeapons
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
     * @ORM\ManyToOne(targetEntity="Matches", inversedBy="statsweapons")
     * @ORM\JoinColumn(name="resultID", referencedColumnName="id")
     */
    private $match;

    /**
    * @ORM\Column(name="playerID", type="string", length=20, nullable=false)
    */
    private $playerID;

    /**
    * @ORM\Column(name="weaponID", type="integer", nullable=false)
    */
    private $weaponID;

    /**
    * @ORM\Column(name="killCount", type="smallint", nullable=false)
    */
    private $killCount;

    /**
    * @ORM\Column(name="headshots", type="integer", nullable=false)
    */
    private $headshot;

    /**
    * @ORM\Column(name="teamkills", type="integer", nullable=false)
    */
    private $teamkills;

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
     *
     * @return StatsWeapons
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
     * Set weaponID
     *
     * @param integer $weaponID
     *
     * @return StatsWeapons
     */
    public function setWeaponID($weaponID)
    {
        $this->weaponID = $weaponID;

        return $this;
    }

    /**
     * Get weaponID
     *
     * @return integer
     */
    public function getWeaponID()
    {
        return $this->weaponID;
    }

    /**
     * Set killCount
     *
     * @param integer $killCount
     *
     * @return StatsWeapons
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
     * Set headshot
     *
     * @param integer $headshot
     *
     * @return StatsWeapons
     */
    public function setHeadshot($headshot)
    {
        $this->headshot = $headshot;

        return $this;
    }

    /**
     * Get headshot
     *
     * @return integer
     */
    public function getHeadshot()
    {
        return $this->headshot;
    }

    /**
     * Set teamkills
     *
     * @param integer $teamkills
     *
     * @return StatsWeapons
     */
    public function setTeamkills($teamkills)
    {
        $this->teamkills = $teamkills;

        return $this;
    }

    /**
     * Get teamkills
     *
     * @return integer
     */
    public function getTeamkills()
    {
        return $this->teamkills;
    }

    /**
     * Set match
     *
     * @param \PSB\AdminBundle\Entity\Matches $match
     *
     * @return StatsWeapons
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
