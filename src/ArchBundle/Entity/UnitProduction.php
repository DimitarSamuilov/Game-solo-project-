<?php

namespace ArchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UnitProduction
 *
 * @ORM\Table(name="unit_productions")
 * @ORM\Entity(repositoryClass="ArchBundle\Repository\UnitProductionRepository")
 */
class UnitProduction
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
     * @var int
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finishesOn", type="datetime")
     */
    private $finishesOn;

    /**
     * @var Unit
     * @ORM\OneToOne(targetEntity="ArchBundle\Entity\Unit",inversedBy="unitProduction")
     * @ORM\JoinTable(name="unit_id")
     */
    private $unit;

    /**
     * @return Unit
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param Unit $unit
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
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
     * Set amount
     *
     * @param integer $amount
     *
     * @return UnitProduction
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set finishesOn
     *
     * @param \DateTime $finishesOn
     *
     * @return UnitProduction
     */
    public function setFinishesOn($finishesOn)
    {
        $this->finishesOn = $finishesOn;

        return $this;
    }

    /**
     * Get finishesOn
     *
     * @return \DateTime
     */
    public function getFinishesOn()
    {
        return $this->finishesOn;
    }
}

