<?php

namespace ArchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Structure
 *
 * @ORM\Table(name="structures")
 * @ORM\Entity(repositoryClass="ArchBundle\Repository\StructureRepository")
 */
class Structure
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
     * @var StructureUpgrade
     * @ORM\OneToOne(targetEntity="ArchBundle\Entity\StructureUpgrade",mappedBy="structure")
     *
     */
    private $structureUpgrade;
    /**
     * @var int
     *
     * @ORM\Column(name="level", type="integer")
     */
    private $level;

    /**
     * @var StructureName
     * @ORM\ManyToOne(targetEntity="ArchBundle\Entity\StructureName",inversedBy="structures")
     * @ORM\JoinTable(name="structureNameId")
     */
    private $structureName;

    /**
     * @var Base
     * @ORM\ManyToOne(targetEntity="ArchBundle\Entity\Base",inversedBy="structures")
     * @ORM\JoinTable(name="baseId")
     */
    private $base;

    /**
     * @return StructureUpgrade
     */
    public function getStructureUpgrade()
    {
        return $this->structureUpgrade;
    }

    /**
     * @param StructureUpgrade $structureUpgrade
     */
    public function setStructureUpgrade($structureUpgrade)
    {
        $this->structureUpgrade = $structureUpgrade;
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
     * @return Structure
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

    /**
     * @return StructureName
     */
    public function getStructureName(): StructureName
    {
        return $this->structureName;
    }

    /**
     * @param StructureName $structureName
     */
    public function setStructureName(StructureName $structureName)
    {
        $this->structureName = $structureName;
    }

    /**
     * @return Base
     */
    public function getBase(): Base
    {
        return $this->base;
    }

    /**
     * @param Base $base
     */
    public function setBase(Base $base)
    {
        $this->base = $base;
    }



}

