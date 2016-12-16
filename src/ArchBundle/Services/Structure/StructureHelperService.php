<?php

namespace ArchBundle\Services\Structure;


use ArchBundle\Entity\Base;
use ArchBundle\Entity\BaseResource;
use ArchBundle\Entity\Structure;
use ArchBundle\Entity\StructureCost;
use ArchBundle\Entity\StructureUpgrade;
use ArchBundle\Entity\User;
use ArchBundle\Models\ViewModel\StructureViewModel;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Config\Definition\Exception\Exception;


/**
 * Class StructureHelperService
 * @package ArchBundle\Services\Base
 */
class StructureHelperService implements StructureHelperServiceInterface
{
    /**
     * @param $baseId
     * @param $doctrine Registry
     */
    public function structureUpgradeProcessing($baseId, $doctrine)
    {
        $structures = $doctrine->getRepository(Base::class)->find($baseId)->getStructures();
        /**
         * @var  $structure Structure
         */
        foreach ($structures as $structure) {
            if ($structure->getStructureUpgrade() === null) {
                continue;
            }
            $currentDate = new \DateTime();
            if ($currentDate < $structure->getStructureUpgrade()->getFinishesOn()) {
                continue;
            }
            $structureUpgrade = $structure->getStructureUpgrade();
            $em = $doctrine->getManager();
            $structure->setLevel($structure->getLevel() + 1);
            $em->remove($structureUpgrade);
            $structure->setStructureUpgrade(null);
            $em->persist($structure);
            $em->flush();
        }
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

    /**
     * @param $upgradeStructure Structure
     * @param $doctrine Registry
     * @return bool
     */
    public function beginUpgrade($upgradeStructure, $doctrine)
    {
        if ($upgradeStructure->getStructureUpgrade() !== null) {
            return false;
        }
        $level = $upgradeStructure->getLevel();
        $time = $upgradeStructure->getStructureName()->getTime();
        $upgradeEntry = new StructureUpgrade();
        $upgradeEntry->setFinishesOn($this->calculateUpgradeTime($time, $level));
        $upgradeEntry->setStructure($upgradeStructure);
        $upgradeStructure->setStructureUpgrade($upgradeEntry);
        $em = $doctrine->getManager();
        $em->persist($upgradeEntry);
        $em->flush();
        return true;
    }

    /**
     * @param $doctrine Registry
     * @param $id
     * @return  bool
     */
    public function haveResources($doctrine, $id)
    {
        $structure = $doctrine->getRepository(Structure::class)->find($id);
        $currentStructureLevel = $structure->getLevel();
        $baseResources = $structure->getBase()->getResources();
        $availableResources = $this->getAvailableResources($baseResources);
        $upgradeCost = $structure->getStructureName()->getStructureCost();
        $neededResources = $this->findNeededResources($upgradeCost, $currentStructureLevel);
        $result = $this->resourceCheck($availableResources, $neededResources);
        if(!$result){
            throw new Exception(
                "You don't have enough resources to upgrade ".$structure->getStructureName()->getName()." !"
            );
        }
        return $result;

    }

    private function resourceCheck($availableResources, $neededResources)
    {
        $count = 0;
        foreach ($neededResources as $resource => $amount) {
            if ($availableResources[$resource] >= ($neededResources[$resource])) {
                $count++;
            }
        }
        return $count >= sizeof($neededResources);
    }

    /**
     * @param $upgradeCost
     * @param $level
     * @return array
     */
    private function findNeededResources($upgradeCost, $level)
    {
        $neededResources = [];
        /**
         * @var $upgrade StructureCost
         */
        foreach ($upgradeCost as $upgrade) {
            $neededResources[$upgrade->getResource()->getName()] = ($upgrade->getAmount() * ($level + 1));
        }
        return $neededResources;
    }

    /**
     * @param $baseResources
     * @return array
     */
    private function getAvailableResources($baseResources)
    {
        $availableResources = [];
        /**
         * @var  $resource BaseResource
         */
        foreach ($baseResources as $resource) {
            $availableResources[$resource->getResourceName()->getName()] = $resource->getAmount();
        }

        return $availableResources;
    }

    /**
     * @param $doctrine Registry
     * @param $baseId
     * @param $structure Structure
     * @var $resource StructureCost
     */
    public function allocateUpgradeResources($baseId, $structure, $doctrine)
    {
        $upgradeCost = $structure->getStructureName()->getStructureCost();
        $currentLevel = $structure->getLevel();
        $neededResourcesArray = $this->findNeededResources($upgradeCost, $currentLevel);
        $baseResources = $doctrine->getRepository(Base::class)->find($baseId)->getResources();
        $em = $doctrine->getManager();
        /**
         * @var  $resource BaseResource
         */
        foreach ($baseResources as $resource) {
            $tempResource = $resource->getAmount();
            $tempResource -= $neededResourcesArray[$resource->getResourceName()->getName()];
            $resource->setAmount($tempResource);
            $em->persist($resource);
            $em->flush();
        }

    }


}