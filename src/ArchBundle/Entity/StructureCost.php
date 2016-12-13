<?php

namespace ArchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StructureCost
 *
 * @ORM\Table(name="structure_costs")
 * @ORM\Entity(repositoryClass="ArchBundle\Repository\StructureCostRepository")
 */
class StructureCost
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
     * @var StructureName
     * @ORM\ManyToOne(targetEntity="ArchBundle\Entity\StructureName",inversedBy="structureCost")
     * @ORM\JoinTable(name="structure_name_id")
     */
    private $structureName;

    /**
     * @var ResourceName
     * @ORM\ManyToOne(targetEntity="ArchBundle\Entity\ResourceName",inversedBy="structureCost")
     * @ORM\JoinTable(name="resource_name_id")
     */
    private $resource;

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
     * @return StructureCost
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
     * @return ResourceName
     */
    public function getResource(): ResourceName
    {
        return $this->resource;
    }

    /**
     * @param ResourceName $resource
     */
    public function setResource(ResourceName $resource)
    {
        $this->resource = $resource;
    }


}

