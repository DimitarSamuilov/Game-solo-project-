<?php
/**
 * Created by PhpStorm.
 * User: Arch
 * Date: 7.12.2016 г.
 * Time: 15:21 ч.
 */

namespace ArchBundle\Services\Base;

use ArchBundle\Entity\Base;
use ArchBundle\Entity\BaseResource;
use ArchBundle\Entity\ResourceName;
use ArchBundle\Entity\Structure;
use ArchBundle\Entity\StructureName;
use ArchBundle\Entity\Unit;
use ArchBundle\Entity\UnitName;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ObjectRepository;


class BaseGenerationService implements BaseGenerationInterface
{
    const START_BASES = 3;
    const START_LEVEL = 0;

    const START_RESOURCE = 1000;


    const MIN_X = 0;
    const MAX_X = 100;

    const MIN_Y = 0;
    const MAX_Y = 100;

    /**
     * @param $doctrine Registry
     * @param $user
     *
     */
    public function generateBases($doctrine, $user)
    {

        for ($i = 0; $i < self::START_BASES; $i++) {
            $base = $this->generateCoordinates($doctrine->getRepository(Base::class));
            $base->setUser($user);
            $em = $doctrine->getEntityManager();
            $em->persist($base);
            $em->flush();
            $this->initializeBuildings($doctrine, $base);
            $this->initializeResources($doctrine, $base);
            $this->initializeUnits($doctrine,$base);
        }
    }

    /**
     * @return Base
     * @param $repo ObjectRepository
     */
    private function generateCoordinates($repo)
    {
        $base = new Base();
        $x = 0;
        $y = 0;
        do {
            $x = rand(self::MIN_X, self::MAX_X);
            $y = rand(self::MIN_Y, self::MAX_Y);
            $temp = $repo->findOneBy(['x' => $x, 'y' => $y]);

        } while ($temp !== null);

        $base->setX($x);
        $base->setY($y);
        return $base;
    }

    /**
     * @param $doctrine Registry
     * @param $base Base
     *
     *
     */
    private function initializeBuildings($doctrine, $base)
    {
        $structureNames = $doctrine->getRepository(StructureName::class)->findAll();
        foreach ($structureNames as $structureName) {
            $structure = new Structure();
            $structure->setBase($base);
            $structure->setStructureName($structureName);
            $structure->setLevel(self::START_LEVEL);
            $em = $doctrine->getEntityManager();
            $em->persist($structure);
            $em->flush();
        }
    }

    /**
     * @param $doctrine Registry
     * @param $base
     */
    private function initializeResources($doctrine, $base)
    {
        $em = $doctrine->getEntityManager();
        $resourceRepo = $doctrine->getRepository(ResourceName::class)->findAll();
        foreach ($resourceRepo as $resourceName) {
            $resource = new BaseResource();
            $resource->setResourceName($resourceName);
            $resource->setBase($base);
            $resource->setAmount(self::START_RESOURCE);
            $em->persist($resource);
            $em->flush();
        }
    }

    /**
     * @param $doctrine Registry
     * @param $base
     */
    private function initializeUnits($doctrine, $base)
    {
        $em = $doctrine->getEntityManager();
        $unitNamesRepo = $doctrine->getRepository(UnitName::class)->findAll();
        foreach ($unitNamesRepo as $unitName){
            $unit=new Unit();
            $unit->setBase($base);
            $unit->setCount(0);
            $unit->setUnitName($unitName);
            $em->persist($unit);
            $em->flush();
        }
    }
}