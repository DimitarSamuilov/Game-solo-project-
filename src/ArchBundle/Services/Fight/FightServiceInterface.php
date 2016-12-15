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
    public function organiseAssault($battle,$doctrine);
    public function getBasesView($bases, $currentBase,$doctrine);
    public function areMoreSoldiersAdded($currentUnits,$newlyEnteredUnits);
    public function mapAttackerUnits($army);
    public function sendArmy($attackerBase,$defenderBase,$armyArr,$before,$doctrine);
    public function isBaseDestroyed($attackingForce, $defendingForce);
    public function getPlayerBattles($attackerBase,$doctrine);
    public function prepareBattle($attackerBase,$defenderBase,$army,$before,$doctrine);
}