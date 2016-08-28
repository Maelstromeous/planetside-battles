<?php

namespace PSB\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class User implements UserInterface
{
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="username", type="string", length=30)
	 */
	private $username;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="password", type="string", length=64)
	 */
	private $password; 

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="rep", type="boolean")
	 */
	private $rep;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="admin", type="boolean")
	 */
	private $admin;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="super", type="boolean")
	 */
	private $super;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="apikey", type="string", length=64)
	 */
	private $apikey;

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
	 * Set username
	 *
	 * @param string $username
	 * @return User
	 */
	public function setUsername($username)
	{
		$this->username = $username;

		return $this;
	}

	/**
	 * Get username
	 *
	 * @return string 
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * Set password
	 *
	 * @param string $password
	 * @return User
	 */
	public function setPassword($password)
	{
		$this->password = password_hash($password, PASSWORD_BCRYPT);

		return $this;
	}

	/**
	 * Get password
	 *
	 * @return string 
	 */
	public function getPassword()
	{
		return $this->password;
	}

	public function getSalt()
	{
		return null;
	}

	public function getRoles()
	{
		if ($this->getSuper())
		{
			return array("ROLE_SUPER_ADMIN");
		}
		else if ($this->getAdmin())
		{
			return array("ROLE_ADMIN");
		}
		else if ($this->getRep())
		{
			return array("ROLE_REP");
		}

		return array("ROLE_USER"); // If nothing else
	}

	public function eraseCredentials()
	{

	}

	public function setAdmin($admin)
	{
		$this->admin = $admin;

		return $this;
	}

	/**
	 * Get admin
	 *
	 * @return boolean 
	 */
	public function getAdmin()
	{
		return $this->admin;
	}

    /**
     * Set rep
     *
     * @param boolean $rep
     * @return User
     */
    public function setRep($rep)
    {
        $this->rep = $rep;

        return $this;
    }

    /**
     * Get rep
     *
     * @return boolean 
     */
    public function getRep()
    {
        return $this->rep;
    }

    /**
     * Set super
     *
     * @param boolean $super
     * @return User
     */
    public function setSuper($super)
    {
        $this->super = $super;

        return $this;
    }

    /**
     * Get super
     *
     * @return boolean 
     */
    public function getSuper()
    {
        return $this->super;
    }

    /**
     * Set apikey
     *
     * @param string $apikey
     * @return User
     */
    public function setApikey($apikey)
    {
        $this->apikey = $apikey;

        return $this;
    }

    /**
     * Get apikey
     *
     * @return string 
     */
    public function getApikey()
    {
        return $this->apikey;
    }
}
