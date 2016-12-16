<?php
/**
 * Created by PhpStorm.
 * User: Arch
 * Date: 12.12.2016 г.
 * Time: 19:20 ч.
 */

namespace ArchBundle\Services\Fight;

use ArchBundle\Entity\Base;
use ArchBundle\Entity\Battle;
use ArchBundle\Entity\BattleLog;
use ArchBundle\Entity\BattleUnit;
use ArchBundle\Entity\Unit;
use ArchBundle\Entity\UnitName;
use ArchBundle\Entity\User;
use ArchBundle\Models\ViewModel\PlayerBaseModel;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Config\Definition\Exception\Exception;

class FightService implements FightServiceInterface
{
    const MIN_ARMY_RETURN = 1;
    const MAX_ARMY_RETURN = 4;


    /**
     * @param $battle Battle
     * @param $battleTime \DateTime
     * @param $doctrine Registry
     */
    private function generateBattleLog($battle,$battleTime,$doctrine)
    {
        $userBase=$battle->getDefenderBase();
        $attackerBase=$battle->getAttackerBase();
        $attacker=$attackerBase->getUser();
        $user=$userBase->getUser();
        $battleLog=new BattleLog();
        $battleLog->setTitle('Battle warning!!');
        $message=
            'Player '.$attacker->getUsername().
            ' is attacking you!!!He will be at you base .['.
            $userBase->getX().':'.$userBase->getY().'] at '.$battleTime->format('F j, Y, g:i a');
        $battleLog->setContent($message);
        $battleLog->setUser($user);
        $em=$doctrine->getManager();
        $em->persist($battleLog);
        $user->addBattleLog($battleLog);
        $em->persist($user);
        $em->flush();

    }
    /**
     * @param $attackerBase Base
     * @param $defenderBase Base
     * @param $army
     * @param $before
     * @param $doctrine Registry
     */
    public function prepareBattle($attackerBase,$defenderBase,$army,$before,$doctrine)
    {
        if($attackerBase->getUser()->getId()===$defenderBase->getUser()->getId())
        {
            throw new Exception("You cannot attack your own base!");
        }
        $battleCheck=$doctrine->
        getRepository(Battle::class)->
        findOneBy([
            'attackerBase'=>$attackerBase,'defenderBase'=>$defenderBase
        ]);
        if($battleCheck!==null){
            throw new Exception("You cannot attack the same base twice");
        }

        $coordinateX=[$attackerBase->getX(),$defenderBase->getX()];
        $coordinateY=[$attackerBase->getY(),$defenderBase->getY()];
        $battleTime=$this->calculateTime($coordinateX,$coordinateY);

        $uniNames=$doctrine->getRepository(UnitName::class)->findAll();
        $battle=new Battle();
        $battle->setStartsOn($battleTime);
        $battle->setAttackerBase($attackerBase);
        $battle->setDefenderBase($defenderBase);
        $em=$doctrine->getEntityManager();
        $em->persist($battle);
        $em->flush();

        $battleUnits=$this->createBattleUnits($battle,$army,$uniNames,$doctrine);
        $battle->setBattleUnits($battleUnits);
        $em->persist($battle);
        $em->flush();

        $this->subtractAttackerUnits($attackerBase, $army, $before, $doctrine);
        $this->generateBattleLog($battle,$battleTime,$doctrine);
    }

    /**
     * @param $battle
     * @param $armyArr
     * @param $unitNames
     * @param $doctrine Registry
     * @return array|ArrayCollection
     */
    private function createBattleUnits($battle,$armyArr,$unitNames,$doctrine)
    {
        $em=$doctrine->getManager();
        $battleUnits=new ArrayCollection();
        /**
         * @var $unitName UnitName
         */
        foreach ($unitNames as $unitName){
            $battleUnit=new BattleUnit();
            $battleUnit->setUnitName($unitName);
            $battleUnit->setCount($armyArr[$unitName->getName()]);
            $battleUnit->setBattle($battle);
            $em->persist($battleUnit);
            $em->flush();
        }
        return $battleUnits;
    }


    /**
     * @param $attackerBase
     * @param $defenderBase
     * @param $armyArr
     * @param $doctrine Registry
     * @param $before array
     */
    public function sendArmy($attackerBase, $defenderBase, $armyArr, $before, $doctrine)
    {

        $em = $doctrine->getEntityManager();
        $unitNames = $doctrine->getRepository(UnitName::class)->findAll();
        foreach ($unitNames as $unit) {
            $battleUnits = new BattleUnit();
            $battleUnits->setUnitName($unit);
            $battleUnits->setAmount($armyArr[$unit->getName()]);
            $em->persist($battleUnits);
            $em->flush();
        }
        $this->subtractAttackerUnits($attackerBase, $armyArr, $before, $doctrine);
    }

    /**
     * @param $attackerBase Base
     * @param $armyArr
     * @param $doctrine Registry
     * @param $before array
     */
    private function subtractAttackerUnits($attackerBase, $armyArr, $before, $doctrine)
    {
        /**
         * @var $unit Unit
         */
        foreach ($attackerBase->getUnits() as $unit) {
            $unit->setCount($before[$unit->getUnitName()->getName()] - $armyArr[$unit->getUnitName()->getName()]);
        }
        $em = $doctrine->getManager();
        $em->persist($attackerBase);
        $em->flush();
    }

    /**
     * @param $attackerBase Base
     * @param $doctrine Registry
     * @return mixed
     */
    public function getPlayerBattles($attackerBase,$doctrine)
    {
        $battlesToProcess=[];
        $currentDate=new \DateTime();
        $battleUnits=$doctrine->getRepository(Battle::class)->findBy(['attackerBase'=>$attackerBase,]);
        /**
         * @var $battle Battle
         */
        foreach ($battleUnits as $battle){
            if($currentDate>$battle->getStartsOn()){
                $battlesToProcess[]=$battle;
            }

        }
        return $battlesToProcess;
    }

    /**
     * @param $battle Battle
     * @param $doctrine Registry
     */
    public function organiseAssault($battle, $doctrine)
    {
        $attackerBase=$battle->getAttackerBase();
        $defenderBase=$battle->getDefenderBase();
        $attackerUnits =$battle->getBattleUnits();
        $battleResult = $this->isBaseDestroyed($attackerUnits, $defenderBase->getUnits());
        if ($battleResult[0] === true) {
            if ($battleResult[1] != true) {
                $attackerUnits = $this->battleCasualties($attackerUnits);
            }
            echo 'base Destroyed';
            $this->attackerWins($attackerBase->getUser(), $this->mapAttackerUnits($attackerUnits), $defenderBase, $doctrine);

        } else {
            echo 'base Survived';
            if ($battleResult[1] != true) {
                $defenderBase->setUnits($this->battleCasualties($defenderBase->getUnits()));
            }
            $this->defenderHolds($defenderBase, $doctrine);
        }
        $this->nullifyBattleUnits($battle, $doctrine);
    }

    /**
     * @param $attacker User
     * @param $attackerUnits  array
     * @param $defenderBase Base
     * @param $doctrine  Registry
     */
    private function attackerWins($attacker, $attackerUnits, $defenderBase, $doctrine)
    {
        $defenderBase->setUser($attacker);
        foreach ($defenderBase->getUnits() as $unit) {
            $unitName = $unit->getUnitName()->getName();
            if (!array_key_exists($unitName, $attackerUnits)) {
                $unit->setCount(0);
            } else {
                $unit->setCount($attackerUnits[$unitName]);
            }
        }
        $em = $doctrine->getManager();
        $em->persist($defenderBase);
        $em->flush();
    }

    /**
     * @param $battle Battle
     * @param $doctrine Registry
     * @var $unit BattleUnit
     */
    private function nullifyBattleUnits($battle, $doctrine)
    {
        $battleUnits=$battle->getBattleUnits();
        $em = $doctrine->getManager();
        foreach ($battleUnits as $unit) {
            $em->remove($unit);
            $em->flush();
        }
        $em->remove($battle);
        $em->flush();
    }

    /**
     * @param $defenderBase Base
     * @param $doctrine Registry
     */
    private function defenderHolds($defenderBase, $doctrine)
    {
        $em = $doctrine->getManager();
        $em->persist($defenderBase);
        $em->flush();
    }


    public function battleCasualties($forces)
    {
        /**
         * @var $units Unit
         */
        foreach ($forces as $units) {
            $survivalRate = rand(self::MIN_ARMY_RETURN, self::MAX_ARMY_RETURN) / 10;
            $units->setCount(round($units->getCount() * $survivalRate));
        }
        return $forces;
    }


    /**
     * @param $attackingForce
     * @param $defendingForce
     * @return mixed
     */
    public function isBaseDestroyed($attackingForce, $defendingForce)
    {
        $emptyBase = false;
        $attackerPoints = 0;
        $defenderPoints = 0;
        /**
         * @var $attackerUnit Unit
         */
        foreach ($attackingForce as $attackerUnit) {
            $attack = $attackerUnit->getUnitName()->getAttack();
            $attackerPoints += ($attack * $attackerUnit->getCount());
        }
        /**
         * @var $defenderUnit Unit
         */
        foreach ($defendingForce as $defenderUnit) {
            $defense = $defenderUnit->getUnitName()->getDefense();
            $defenderPoints += ($defense * $defenderUnit->getCount());
        }

        if ($defenderPoints == 0) {
            $emptyBase = true;
        }
        return [$attackerPoints > $defenderPoints, $emptyBase];
    }


    /**
     * @param $xArr array
     * @param $yArr array
     * @return \DateTime
     */
    public function calculateTime($xArr, $yArr)
    {
        $x = $xArr[0] - $xArr[1];
        $y = $yArr[0] - $yArr[1];
        $distance = ceil(sqrt(pow($x, 2) + pow($y, 2)));
        $distance *= 10;
        $attackTime = new \DateTime();
        $attackTime = $attackTime->add(\DateInterval::createFromDateString($distance . ' seconds'));
        return $attackTime;
    }
    /**
     * @param $army array
     * @return array
     */
    public function mapAttackerUnits($army)
    {
        $resultArray = [];
        /**
         * @var $units Unit
         */
        foreach ($army as $units) {
            if (!array_key_exists($units->getUnitName()->getName(), $resultArray)) {
                $resultArray[$units->getUnitName()->getName()] = 0;
            }
            $resultArray[$units->getUnitName()->getName()] += $units->getCount();
        }
        return $resultArray;
    }

    public function areMoreSoldiersAdded($currentUnits, $newlyEnteredUnits)
    {
        /**
         * @var $unit Unit
         */
        foreach ($newlyEnteredUnits as $unit) {
            if (($unit->getCount() > $currentUnits[$unit->getUnitName()->getName()] or ($unit->getCount() < 0))) {
                throw new Exception('You \'t gave that much '.$unit->getUnitName()->getName().'s !');
            }
        }
        return true;
    }


}