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
    public function setUpgrade($doctrine, $id);

    public function allocateUpgradeResources($doctrine,$baseId,$structureId);
}