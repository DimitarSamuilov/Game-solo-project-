<?php

namespace ArchBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ResourceName
 *
 * @ORM\Table(name="resource_names")
 * @ORM\Entity(repositoryClass="ArchBundle\Repository\ResourceNameRepository")
 */
class ResourceName
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="ArchBundle\Entity\BaseResource",mappedBy="resourceName")
     *
     */
    private $baseResources;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="ArchBundle\Entity\StructureCost",mappedBy="resource")
     */
    private $structureCost;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ArchBundle\Entity\UnitCost",mappedBy="resource")
     *
     */
    private $unitCost;

    public function __construct()
    {
        $this->structureCost=new ArrayCollection();
        $this->unitCost=new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getStructureCost()
    {
        return $this->structureCost;
    }

    /**
     * @param ArrayCollection $structureCost
     */
    public function setStructureCost($structureCost)
    {
        $this->structureCost = $structureCost;
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
     * @return ResourceName
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
    public function getBaseResources()
    {
        return $this->baseResources;
    }

    /**
     * @param ArrayCollection $baseResources
     */
    public function setBaseResources($baseResources)
    {
        $this->baseResources = $baseResources;
    }

    /**
     * @return ArrayCollection
     */
    public function getUnitCost()
    {
        return $this->unitCost;
    }

    /**
     * @param ArrayCollection $unitCost
     */
    public function setUnitCost($unitCost)
    {
        $this->unitCost = $unitCost;
    }


}

