<?php
/**
 * Created by PhpStorm.
 * User: Arch
 * Date: 10.12.2016 г.
 * Time: 17:00 ч.
 */

namespace ArchBundle\Services\Unit;


use ArchBundle\Entity\Base;
use ArchBundle\Entity\Unit;
use ArchBundle\Entity\UnitCost;
use ArchBundle\Entity\UnitName;
use ArchBundle\Entity\UnitProduction;
use ArchBundle\Models\ViewModel\UnitViewModel;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Config\Definition\Exception\Exception;

class UnitHelperService implements UnitHelperInterface
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
                $tempViewObject->setProductionTime($finishTime);
                $tempViewObject->setProductionAmount($unit->getUnitProduction()->getAmount());
            }
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
     * @param $baseId
     * @param $doctrine Registry
     */
    public function unitProductionProcessing($baseId, $doctrine)
    {
        $units = $doctrine->getRepository(Base::class)->find($baseId)->getUnits();
        /**
         * @var $unit Unit
         */
        foreach ($units as $unit) {
            if ($unit->getUnitProduction() === null) {
                continue;
            }
            $currentDate = new \DateTime();
            if ($currentDate < $unit->getUnitProduction()->getFinishesOn()) {
                continue;
            }

            $em = $doctrine->getManager();
            $currentUnits = $unit->getCount();
            $producedUnits = $unit->getUnitProduction()->getAmount();
            $unit->setCount($currentUnits + $producedUnits);
            $unitProduction = $unit->getUnitProduction();
            $em->remove($unitProduction);
            $unit->setUnitProduction(null);
            $em->persist($unit);
            $em->flush();
        }
    }

    /**
     * @param $unitNameId
     * @param $doctrine Registry
     * @param $unitAmount
     * @param $baseId
     * @return mixed
     */
    public function haveNeededResources($unitNameId, $baseId, $unitAmount, $doctrine)
    {
        $base = $doctrine->getRepository(Base::class)->find($baseId);
        $unitName = $doctrine->getRepository(UnitName::class)->find($unitNameId);
        $baseResources = $this->getAvailableResources($base->getResources());
        $neededResources = $this->getNeededUnitProductionResources($unitName, $unitAmount);
        $result=$this->compareResources($baseResources, $neededResources);
        if(!$result){
            throw new Exception('You don\'t meet the requirements to produce '.$unitAmount.' '.$unitName.'!');
        }
        return $result;
    }

    /**
     * @param $unitName
     * @param $baseId
     * @param $amount
     * @param $doctrine Registry
     * @return bool
     */
    public function beginProduction($unitName, $baseId, $amount, $doctrine)
    {
        $base = $doctrine->getRepository(Base::class)->find($baseId);
        $unit = $doctrine->getRepository(Unit::class)->findOneBy(['base' => $base, 'unitName' => $unitName]);
        if ($unit->getUnitProduction() !== null) {
            return false;
        }
        $productionTime = $this->calculateProductionTime($unit->getUnitName()->getTime(), $amount);
        $unitProduction = new UnitProduction();
        $unitProduction->setUnit($unit);
        $unitProduction->setFinishesOn($productionTime);
        $unitProduction->setAmount($amount);
        $em = $doctrine->getManager();
        $em->persist($unitProduction);
        $em->flush();
        return true;
    }

    /**
     * @param $singleUnitTime
     * @param $amount
     * @return \DateTime
     */
    public function calculateProductionTime($singleUnitTime, $amount)
    {
        $interval = $singleUnitTime * $amount;
        $competeTime = new \DateTime();
        $competeTime = $competeTime->add(\DateInterval::createFromDateString($interval . ' seconds'));
        return $competeTime;
    }

    /**
     * @param $baseResources array
     * @return array
     */
    private function getAvailableResources($baseResources)
    {
        $availableResources = [];
        foreach ($baseResources as $resource) {
            $availableResources[$resource->getResourceName()->getName()] = $resource->getAmount();
        }
        return $availableResources;
    }

    /**
     * @param $unitName UnitName
     * @param $amount integer
     * @return  mixed
     */
    private function getNeededUnitProductionResources($unitName, $amount)
    {
        $neededResources = [];
        $unitCosts = $unitName->getUnitCost();
        /**
         * @var $cost UnitCost
         */
        foreach ($unitCosts as $cost) {
            $neededResources[$cost->getResource()->getName()] = $cost->getAmount() * $amount;
        }
        return $neededResources;
    }

    private function compareResources($available, $needed)
    {
        $count = 0;
        foreach ($needed as $resource => $amount) {
            if ($available[$resource] >= $needed[$resource]) {
                $count++;
            }
        }
        return $count >= sizeof($needed);
    }
}