<?php

namespace ArchBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Battle
 *
 * @ORM\Table(name="battles")
 * @ORM\Entity(repositoryClass="ArchBundle\Repository\BattleRepository")
 */
class Battle
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
     * @var \DateTime
     *
     * @ORM\Column(name="startsOn", type="datetime")
     */
    private $startsOn;


    /**
     * @var Base
     * @ORM\ManyToOne(targetEntity="ArchBundle\Entity\Base",inversedBy="battle")
     * @ORM\JoinTable(name="attack_base_id")
     */
    private $attackerBase;

    /**
     * @var Base
     * @ORM\ManyToOne(targetEntity="ArchBundle\Entity\Base",inversedBy="battleDefense")
     * @ORM\JoinTable(name="defend_base_id")
     */
    private $defenderBase;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="ArchBundle\Entity\BattleUnit",mappedBy="battle")
     */
    private $battleUnits;

    public function __construct()
    {
        $this->battleUnits = new ArrayCollection();
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
     * @return Base
     */
    public function getAttackerBase()
    {
        return $this->attackerBase;
    }

    /**
     * @param Base $attackerBase
     */
    public function setAttackerBase($attackerBase)
    {
        $this->attackerBase = $attackerBase;
    }

    /**
     * @return Base
     */
    public function getDefenderBase()
    {
        return $this->defenderBase;
    }

    /**
     * @param Base $defenderBase
     */
    public function setDefenderBase($defenderBase)
    {
        $this->defenderBase = $defenderBase;
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
     * Set startsOn
     *
     * @param \DateTime $startsOn
     *
     * @return Battle
     */
    public function setStartsOn($startsOn)
    {
        $this->startsOn = $startsOn;

        return $this;
    }

    /**
     * Get startsOn
     *
     * @return \DateTime
     */
    public function getStartsOn()
    {
        return $this->startsOn;
    }

    public function addBattleUnit($unit)
    {
        $this->battleUnits[]=$unit;
        return $this;
    }
}

