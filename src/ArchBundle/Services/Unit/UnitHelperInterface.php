<?php

namespace ArchBundle\Services\Unit;

interface UnitHelperInterface
{

    public function beginProduction($unitName,$base, $amount, $doctrine);

    public function haveNeededResources($unitId,$base,$unitAmount,$doctrine);

    public function unitProductionProcessing($baseId,$doctrine);

}