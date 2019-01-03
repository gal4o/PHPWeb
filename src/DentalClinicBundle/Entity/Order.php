<?php

namespace DentalClinicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Order
 *
 * @ORM\Table(name="orders")
 * @ORM\Entity(repositoryClass="DentalClinicBundle\Repository\OrderRepository")
 */
class Order
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
     * @var Material
     *
     * @ORM\ManyToOne(targetEntity="DentalClinicBundle\Entity\Material", inversedBy="orders")
     * @ORM\JoinColumn(name="materialId", referencedColumnName="id")
     */
    private $material;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="DentalClinicBundle\Entity\User", inversedBy="orders")
     * @ORM\JoinColumn(name="dentistId", referencedColumnName="id")
     */
    private $dentist;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;


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
     * Set material
     *
     * @param Material $material
     *
     * @return Order
     */
    public function setMaterial($material)
    {
        $this->material = $material;

        return $this;
    }

    /**
     * Get material
     *
     * @return Material
     */
    public function getMaterial()
    {
        return $this->material;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Order
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set dentist
     *
     * @param User $dentist
     *
     * @return Order
     */
    public function setDentist($dentist)
    {
        $this->dentist = $dentist;

        return $this;
    }

    /**
     * Get dentistId
     *
     * @return User
     */
    public function getDentist()
    {
        return $this->dentist;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Order
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}

