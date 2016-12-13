<?php

namespace ArchBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Base
 *
 * @ORM\Table(name="bases")
 * @ORM\Entity(repositoryClass="ArchBundle\Repository\BaseRepository")
 */
class Base
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
     * @ORM\Column(name="x", type="integer")
     */
    private $x;

    /**
     * @var int
     *
     * @ORM\Column(name="y", type="integer")
     */
    private $y;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="ArchBundle\Entity\User",inversedBy="bases")
     * @ORM\JoinTable(name="userId")
     */
    private $user;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="ArchBundle\Entity\BaseResource",mappedBy="base")
     */
    private $resources;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="ArchBundle\Entity\Structure",mappedBy="base")
     *
     */
    private $structures;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="ArchBundle\Entity\Unit",mappedBy="base")
     */
    private $units;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="ArchBundle\Entity\BattleUnit",mappedBy="attackerBase")
     */
     private $battleUnits;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="ArchBundle\Entity\BattleUnit",mappedBy="defenderBase")
     */
     private $battleUnitsDefense;

    /**
     * Base constructor.
     */
    public function __construct()
    {
       $this->battleUnitsDefense=new ArrayCollection();
        $this->battleUnits=new ArrayCollection();
        $this->structures = new ArrayCollection();
        $this->resources = new ArrayCollection();
        $this->units = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getBattleUnitsDefense(): ArrayCollection
    {
        return $this->battleUnitsDefense;
    }

    /**
     * @param ArrayCollection $battleUnitsDefense
     */
    public function setBattleUnitsDefense(ArrayCollection $battleUnitsDefense)
    {
        $this->battleUnitsDefense = $battleUnitsDefense;
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
    public function setBattleUnits( $battleUnits)
    {
        $this->battleUnits = $battleUnits;
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
     * Set x
     *
     * @param integer $x
     *
     * @return Base
     */
    public function setX($x)
    {
        $this->x = $x;

        return $this;
    }

    /**
     * Get x
     *
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * Set y
     *
     * @param integer $y
     *
     * @return Base
     */
    public function setY($y)
    {
        $this->y = $y;

        return $this;
    }

    /**
     * Get y
     *
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return ArrayCollection
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * @param ArrayCollection $resources
     */
    public function setResources($resources)
    {
        $this->resources = $resources;
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
    public function setStructures($structures)
    {
        $this->structures = $structures;
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


}

