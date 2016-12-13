<?php
/**
 * Created by PhpStorm.
 * User: Arch
 * Date: 12.12.2016 г.
 * Time: 16:32 ч.
 */

namespace ArchBundle\Models\ViewModel;


class PlayerBaseModel
{
    private $Id;
    private $userUsername;
    private $userId;
    private $x;
    private $y;
    private $time;

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->Id;
    }

    /**
     * @param mixed $Id
     */
    public function setId($Id)
    {
        $this->Id = $Id;
    }

    /**
     * @return mixed
     */
    public function getUserUsername()
    {
        return $this->userUsername;
    }

    /**
     * @param mixed $userUsername
     */
    public function setUserUsername($userUsername)
    {
        $this->userUsername = $userUsername;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param mixed $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }

    /**
     * @return mixed
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param mixed $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }

}