<?php

namespace ArchBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * UnitName
 *
 * @ORM\Table(name="unit_names")
 * @ORM\Entity(repositoryClass="ArchBundle\Repository\UnitNameRepository")
 */
class UnitName
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
     * @ORM\Column(name="description",type="text")
     */
    private $description;
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;
    /**
     * @var int
     * @ORM\Column(name="attack" ,type="integer")
     */
    private $attack;

    /**
     * @var int
     * @ORM\Column(name="defense",type="integer")
     */
    private $defense;
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ArchBundle\Entity\Unit",mappedBy="unitName")
     */
    private $units;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ArchBundle\Entity\UnitCost",mappedBy="unitName")
     */
    private $unitCost;


    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ArchBundle\Entity\BattleUnit",mappedBy="unitName")
     */
    private $battleUnits;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="ArchBundle\Entity\UnitStructureDependency",mappedBy="unitRequired")
     */
    private $neededLevels;

    /**
     * @var
     * @ORM\Column(name="time",type="integer")
     */
    private $time;

    public function __construct()
    {
        $this->neededLevels=new ArrayCollection();
        $this->battleUnits = new ArrayCollection();
        $this->units = new ArrayCollection();
        $this->unitCost = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }


    /**
     * @return ArrayCollection
     */
    public function getNeededLevels()
    {
        return $this->neededLevels;
    }

    /**
     * @param ArrayCollection $neededLevels
     */
    public function setNeededLevels($neededLevels)
    {
        $this->neededLevels = $neededLevels;
    }


    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }


    /**
     * @return ArrayCollection
     */
    public function getBattleUnits()
    {
        return $this->battleUnits;
    }

    /**
     * @param ArrayCollection $battleUnits
     */
    public function setBattleUnits($battleUnits)
    {
        $this->battleUnits = $battleUnits;
    }


    /**
     * @return int
     */
    public function getAttack(): int
    {
        return $this->attack;
    }

    /**
     * @param int $attack
     */
    public function setAttack(int $attack)
    {
        $this->attack = $attack;
    }

    /**
     * @return int
     */
    public function getDefense(): int
    {
        return $this->defense;
    }

    /**
     * @param int $defense
     */
    public function setDefense(int $defense)
    {
        $this->defense = $defense;
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
     * Set name
     *
     * @param string $name
     *
     * @return UnitName
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
     * @return ArrayCollection
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * @param ArrayCollection $units
     */
    public function setUnits($units)
    {
        $this->units = $units;
    }

    /**
     * @return ArrayCollection
     */
    public function getUnitCost()
    {
        return $this->unitCost;
    }

    /**
     * @param ArrayCollection $unitCosts
     */
    public function setUnitCost($unitCosts)
    {
        $this->unitCost = $unitCosts;
    }

    function __toString()
    {
        return $this->getName();
    }
}

