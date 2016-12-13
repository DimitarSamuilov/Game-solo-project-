<?php

namespace ArchBundle\Services\Structure;


use ArchBundle\Entity\Base;
use ArchBundle\Entity\Structure;
use Doctrine\Bundle\DoctrineBundle\Registry;


/**
 * Class StructureHelperService
 * @package ArchBundle\Services\Base
 */
class StructureHelperService implements StructureHelperServiceInterface
{
    /**
     * @param $doctrine Registry
     * @param $id
     * @return  bool
     */
    public function setUpgrade($doctrine, $id)
    {
        $result = false;
        $structure = $doctrine->getRepository(Structure::class)->find($id);
        $currentStructureLevel = $structure->getLevel();
        $baseResources = $structure->getBase()->getResources();
        $availableResources = $this->getAvailableResources($baseResources);
        $upgradeCost = $structure->getStructureName()->getStructureCost();
        $neededResources = $this->findNeededResources($upgradeCost, $currentStructureLevel);
        $result = $this->haveResources($availableResources, $neededResources);
        return $result;

    }

    private function haveResources($availableResources, $neededResources)
    {
        $count = 0;
        foreach ($neededResources as $resource => $amount) {
            if ($availableResources[$resource] >= ($neededResources[$resource])) {
                $count++;
            }
        }
        return $count >= sizeof($neededResources);
    }

    private function findNeededResources($upgradeCost, $level)
    {
        $neededResources = [];
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
        foreach ($baseResources as $resource) {
            $availableResources[$resource->getResourceName()->getName()] = $resource->getAmount();
        }

        return $availableResources;
    }

    /**
     * @param $doctrine Registry
     * @param $baseId
     * @param $structureId
     */
    public function allocateUpgradeResources($doctrine, $baseId, $structureId)
    {
        $structure = $doctrine->getRepository(Structure::class)->find($structureId);
        $upgradeCost = $structure->getStructureName()->getStructureCost();
        $currentLevel = $structure->getLevel();
        $neededResourcesArray = $this->findNeededResources($upgradeCost, $currentLevel);
        $base = $doctrine->getRepository(Base::class)->find($baseId);
        $baseResources = $base->getResources();
        $em = $doctrine->getManager();
        foreach ($baseResources as $resource) {
            $tempResource = $resource->getAmount();
            var_dump($tempResource);
            $tempResource = $tempResource - $neededResourcesArray[$resource->getResourceName()->getName()];
            $resource->setAmount($tempResource);
            var_dump($resource->getAmount());
            $em->persist($resource);
            $em->flush();
        }
        $structure->setLevel($currentLevel + 1);
        $em->persist($structure);
        $em->flush();


    }
}