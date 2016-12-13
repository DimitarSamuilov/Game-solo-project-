<?php

namespace ArchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UnitCost
 *
 * @ORM\Table(name="unit_costs")
 * @ORM\Entity(repositoryClass="ArchBundle\Repository\UnitCostRepository")
 */
class UnitCost
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
     * @var UnitName
     *
     * @ORM\ManyToOne(targetEntity="ArchBundle\Entity\UnitName",inversedBy="unitCost")
     * @ORM\JoinTable(name="unit_name_id")
     *
     */
    private $unitName;

    /**
     * @var ResourceName
     * @ORM\ManyToOne(targetEntity="ArchBundle\Entity\ResourceName",inversedBy="unitCost")
     * @ORM\JoinTable(name="resource_id")
     */
    private $resource;

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
     * @return UnitCost
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
     * @return UnitName
     */
    public function getUnitName(): UnitName
    {
        return $this->unitName;
    }

    /**
     * @param UnitName $unitName
     */
    public function setUnitName(UnitName $unitName)
    {
        $this->unitName = $unitName;
    }


}

