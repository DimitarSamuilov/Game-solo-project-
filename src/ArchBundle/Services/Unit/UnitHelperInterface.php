<?php

namespace ArchBundle\Services\Unit;

interface UnitHelperInterface
{

    public function setProduction($doctrine,$unitName,$unitCount,$baseId);
}