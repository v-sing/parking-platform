<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace  VSing\ParkingPlatform\Parking;

use VSing\ParkingPlatform\Kernel\ServiceContainer;
use VSing\ParkingPlatform\Parking\Query\QueryParkSpace;


/**
 *  Class Application.
 *
 * @author mingyoung <mingyoungcheung@gmail.com>
 *
 * @property QueryParkSpace $queryParkSpace
 *
 *
 */
class Application extends ServiceContainer
{

    protected $providers = [
        Query\ServiceProvider::class,
//        Merchant\ServiceProvider::class,
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