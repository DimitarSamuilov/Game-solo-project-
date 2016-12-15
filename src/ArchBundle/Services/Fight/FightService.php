<?php
/**
 * Created by PhpStorm.
 * User: Arch
 * Date: 12.12.2016 г.
 * Time: 19:20 ч.
 */

namespace ArchBundle\Services\Fight;

use ArchBundle\Entity\Base;
use ArchBundle\Entity\BattleUnit;
use ArchBundle\Entity\Unit;
use ArchBundle\Entity\UnitName;
use ArchBundle\Entity\User;
use ArchBundle\Models\ViewModel\PlayerBaseModel;
use Doctrine\Bundle\DoctrineBundle\Registry;

class FightService implements FightServiceInterface
{
    const MIN_ARMY_RETURN = 1;
    const MAX_ARMY_RETURN = 4;

    /**
     * @param $attackerBase
     * @param $defenderBase
     * @param $armyArr
     * @param $doctrine Registry
     * @param $before array
     */
    public function sendArmy($attackerBase, $defenderBase, $armyArr, $before, $doctrine)
    {
        $em = $doctrine->getEntityManager();
        $unitNames = $doctrine->getRepository(UnitName::class)->findAll();
        foreach ($unitNames as $unit) {
            $battleUnits = new BattleUnit();
            $battleUnits->setAttackerBase($attackerBase);
            $battleUnits->setDefenderBase($defenderBase);
            $battleUnits->setUnitName($unit);
            $battleUnits->setCount($armyArr[$unit->getName()]);
            $battleUnits->setArrivesOn(new \DateTime());
            $em->persist($battleUnits);
            $em->flush();
        }
        $this->subtractAttackerUnits($attackerBase, $armyArr, $before, $doctrine);
    }

    /**
     * @param $attackerBase Base
     * @param $armyArr
     * @param $doctrine Registry
     * @param $before array
     */
    private function subtractAttackerUnits($attackerBase, $armyArr, $before, $doctrine)
    {
        /**
         * @var $unit Unit
         */
        foreach ($attackerBase->getUnits() as $unit) {
            $unit->setCount($before[$unit->getUnitName()->getName()] - $armyArr[$unit->getUnitName()->getName()]);
        }
        $em = $doctrine->getManager();
        $em->persist($attackerBase);
        $em->flush();
    }

    /**
     * @param $attackerBase Base
     * @param $doctrine Registry
     * @return mixed
     */
    public function getPlayerAssaultUnits($attackerBase,$doctrine)
    {
        $battlesToProcess=[];
        $currentDate=new \DateTime();
        $battleUnits=$doctrine->getRepository(BattleUnit::class)->findBy(['attackerBase'=>$attackerBase,]);
        foreach ($battleUnits as $battleUnit){
            if($currentDate>$battleUnit->getArrivesOn()){
                $battlesToProcess[]=$battleUnit;
            }
        }
        return $battlesToProcess;

    }
    /**
     * @param $attackerBase Base
     * @param $defenderBase Base
     * @param $doctrine Registry
     */
    public function organiseAssault($attackerBase, $defenderBase, $doctrine)
    {

        $attackerUnits = $doctrine->getRepository(BattleUnit::class)->findBy(['attackerBase' => $attackerBase, 'defenderBase' => $defenderBase]);
        $battleResult = $this->isBaseDestroyed($attackerUnits, $defenderBase->getUnits());
        if ($battleResult[0] === true) {
            if ($battleResult[1] != true) {
                $attackerUnits = $this->battleCasualties($attackerUnits);
            }
            echo 'base Destroyed';
            $this->attackerWins($attackerBase->getUser(), $this->mapAttackerUnits($attackerUnits), $defenderBase, $doctrine);
            $this->nullifyBattleUnits($attackerUnits, $doctrine);
        } else {
            echo 'base Survived';
            if ($battleResult[1] != true) {
                $defenderBase->setUnits($this->battleCasualties($defenderBase->getUnits()));
            }
            $this->defenderHolds($defenderBase, $doctrine);
        }
    }

    /**
     * @param $attacker User
     * @param $attackerUnits  array
     * @param $defenderBase Base
     * @param $doctrine  Registry
     */
    private function attackerWins($attacker, $attackerUnits, $defenderBase, $doctrine)
    {
        $defenderBase->setUser($attacker);
        foreach ($defenderBase->getUnits() as $unit) {
            $unitName = $unit->getUnitName()->getName();
            if (!array_key_exists($unitName, $attackerUnits)) {
                $unit->setCount(0);
            } else {
                $unit->setCount($attackerUnits[$unitName]);
            }
        }
        $em = $doctrine->getManager();
        $em->persist($defenderBase);
        $em->flush();
    }

    /**
     * @param $battleUnits array
     * @param $doctrine Registry
     * @var $unit BattleUnit
     */
    private function nullifyBattleUnits($battleUnits, $doctrine)
    {
        $em = $doctrine->getManager();
        foreach ($battleUnits as $unit) {
            $em->remove($unit);
            $em->flush();
        }
    }

    /**
     * @param $defenderBase Base
     * @param $doctrine Registry
     */
    private function defenderHolds($defenderBase, $doctrine)
    {
        $em = $doctrine->getManager();
        $em->persist($defenderBase);
        $em->flush();
    }


    public function battleCasualties($forces)
    {
        /**
         * @var $units Unit
         */
        foreach ($forces as $units) {
            $survivalRate = rand(self::MIN_ARMY_RETURN, self::MAX_ARMY_RETURN) / 10;
            $units->setCount(round($units->getCount() * $survivalRate));
        }
        return $forces;
    }


    /**
     * @param $attackingForce
     * @param $defendingForce
     * @return mixed
     */
    public function isBaseDestroyed($attackingForce, $defendingForce)
    {
        $emptyBase = false;
        $attackerPoints = 0;
        $defenderPoints = 0;
        /**
         * @var $attackerUnit Unit
         */
        foreach ($attackingForce as $attackerUnit) {
            $attack = $attackerUnit->getUnitName()->getAttack();
            $attackerPoints += ($attack * $attackerUnit->getCount());
        }
        /**
         * @var $defenderUnit Unit
         */
        foreach ($defendingForce as $defenderUnit) {
            $defense = $defenderUnit->getUnitName()->getDefense();
            $defenderPoints += ($defense * $defenderUnit->getCount());
        }

        if ($defenderPoints == 0) {
            $emptyBase = true;
        }
        return [$attackerPoints > $defenderPoints, $emptyBase];
    }

    public function getBasesView($bases, $currentBase)
    {
        $resultArray = [];
        /**
         * @var $base Base
         * @var $currentBase Base
         */
        foreach ($bases as $base) {
            if ($base->getUser()->getId() === $currentBase->getUser()->getId()) {
                continue;
            }
            $temp = new PlayerBaseModel();
            $temp->setId($base->getId());
            $temp->setUserId($base->getUser()->getId());
            $temp->setUserUsername($base->getUser()->getUsername());
            $temp->setX($base->getX());
            $temp->setY($base->getY());
            $temp->setTime(
                $this->calculateTime(
                    [$base->getX(), $currentBase->getX()],
                    [$base->getY(), $currentBase->getY()]
                )->format('Y-m-d H:i:s'));
            $resultArray[] = $temp;
        }
        return $resultArray;
    }

    public function calculateTime($xArr, $yArr)
    {
        $x = $xArr[0] - $xArr[1];
        $y = $yArr[0] - $yArr[1];
        $distance = ceil(sqrt(pow($x, 2) + pow($y, 2)));
        $distance *= 10;
        $attackTime = new \DateTime(null, new \DateTimeZone('Europe/Sofia'));
        $attackTime = $attackTime->add(\DateInterval::createFromDateString($distance . ' seconds'));
        return $attackTime;
    }

    /**
     * @param $army array
     * @return array
     */
    public function mapAttackerUnits($army)
    {
        $resultArray = [];
        /**
         * @var $units Unit
         */
        foreach ($army as $units) {
            if (!array_key_exists($units->getUnitName()->getName(), $resultArray)) {
                $resultArray[$units->getUnitName()->getName()] = 0;
            }
            $resultArray[$units->getUnitName()->getName()] += $units->getCount();
        }
        return $resultArray;
    }

    public function areMoreSoldiersAdded($currentUnits, $newlyEnteredUnits)
    {
        /**
         * @var $unit Unit
         */
        foreach ($newlyEnteredUnits as $unit) {
            if ($unit->getCount() > $currentUnits[$unit->getUnitName()->getName()] or ($unit->getCount() < 0)) {
                return true;
            }
        }
        return false;
    }


}