<?php
/**
 * Created by PhpStorm.
 * User: Arch
 * Date: 12.12.2016 г.
 * Time: 19:20 ч.
 */

namespace ArchBundle\Services\Fight;

use ArchBundle\Entity\Base;
use ArchBundle\Entity\Unit;
use ArchBundle\Entity\UnitName;
use ArchBundle\Entity\User;
use ArchBundle\Models\Utility\FightingUnit;
use ArchBundle\Models\ViewModel\PlayerBaseModel;
use Doctrine\Bundle\DoctrineBundle\Registry;

class FightService implements FightServiceInterface
{
    const MIN_ARMY_RETURN = 1;
    const MAX_ARMY_RETURN = 4;

    /**
     * @param $attackerBase Base
     * @param $defenderBase Base
     * @param $attackerUnits
     * @param $doctrine
     * @param $before
     */
    public function organiseAssault($attackerBase, $defenderBase, $attackerUnits,$before, $doctrine)
    {

        $battleResult = $this->isBaseDestroyed($attackerUnits, $defenderBase->getUnits());
        if ($battleResult[0] === true) {
            echo 'base Destroyed';

            $this->attackerWins($attackerBase->getUser(),$this->mapAttackerUnits($attackerUnits),$defenderBase,$doctrine);
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
        $defenderUnits = $defenderBase->getUnits();
        foreach ($defenderUnits as $unit) {
            $unitName = $unit->getUnitName()->getName();
            if (!array_key_exists($unitName, $attackerUnits)) {
                $unit->setCount(0);
            } else {
                $unit->setCount($attackerUnits[$unitName]);
            }
        }
        $defenderBase->setUnits($defenderUnits);
        $em = $doctrine->getManager();
        $em->persist($defenderBase);
        $em->flush();
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


    public function battleCasualties($attackerForces)
    {
        /**
         * @var $units FightingUnit
         */
        foreach ($attackerForces as $units) {
            $survivalRate = rand(self::MIN_ARMY_RETURN, self::MAX_ARMY_RETURN) / 10;
            $units->setCount(round($units->getCount() * $survivalRate));
        }
        return $attackerForces;
    }


    /**
     * @param $attackingForce
     * @param $defendingForce
     * @return
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
                )->diff(new \DateTime())->format("%d-%h-%i-%s"));
            $resultArray[] = $temp;
        }
        return $resultArray;
    }

    public function calculateTime($xArr, $yArr)
    {
        $x = $xArr[0] - $xArr[1];
        $y = $yArr[0] - $yArr[1];
        $distance = ceil(sqrt(pow($x, 2) + pow($y, 2)));
        $distance *= 50;
        $attackTime = new \DateTime();
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

    public function areMoreSoldiersAdded($currentUnits,$newlyEnteredUnits)
    {
        /**
         * @var $unit Unit
         */
        foreach ($newlyEnteredUnits as $unit) {
            if($unit->getCount()>$currentUnits[$unit->getUnitName()->getName()] or ($unit->getCount()<0)){
                return true;
            }
        }
        return false;
    }


}