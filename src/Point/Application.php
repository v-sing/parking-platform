<?php

namespace VSing\ParkingPlatform\Point;

use VSing\ParkingPlatform\Kernel\ServiceContainer;


/**
 *  Class Application.
 *
 * @author v-sing <email1946367301@163.com>
 *
 * @property \VSing\ParkingPlatform\Point\Store\Client $store
 * @property \VSing\ParkingPlatform\Point\Member\Client $memberInfo
 * @property \VSing\ParkingPlatform\Point\Coin\Client $coin
 *
 *
 */
class Application extends ServiceContainer
{

    protected $providers = [
        Store\ServiceProvider::class,
        Member\ServiceProvider::class,
        Coin\ServiceProvider::class,
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
