<?php

namespace App\Repositories;

use App\Libraries\RedisLibrary;
use App\Utilities\ResponseTrait;

class OrderRepository
{
    use ResponseTrait;

    private $oRedisLibrary;

    /**
     * OrderRepository constructor
     * @param RedisLibrary $oRedisLibrary
     */
    public function __construct(RedisLibrary $oRedisLibrary)
    {
        $this->oRedisLibrary = $oRedisLibrary;
    }

    /**
     * Save order in redis
     * @param string $sOrderKey
     * @param array $aOrder
     */
    public function storeOrderInRedis(string $sOrderKey, array $aOrder)
    {
        return $this->oRedisLibrary->setData($sOrderKey, json_encode($aOrder));
    }

    /**
     * Retrieve order from redis
     * @param string $sOrderKey
     */
    public function getOrderFromRedis(string $sOrderKey)
    {
        $oOrderData = $this->oRedisLibrary->get($sOrderKey);
        if(empty($oOrderData) === true) {
            return $this->failedResponse(404, 'Order not found');
        }

        return $this->successResponse(json_decode($oOrderData, true));
    }
}