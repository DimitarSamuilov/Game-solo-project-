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
use ArchBundle\Services\View\ViewHelperInterface;

class ServiceHolder
{

    private $structureHelper;
    private $baseGeneration;
    private $unitHelper;
    private $fightService;
    private $viewHelper;
    public function __construct
    (
        ViewHelperInterface $viewHelper,
        BaseGenerationInterface $baseGeneration,
        StructureHelperServiceInterface $structureHelperService,
        UnitHelperInterface $unitHelper,
        FightServiceInterface $fightService
    )
    {
        $this->viewHelper=$viewHelper;
        $this->fightService=$fightService;
        $this->unitHelper=$unitHelper;
        $this->structureHelper = $structureHelperService;
        $this->baseGeneration = $baseGeneration;
    }

    /**
     * @return ViewHelperInterface
     */
    public function getViewHelper(): ViewHelperInterface
    {
        return $this->viewHelper;
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