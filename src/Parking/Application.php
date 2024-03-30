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
use VSing\ParkingPlatform\Parking\Query\QueryCarByCarNo;


/**
 *  Class Application.
 *
 * @author mingyoung <mingyoungcheung@gmail.com>
 *
 * @property QueryCarByCarNo $queryCarByCarNo
 * @property Client $login
 *
 *
 */
class Application extends ServiceContainer
{

    protected $providers = [
        Query\ServiceProvider::class,
        Login\ServiceProvider::class,
        //        Store\ServiceProvider::class,
        //        Delivery\ServiceProvider::class,
        //        Order\ServiceProvider::class
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
