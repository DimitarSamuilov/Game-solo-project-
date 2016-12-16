<?php
/**
 * Created by PhpStorm.
 * User: Arch
 * Date: 7.12.2016 г.
 * Time: 15:20 ч.
 */

namespace ArchBundle\Services\Base;


interface BaseGenerationInterface
{
    public function generateBases( $doctrine,$id);

    public function resourcePassiveIncome($baseId,$doctrine);

}