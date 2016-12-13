<?php
/**
 * Created by PhpStorm.
 * User: Arch
 * Date: 12.12.2016 г.
 * Time: 19:41 ч.
 */

namespace ArchBundle\Models\Utility;


class FightingUnit
{
    private $name;
    private $count;
    private $id;

    public function __construct($id,$name,$count)
    {
        $this->name=$name;
        $this->id=$id;
        $this->count=$count;
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
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param mixed $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }


}