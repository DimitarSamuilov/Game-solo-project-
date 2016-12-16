<?php
/**
 * Created by PhpStorm.
 * User: Arch
 * Date: 15.12.2016 г.
 * Time: 20:42 ч.
 */

namespace ArchBundle\Services\View;


use ArchBundle\Entity\Base;
use ArchBundle\Entity\Battle;
use ArchBundle\Entity\Structure;
use ArchBundle\Entity\StructureCost;
use ArchBundle\Entity\Unit;
use ArchBundle\Entity\UnitCost;
use ArchBundle\Entity\User;
use ArchBundle\Models\ViewModel\PlayerBaseModel;
use ArchBundle\Models\ViewModel\StructureViewModel;
use ArchBundle\Models\ViewModel\UnitViewModel;
use Doctrine\Bundle\DoctrineBundle\Registry;


class ViewHelper implements ViewHelperInterface
{


    /**
     * @param $unitRepo
     * @return array
     */
    public function getViewArray($unitRepo)
    {
        $viewArray = [];
        foreach ($unitRepo as $unit) {
            /**
             * @var $unit Unit
             */
            $tempViewObject = new UnitViewModel();
            if ($unit->getUnitProduction() !== null) {
                $finishTime = $unit->getUnitProduction()->getFinishesOn();
                $tempViewObject->setProductionTime($this->formatCountDownTime($finishTime));
                $tempViewObject->setProductionAmount($unit->getUnitProduction()->getAmount());
            }
            $tempViewObject->setProductionTimeRequirements($unit->getUnitName()->getTime());
            $tempViewObject->setName($unit->getUnitName()->getName());
            $tempViewObject->setCount($unit->getCount());
            $unitCosts = $unit->getUnitName()->getUnitCost();
            foreach ($unitCosts as $unitCost) {
                /**
                 * @var $unitCost UnitCost
                 */
                if ($unitCost->getResource()->getName() == "Wood") {
                    $tempViewObject->setWood($unitCost->getAmount());
                } else if ($unitCost->getResource()->getName() == "Coin") {
                    $tempViewObject->setCoin($unitCost->getAmount());
                }
            }
            $viewArray[] = $tempViewObject;
        }
        return $viewArray;
    }

    /**
     * @param $structures Structure
     * @param $user User
     * @return array
     */
    public function prepareStructureViewModel($structures, $user)
    {
        $resultViewArray = [];
        foreach ($structures as $structure) {
            /**
             * @var $structure Structure
             * @var $structureCost StructureCost
             */
            $tempViewObject = new StructureViewModel();
            $upgrade = $structure->getStructureUpgrade();
            if ($upgrade !== null) {
                $tempViewObject
                    ->setUpgradeTime(
                        $this->formatCountDownTime($upgrade->getFinishesOn())
                    );
            }
            $tempViewObject->setRequiredTime(
                $this->calculateUpgradeTime(
                    $structure->getStructureName()->getTime(), $structure->getLevel()));
            $tempViewObject->setName($structure->getStructureName()->getName());
            $tempViewObject->setId($structure->getId());
            $tempViewObject->setUsername($user->getUsername());
            $tempViewObject->setLevel($structure->getLevel());
            foreach ($structure->getStructureName()->getStructureCost() as $structureCost) {
                if ($structureCost->getResource()->getName() == "Wood") {
                    $tempViewObject->setWood($structureCost->getAmount() * ($structure->getLevel() + 1));
                } else if ($structureCost->getResource()->getName() == "Coin") {
                    $tempViewObject->setCoin($structureCost->getAmount() * ($structure->getLevel() + 1));
                }
            }
            $resultViewArray[] = $tempViewObject;
        }

        return $resultViewArray;

    }

    /**
     * @param $bases Base
     * @param $currentBase
     * @param $doctrine Registry
     * @return array
     */
    public function getBasesView($bases, $currentBase, $doctrine)
    {
        $resultArray = [];
        /**
         * @var $base \ArchBundle\Entity\Base
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
                $this->formatCountDownTime($this->calculateAttackMarchTime(
                    [$base->getX(), $currentBase->getX()],
                    [$base->getY(), $currentBase->getY()]
                )));
            $temp->setBattleTime($this->isAttacked($currentBase, $base, $doctrine));
            $resultArray[] = $temp;
        }
        return $resultArray;
    }

    public function calculateAttackMarchTime($xArr, $yArr)
    {
        $x = $xArr[0] - $xArr[1];
        $y = $yArr[0] - $yArr[1];
        $distance = ceil(sqrt(pow($x, 2) + pow($y, 2)));
        $distance *= 10;
        $attackTime = new \DateTime();
        $attackTime = $attackTime->add(\DateInterval::createFromDateString($distance . ' seconds'));
        return $attackTime;
    }

    /**
     * @param $attackerBase Base
     * @param $defenderBase Base
     * @param $doctrine Registry
     * @return  bool
     */
    private function isAttacked($attackerBase, $defenderBase, $doctrine)
    {
        $haveBattle = $doctrine->getRepository(Battle::class)->findOneBy(['attackerBase' => $attackerBase, 'defenderBase' => $defenderBase]);
        if ($haveBattle === null) {
            return false;
        } else {
            return $this->formatCountDownTime($haveBattle->getStartsOn());
        }
    }


    public function formatCountDownTime($date)
    {
        $currentTime = new \DateTime();
        $currentTimeStamp = $currentTime->getTimestamp();
        $compareTimeStamp = $date->getTimestamp();
        $difference = $compareTimeStamp - $currentTimeStamp;
        $arr = [];
        $arr['days'] = floor($difference / 86400);
        $arr['hours'] = floor(($difference % 86400) / 3600);
        $arr['minutes'] = floor(($difference % 3600) / 60);
        $arr['seconds'] = floor($difference % 60);
        return 'Days:' . $arr['days'] . ' Hours:' . $arr['hours'] . ' Minutes:' . $arr['minutes'] . ' Seconds:' . $arr['seconds'];
    }


    /**
     * @param $time
     * @param $level
     * @return \DateTime
     */
    private function calculateUpgradeTime($time, $level)
    {
        $interval = ($time + $level) * 10;
        $completeTime = new \DateTime();
        $completeTime = $completeTime->add(\DateInterval::createFromDateString($interval . ' seconds'));
        return $completeTime;
    }


}