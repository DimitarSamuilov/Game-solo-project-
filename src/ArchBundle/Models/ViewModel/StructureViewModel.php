<?php
/**
 * Created by PhpStorm.
 * User: Arch
 * Date: 11.12.2016 г.
 * Time: 13:52 ч.
 */

namespace ArchBundle\Models\ViewModel;


class StructureViewModel
{

    private $name;
    private $level;
    private $coin;
    private $wood;
    private $id;
    private $username;
    private $upgradeTime;

    /**
     * @return mixed
     */
    public function getUpgradeTime()
    {
        return $this->upgradeTime;
    }

    /**
     * @param mixed $upgradeTime
     */
    public function setUpgradeTime($upgradeTime)
    {
        $this->upgradeTime = $upgradeTime->format('Y-m-d H:i:s');
        //$this->upgradeTime = $upgradeTime->diff(new \DateTime())->format("%d days %h hours %i minutes ");
    }
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return mixed
     */
    public function getCoin()
    {
        return $this->coin;
    }

    /**
     * @param mixed $coin
     */
    public function setCoin($coin)
    {
        $this->coin = $coin;
    }

    /**
     * @return mixed
     */
    public function getWood()
    {
        return $this->wood;
    }

    /**
     * @param mixed $wood
     */
    public function setWood($wood)
    {
        $this->wood = $wood;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }


}