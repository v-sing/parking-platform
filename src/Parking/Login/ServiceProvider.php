<?php

namespace VSing\ParkingPlatform\Parking\login;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use VSing\ParkingPlatform\Parking\Login\Client;
class ServiceProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple): void
    {

        !isset($pimple['login']) && $pimple['login'] = function ($pimple) {
            return new Client($pimple);
        };

    }
}
