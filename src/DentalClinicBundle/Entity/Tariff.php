<?php

namespace DentalClinicBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Tariff
 *
 * @ORM\Table(name="tariffs")
 * @ORM\Entity(repositoryClass="DentalClinicBundle\Repository\TariffRepository")
 */
class Tariff
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
     * @ORM\Column(name="treatment", type="string", length=255, unique=true)
     */
    private $treatment;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", scale=2)
     */
    private $price;

    /**
     * @ORM\ManyToMany(targetEntity="DentalClinicBundle\Entity\Visit", mappedBy="manipulations")
     */
    private $visits;

    public function __construct()
    {
        $this->visits = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getTreatment();
    }

    /**
     * @return Visit[]|ArrayCollection
     */
    public function getVisits()
    {
        return $this->visits;
    }

    /**
     * @param Visit $visit
     *
     * @return Tariff
     */
    public function setVisits(Visit $visit)
    {
        $this->visits[] = $visit;
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
     * Set treatment
     *
     * @param string $treatment
     *
     * @return Tariff
     */
    public function setTreatment($treatment)
    {
        $this->treatment = $treatment;

        return $this;
    }

    /**
     * Get treatment
     *
     * @return string
     */
    public function getTreatment()
    {
        return $this->treatment;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Tariff
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }
}

