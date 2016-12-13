<?php

namespace ArchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BaseResource
 *
 * @ORM\Table(name="base_resources")
 * @ORM\Entity(repositoryClass="ArchBundle\Repository\BaseResourceRepository")
 */
class BaseResource
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
     * @var ResourceName
     * @ORM\ManyToOne(targetEntity="ArchBundle\Entity\ResourceName",inversedBy="baseResources")
     * @ORM\JoinTable(name="resourceNameId")
     */
    private $resourceName;

    /**
     * @var Base
     * @ORM\ManyToOne(targetEntity="ArchBundle\Entity\Base",inversedBy="resources")
     * @ORM\JoinTable(name="baseId")
     */
    private $base;

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
     * @return BaseResource
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
    public function getResourceName()
    {
        return $this->resourceName;
    }

    /**
     * @param ResourceName $resourceName
     */
    public function setResourceName($resourceName)
    {
        $this->resourceName = $resourceName;
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

