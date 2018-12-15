<?php

namespace DentalClinicBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Visit
 *
 * @ORM\Table(name="visits")
 * @ORM\Entity(repositoryClass="DentalClinicBundle\Repository\VisitRepository")
 */
class Visit
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="string", length=255, nullable=true)
     */
    private $note;

    /**
     * @var Patient
     *
     * @ORM\ManyToOne(targetEntity="DentalClinicBundle\Entity\Patient", inversedBy="visits")
     * @ORM\JoinColumn(name="patientId", referencedColumnName="id")
     */
    private $patient;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="DentalClinicBundle\Entity\User", inversedBy="visits")
     * @ORM\JoinColumn(name="dentistId", referencedColumnName="id")
     */
    private $dentist;

    /**
     *
     * @var Tariff[]
     * @ORM\ManyToMany(targetEntity="DentalClinicBundle\Entity\Tariff", inversedBy="visits")
     * @ORM\JoinTable(name="manipulations")
     */
    private $manipulations;

    public function __construct()
    {
        $this->date = new \DateTime('now');
        $this->manipulations = new ArrayCollection();
    }

    /**
     * @return Tariff[]|ArrayCollection
     */
    public function getManipulations()
    {
        return $this->manipulations;
    }

    /**
     * @param Tariff $manipulation
     * @return Visit
     */
    public function addManipulations(Tariff $manipulation)
    {
//        $manipulation->setVisits($this);
        $this->manipulations[] = $manipulation;
//        $manipulation->setVisits($this);
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Visit
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return Visit
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @return Patient
     */
    public function getPatient()
    {
        return $this->patient;
    }

    /**
     * @param Patient
     *
     * @return Visit
     */
    public function setPatient(Patient $patient = null)
    {
        $this->patient = $patient;
        return $this;
    }

    /**
     * @return User
     */
    public function getDentist()
    {
        return $this->dentist;
    }

    /**
     * @param User $dentist
     * @return Visit
     */
    public function setDentist(User $dentist = null)
    {
        $this->dentist = $dentist;
        return $this;
    }

}

