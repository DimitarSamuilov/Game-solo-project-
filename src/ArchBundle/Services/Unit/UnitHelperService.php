<?php
/**
 * Created by PhpStorm.
 * User: Arch
 * Date: 10.12.2016 г.
 * Time: 17:00 ч.
 */

namespace ArchBundle\Services\Unit;


use ArchBundle\Entity\Base;
use ArchBundle\Entity\UnitName;
use Doctrine\Bundle\DoctrineBundle\Registry;

class UnitHelperService implements UnitHelperInterface
{
    /**
     * @param $doctrine Registry
     * @param $unitName
     * @param $baseId
     * @param @unitCount
     * @return  bool
     */
    public function setProduction($doctrine, $unitName, $unitCount, $baseId)
    {
        $em = $doctrine->getManager();
        $base = $doctrine->getRepository(Base::class)->find($baseId);
        $availableResources = $this->getAvailableResources($base->getResources());
        $neededResources = $this->getNeededUnitProductionResources($doctrine, $unitName, $unitCount);
        $haveNeeded = $this->haveNeededResources($availableResources, $neededResources);
        if ($haveNeeded) {
            $baseUnits = $doctrine->getRepository(Base::class)->find($baseId)->getUnits();
            foreach ($baseUnits as $unit) {
                $baseUnitName = $unit->getUnitName()->getName();
                if ($baseUnitName == $unitName) {

                    $unit->setCount($unit->getCount() + $unitCount);
                    $em->persist($unit);
                    $em->flush();
                }
            }
            $baseResources=$base->getResources();
            foreach ($baseResources as $resource){

                $currentResource=$resource->getAmount();
                $resource->setAmount($currentResource-$neededResources[$resource->getResourceName()->getName()]);
                $em->persist($resource);
                $em->flush();
            }
            return true;
        }
        return false;


    }

    private function getAvailableResources($baseResources)
    {
        $availableResources = [];
        foreach ($baseResources as $resource) {
            $availableResources[$resource->getResourceName()->getName()] = $resource->getAmount();
        }
        return $availableResources;
    }

    /**
     * @param $doctrine Registry
     * @param $unitName
     * @param $unitCount
     * @return array
     */
    private function getNeededUnitProductionResources($doctrine, $unitName, $unitCount)
    {
        $unitProductionNeeds = [];
        $unitNameClass = $doctrine->getRepository(UnitName::class)->findBy(['name' => $unitName]);
        foreach ($unitNameClass[0]->getUnitCost() as $unitCost) {
            $unitProductionNeeds[$unitCost->getResource()->getName()] = ($unitCost->getAmount() * $unitCount);
        }
        return $unitProductionNeeds;
    }

    private function haveNeededResources($available, $needed)
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