<?php

namespace ArchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BattleUnit
 *
 * @ORM\Table(name="battle_units")
 * @ORM\Entity(repositoryClass="ArchBundle\Repository\BattleUnitRepository")
 */
class BattleUnit
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
     * @ORM\Column(name="count", type="integer")
     */
    private $count;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="arrivesOn", type="datetime")
     */
    private $arrivesOn;

    /**
     * @var Base
     * @ORM\ManyToOne(targetEntity="ArchBundle\Entity\Base",inversedBy="battleUnits")
     * @ORM\JoinTable(name="attack_base_id")
     */
    private $attackerBase;

    /**
     * @var Base
     * @ORM\ManyToOne(targetEntity="ArchBundle\Entity\Base",inversedBy="battleUnitsDefense")
     * @ORM\JoinTable(name="defend_base_id")
     */
    private $defenderBase;

    /**
     * @var UnitName
     *
     * @ORM\ManyToOne(targetEntity="ArchBundle\Entity\UnitName",inversedBy="battleUnits")
     * @ORM\JoinTable(name="unit_name_id")
     */
    private $unitName;

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
    public function setUnitName($unitName)
    {
        $this->unitName = $unitName;
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
    public function setAttackerBase( $attackerBase)
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
    public function setDefenderBase( $defenderBase)
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
     * Set count
     *
     * @param integer $count
     *
     * @return BattleUnit
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
     * Set arrivesOn
     *
     * @param \DateTime $arrivesOn
     *
     * @return BattleUnit
     */
    public function setArrivesOn($arrivesOn)
    {
        $this->arrivesOn = $arrivesOn;

        return $this;
    }

    /**
     * Get arrivesOn
     *
     * @return \DateTime
     */
    public function getArrivesOn()
    {
        return $this->arrivesOn;
    }
}

