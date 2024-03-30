<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) v-sing <email1946367301@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace VSing\ParkingPlatform\Parking;

use VSing\ParkingPlatform\Kernel\ServiceContainer;
use VSing\ParkingPlatform\Parking\Login\Client;
use VSing\ParkingPlatform\Parking\Order\CreateOrder;
use VSing\ParkingPlatform\Parking\Order\GenerateOrder;
use VSing\ParkingPlatform\Parking\Order\NotifyOrder;
use VSing\ParkingPlatform\Parking\Order\QueryOrder;
use VSing\ParkingPlatform\Parking\Query\QueryCarByCarNo;


/**
 *  Class Application.
 *
 * @author v-sing <email1946367301@163.com>
 *
 * @property QueryCarByCarNo $queryCarByCarNo
 * @property Client $login
 * @property CreateOrder $createOrder
 * @property GenerateOrder $generateOrder
 * @property NotifyOrder $notifyOrder
 * @property QueryOrder $queryOrder
 *
 *
 */
class Application extends ServiceContainer
{

    protected $providers = [
        Query\ServiceProvider::class,
        Login\ServiceProvider::class,
        Order\ServiceProvider::class,
    ];

    /**
     * Handle dynamic calls.
     *
     * @param string $method
     * @param array $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        return $this->base->$method(...$args);
    }
}
