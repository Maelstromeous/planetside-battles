<?php

namespace PSB\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Matchesserversmash
 *
 * @ORM\Table(name="MatchesServerSmash")
 * @ORM\Entity
 */
class MatchesServerSmash
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
     * @ORM\Column(name="server1", type="integer", nullable=true)
     */
    private $server1;

    /**
     * @var integer
     *
     * @ORM\Column(name="server1Faction", type="integer", nullable=true)
     */
    private $server1faction;

    /**
     * @var integer
     *
     * @ORM\Column(name="server2", type="integer", nullable=true)
     */
    private $server2;

    /**
     * @var integer
     *
     * @ORM\Column(name="server2Faction", type="integer", nullable=true)
     */
    private $server2faction;

    /**
     * @var integer
     *
     * @ORM\Column(name="server3", type="integer", nullable=true)
     */
    private $server3;

    /**
     * @var integer
     *
     * @ORM\Column(name="server3Faction", type="integer", nullable=true)
     */
    private $server3faction;

    /**
     * @var integer
     *
     * @ORM\Column(name="factiononly", type="boolean")
     */
    private $factiononly;

    /**
     * @var integer
     *
     * @ORM\Column(name="tournament", type="integer", nullable=true)
     */
    private $tournament;

    /**
     * @var integer
     *
     * @ORM\Column(name="winner", type="string", length=40, nullable=true)
     */
    private $winner;

    /**
     * @ORM\OneToOne(targetEntity="Matches", inversedBy="serversmashdata")
     * @ORM\JoinColumn(name="matchID", referencedColumnName="id")
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
     * Set server1
     *
     * @param integer $server1
     *
     * @return MatchesServerSmash
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
     *
     * @return MatchesServerSmash
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
     *
     * @return MatchesServerSmash
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
     *
     * @return MatchesServerSmash
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
     *
     * @return MatchesServerSmash
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
     *
     * @return MatchesServerSmash
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
     * Set factiononly
     *
     * @param boolean $factiononly
     *
     * @return MatchesServerSmash
     */
    public function setFactiononly($factiononly)
    {
        $this->factiononly = $factiononly;

        return $this;
    }

    /**
     * Get factiononly
     *
     * @return boolean
     */
    public function getFactiononly()
    {
        return $this->factiononly;
    }

    /**
     * Set tournament
     *
     * @param integer $tournament
     *
     * @return MatchesServerSmash
     */
    public function setTournament($tournament)
    {
        $this->tournament = $tournament;

        return $this;
    }

    /**
     * Get tournament
     *
     * @return integer
     */
    public function getTournament()
    {
        return $this->tournament;
    }

    /**
     * Set winner
     *
     * @param string $winner
     *
     * @return MatchesServerSmash
     */
    public function setWinner($winner)
    {
        $this->winner = $winner;

        return $this;
    }

    /**
     * Get winner
     *
     * @return string
     */
    public function getWinner()
    {
        return $this->winner;
    }

    /**
     * Set match
     *
     * @param \PSB\AdminBundle\Entity\Matches $match
     *
     * @return MatchesServerSmash
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
