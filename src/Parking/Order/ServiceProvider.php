<?php

namespace VSing\ParkingPlatform\Parking\Order;


use Pimple\Container;
use Pimple\ServiceProviderInterface;

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

        !isset($pimple['createOrder']) && $pimple['createOrder'] = function ($pimple) {
            return new CreateOrder($pimple);
        };
        !isset($pimple['generateOrder']) && $pimple['generateOrder'] = function ($pimple) {
            return new GenerateOrder($pimple);
        };
        !isset($pimple['notifyOrder']) && $pimple['notifyOrder'] = function ($pimple) {
            return new NotifyOrder($pimple);
        };
        !isset($pimple['queryOrder']) && $pimple['queryOrder'] = function ($pimple) {
            return new NotifyOrder($pimple);
        };

    }
}
