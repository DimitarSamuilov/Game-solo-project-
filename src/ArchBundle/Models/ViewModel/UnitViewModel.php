<?php
/**
 * Created by PhpStorm.
 * User: Arch
 * Date: 11.12.2016 г.
 * Time: 13:25 ч.
 */

namespace ArchBundle\Models\ViewModel;


class UnitViewModel
{
    private $name;
    private $wood;
    private $coin;
    private $count;
    private $productionTime;
    private $productionAmount;

    /**
     * @return mixed
     */
    public function getProductionAmount()
    {
        return $this->productionAmount;
    }

    /**
     * @param mixed $productionAmount
     */
    public function setProductionAmount($productionAmount)
    {
        $this->productionAmount = $productionAmount;
    }



    /**
     * @return mixed
     */
    public function getProductionTime()
    {
        return $this->productionTime;
    }

    /**
     * @param mixed $productionTime
     */
    public function setProductionTime($productionTime)
    {
        $this->productionTime = $productionTime->format('Y-m-d H:i:s');
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