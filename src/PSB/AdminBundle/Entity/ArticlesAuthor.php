<?php

namespace PSB\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Articles
 *
 * @ORM\Table(name="Authors")
 * @ORM\Entity
 */
class ArticlesAuthor
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=500, nullable=false)
     */
    private $website;

    /**
     * @var string
     *
     * @ORM\Column(name="psbstaff", type="boolean", nullable=false)
     */
    private $psbstaff;

    /**
     * @ORM\OneToMany(targetEntity="Articles", mappedBy="author")
    */
    private $articles;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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
     * Set name
     *
     * @param string $name
     * @return ArticlesAuthor
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return ArticlesAuthor
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
     * Set website
     *
     * @param string $website
     * @return ArticlesAuthor
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string 
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set psbstaff
     *
     * @param boolean $psbstaff
     * @return ArticlesAuthor
     */
    public function setPsbstaff($psbstaff)
    {
        $this->psbstaff = $psbstaff;

        return $this;
    }

    /**
     * Get psbstaff
     *
     * @return boolean 
     */
    public function getPsbstaff()
    {
        return $this->psbstaff;
    }

    /**
     * Add articles
     *
     * @param \PSB\AdminBundle\Entity\Articles $articles
     * @return ArticlesAuthor
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
}
