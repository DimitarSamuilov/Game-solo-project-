<?php
/**
 * Created by PhpStorm.
 * User: Arch
 * Date: 15.12.2016 г.
 * Time: 20:42 ч.
 */

namespace ArchBundle\Services\View;


interface ViewHelperInterface
{
    public function getBasesView($bases, $currentBase, $doctrine);

    public function prepareStructureViewModel($structures, $user);

    public function getViewArray($unitRepo);

    public function formatCountDownTime($date);

}