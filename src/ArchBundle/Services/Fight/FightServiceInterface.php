<?php
/**
 * Created by PhpStorm.
 * User: Arch
 * Date: 12.12.2016 г.
 * Time: 19:20 ч.
 */

namespace ArchBundle\Services\Fight;


interface FightServiceInterface
{
    public function organiseAssault($attackerBase, $defenderBase,$doctrine);
    public function getBasesView($bases, $currentBase);
    public function areMoreSoldiersAdded($currentUnits,$newlyEnteredUnits);
    public function mapAttackerUnits($army);
    public function sendArmy($attackerBase,$defenderBase,$armyArr,$before,$doctrine);
    public function isBaseDestroyed($attackingForce, $defendingForce);
}