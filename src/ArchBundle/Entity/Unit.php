<?php

namespace ArchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Unit
 *
 * @ORM\Table(name="units")
 * @ORM\Entity(repositoryClass="ArchBundle\Repository\UnitRepository")
 */
class Unit
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
     * @ORM\Column(name="count", type="integer")
     */
    private $count;

    /**
     * @var UnitName
     * @ORM\ManyToOne(targetEntity="ArchBundle\Entity\UnitName",inversedBy="units")
     * @ORM\JoinTable(name="unit_name_id")
     */
    private $unitName;

    /**
     * @var Base
     * @ORM\ManyToOne(targetEntity="ArchBundle\Entity\Base",inversedBy="units")
     * @ORM\JoinTable(name="base_id")
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
     * Set count
     *
     * @param integer $count
     *
     * @return Unit
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @return UnitName
     */
    public function getUnitName()
    {
        return $this->unitName;
    }

    /**
     * @param UnitName $unitName
     */
    public function setUnitName( $unitName)
    {
        $this->unitName = $unitName;
    }

    /**
     * @return Base
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * @param Base $base
     */
    public function setBase($base)
    {
        $this->base = $base;
    }

    function __toString()
    {
        return $this->getUnitName()->getName();
    }
}

