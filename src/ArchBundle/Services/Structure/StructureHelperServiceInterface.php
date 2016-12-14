<?php
/**
 * Created by PhpStorm.
 * User: Arch
 * Date: 10.12.2016 г.
 * Time: 10:48 ч.
 */

namespace ArchBundle\Services\Structure;


interface StructureHelperServiceInterface
{
    public function haveResources($doctrine, $id);

    public function allocateUpgradeResources($baseId, $structure, $doctrine);

    public function prepareStructureViewModel($structures, $user);

    public function beginUpgrade($upgradeStructure, $doctrine);

    public function structureUpgradeStatus($structure, $doctrine);

}