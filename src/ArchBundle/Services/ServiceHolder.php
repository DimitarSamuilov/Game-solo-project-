<?php
/**
 * Created by PhpStorm.
 * User: Arch
 * Date: 7.12.2016 г.
 * Time: 16:13 ч.
 */

namespace ArchBundle\Services;

use ArchBundle\Services\Base\BaseGenerationService;
use ArchBundle\Services\Base\BaseGenerationInterface;
use ArchBundle\Services\Fight\FightServiceInterface;
use ArchBundle\Services\Structure\StructureHelperServiceInterface;
use ArchBundle\Services\Unit\UnitHelperInterface;

class ServiceHolder
{

    private $structureHelper;
    private $baseGeneration;
    private $unitHelper;
    private $fightService;
    public function __construct
    (
        BaseGenerationInterface $baseGeneration,
        StructureHelperServiceInterface $structureHelperService,
        UnitHelperInterface $unitHelper,
        FightServiceInterface $fightService
    )
    {
        $this->fightService=$fightService;
        $this->unitHelper=$unitHelper;
        $this->structureHelper = $structureHelperService;
        $this->baseGeneration = $baseGeneration;
    }

    /**
     * @return FightServiceInterface
     */
    public function getFightService(): FightServiceInterface
    {
        return $this->fightService;
    }

    /**
     * @param FightServiceInterface $fightService
     */
    public function setFightService(FightServiceInterface $fightService)
    {
        $this->fightService = $fightService;
    }



    /**
     * @return BaseGenerationInterface
     */
    public function getBaseGeneration(): BaseGenerationInterface
    {
        return $this->baseGeneration;
    }

    /**
     * @return StructureHelperServiceInterface
     */
    public function getStructureHelper(): StructureHelperServiceInterface
    {
        return $this->structureHelper;
    }

    /**
     * @return UnitHelperInterface
     */
    public function getUnitHelper(): UnitHelperInterface
    {
        return $this->unitHelper;
    }

}