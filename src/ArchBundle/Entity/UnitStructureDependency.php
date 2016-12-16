<?php

namespace ArchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * UnitStructureDependency
 *
 * @ORM\Table(name="unit_structure_dependencies")
 * @ORM\Entity(repositoryClass="ArchBundle\Repository\UnitStructureDependencyRepository")
 */
class UnitStructureDependency
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
     * @ORM\Column(name="level", type="integer")
     */
    private $level;

    /**
     * @var StructureName
     *
     * @ORM\ManyToOne(targetEntity="ArchBundle\Entity\StructureName",inversedBy="levelRequirements")
     * @ORM\JoinTable(name="structure_required_id")
     */
    private $structureRequired;


    /**
     * @var
     * @ORM\ManyToOne(targetEntity="ArchBundle\Entity\UnitName",inversedBy="neededLevels")
     * @ORM\JoinTable(name="unit_name_id")
     */
    private $unitRequired;

    /**
     * @return StructureName
     */
    public function getStructureRequired(): StructureName
    {
        return $this->structureRequired;
    }

    /**
     * @param StructureName $structureRequired
     */
    public function setStructureRequired(StructureName $structureRequired)
    {
        $this->structureRequired = $structureRequired;
    }

    /**
     * @return mixed
     */
    public function getUnitRequired()
    {
        return $this->unitRequired;
    }

    /**
     * @param mixed $unitRequired
     */
    public function setUnitRequired($unitRequired)
    {
        $this->unitRequired = $unitRequired;
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
     * Set level
     *
     * @param integer $level
     *
     * @return UnitStructureDependency
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }
}

