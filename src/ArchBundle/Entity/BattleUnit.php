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
     * @var UnitName
     * @ORM\ManyToOne(targetEntity="ArchBundle\Entity\UnitName",inversedBy="battleUnits")
     * @ORM\JoinTable(name="unit_name_id")
     */
    private $unitName;

    /**
     * @var Battle
     * @ORM\ManyToOne(targetEntity="ArchBundle\Entity\Battle",inversedBy="battleUnits")
     * @ORM\JoinTable(name="battle_id")
     */
    private $battle;

    /**
     * @return Battle
     */
    public function getBattle(): Battle
    {
        return $this->battle;
    }

    /**
     * @param Battle $battle
     */
    public function setBattle(Battle $battle)
    {
        $this->battle = $battle;
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
}

