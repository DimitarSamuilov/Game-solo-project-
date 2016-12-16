<?php

namespace ArchBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * StructureName
 *
 * @ORM\Table(name="structure_names")
 * @ORM\Entity(repositoryClass="ArchBundle\Repository\StructureNameRepository")
 */
class StructureName
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="ArchBundle\Entity\Structure",mappedBy="structureName")
     */
    private $structures;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ArchBundle\Entity\StructureCost",mappedBy="structureName")
     *
     */
    private $structureCost;

    /**
     * @var
     * @ORM\Column(name="time",type="integer")
     */
    private $time;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="ArchBundle\Entity\UnitStructureDependency",mappedBy="structureRequired")
     */
    private $levelRequirements;
    /**
     * StructureName constructor.
     */
    public function __construct()
    {
        $this->levelRequirements=new ArrayCollection();
        $this->structureCost=new ArrayCollection();
        $this->structures=new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getLevelRequirements()
    {
        return $this->levelRequirements;
    }

    /**
     * @param ArrayCollection $levelRequirements
     */
    public function setLevelRequirements($levelRequirements)
    {
        $this->levelRequirements = $levelRequirements;
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
     * @return StructureName
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
    public function getStructures()
    {
        return $this->structures;
    }

    /**
     * @param ArrayCollection $structures
     */
    public function setStructures( $structures)
    {
        $this->structures = $structures;
    }


    public function getStructureCost()
    {
        return $this->structureCost;
    }


    public function setStructureCost($structureCost)
    {
        $this->structureCost = $structureCost;
    }



}

