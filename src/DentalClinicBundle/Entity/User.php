<?php

namespace DentalClinicBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="DentalClinicBundle\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="fullName", type="string", length=255)
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="DentalClinicBundle\Entity\Visit", mappedBy="dentist")
     */
    private $visits;

    /**
     *
     * @ORM\ManyToOne(targetEntity="DentalClinicBundle\Entity\Role", inversedBy="users")
     * @ORM\JoinColumn(name="roleId", referencedColumnName="id")
     */
    private $role;

    /**
     *
     * @ORM\ManyToOne(targetEntity="DentalClinicBundle\Entity\ClinicBranch", inversedBy="users")
     * @ORM\JoinColumn(name="branchId", referencedColumnName="id")
     */
    private $clinic;

    /**
     * @return mixed
     */
    public function getClinic()
    {
        return $this->clinic;
    }

    /**
     * @param mixed $clinic
     */
    public function setClinic($clinic)
    {
        $this->clinic = $clinic;
    }

    /**
     * @return Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param Role $role
     * @return User
     */
    public function setRole(Role $role = null)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userName
     *
     * @param string $username
     *
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
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

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

    /**
     * Set fullName
     *
     * @param string $fullName
     *
     * @return User
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return User
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @return ArrayCollection
     */
    public function getVisits()
    {
        return $this->visits;
    }

    /**
     * @param Visit $visit
     *
     * @return User
     */
    public function addVisit(Visit $visit)
    {
        $this->visits[] = $visit;
        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return array('ROLE_USER');
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return [];
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return bool
     */
    public function isAdmin() {
        if ($this->getRole()->getName() === "Admin") {
            return true;
        }
    }

    /**
     * @return bool
     */
    public function isHr() {
        if ($this->getRole()->getName() === "Hr") {
            return true;
        }
    }

    /**
     * @return bool
     */
    public function isFinancier() {
        if ($this->getRole()->getName() === "Financier") {
            return true;
        }
    }

    /**
     * @return bool
     */
    public function isDentist() {
        if ($this->getRole()->getName() === "Dentist") {
            return true;
        }
    }
}

