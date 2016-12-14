<?php

namespace ArchBundle\Services\Structure;


use ArchBundle\Entity\Base;
use ArchBundle\Entity\Structure;
use ArchBundle\Entity\StructureCost;
use ArchBundle\Entity\StructureUpgrade;
use ArchBundle\Entity\User;
use ArchBundle\Models\ViewModel\StructureViewModel;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;


/**
 * Class StructureHelperService
 * @package ArchBundle\Services\Base
 */
class StructureHelperService implements StructureHelperServiceInterface
{

    /**
     * @param $structure Structure
     * @param $doctrine Registry
     */
    public function structureUpgradeStatus($structure,$doctrine)
    {
      if($structure->getStructureUpgrade()===null){
          return ;
      }
      $this->levelUpStructure($structure,$doctrine->getManager());
    }
    /**
     * @param $upgradeStructure Structure
     * @param $doctrine Registry
     * @return bool
     */
    public function beginUpgrade($upgradeStructure,$doctrine)
    {
        if($upgradeStructure->getStructureUpgrade()!==null){
            return false;
        }
        $upgradeEntry=new StructureUpgrade();
        $upgradeEntry->setFinishesOn(new \DateTime());
        $upgradeEntry->setStructure($upgradeStructure);
        $em=$doctrine->getManager();
        $em->persist($upgradeEntry);
        $em->flush();
        return true;
    }
    /**
     * @param $structures Structure
     * @param $user User
     * @return array
     */
    public function prepareStructureViewModel($structures,$user)
    {
        $resultViewArray = [];
        foreach ($structures as $structure) {
            /**
             * @var $structure Structure
             * @var $structureCost StructureCost
             */
            $tempViewObject = new StructureViewModel();
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
     * @param $structure Structure
     * @var $resource StructureCost
     */
    public function allocateUpgradeResources( $baseId, $structure,$doctrine)
    {
        $upgradeCost = $structure->getStructureName()->getStructureCost();
        $currentLevel = $structure->getLevel();
        $neededResourcesArray = $this->findNeededResources($upgradeCost, $currentLevel);
        $baseResources = $doctrine->getRepository(Base::class)->find($baseId)->getResources();
        $em = $doctrine->getManager();
        foreach ($baseResources as $resource) {
            $tempResource = $resource->getAmount();
            $tempResource -= $neededResourcesArray[$resource->getResourceName()->getName()];
            $resource->setAmount($tempResource);
            $em->persist($resource);
            $em->flush();
        }

    }

    /**
     * @param $em EntityManager
     * @param $structure Structure
     */
    private function levelUpStructure($structure,$em)
    {
        $structure->setLevel($structure->getLevel()+1);
        $em->persist($structure);
        $em->flush();
    }
}