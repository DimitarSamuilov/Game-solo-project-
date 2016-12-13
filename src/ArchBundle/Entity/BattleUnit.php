<?php

namespace ArchBundle\Entity;

use ArchBundle\Services\Base\BaseGenerationService;
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
     * @var
     *
     * @ORM\Column(name="arrives_on",type="datetime");
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
     * @return Base
     */
    public function getAttackerBase(): Base
    {
        return $this->attackerBase;
    }

    /**
     * @param Base $attackerBase
     */
    public function setAttackerBase(Base $attackerBase)
    {
        $this->attackerBase = $attackerBase;
    }

    /**
     * @return Base
     */
    public function getDefenderBase(): Base
    {
        return $this->defenderBase;
    }

    /**
     * @param Base $defenderBase
     */
    public function setDefenderBase(Base $defenderBase)
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
}

