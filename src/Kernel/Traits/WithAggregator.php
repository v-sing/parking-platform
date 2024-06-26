<?php

declare(strict_types=1);

/*
 * This file is part of the ParkingPlatformComposer.
 *
 * (c) 张铭阳 <mingyoungcheung@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace VSing\ParkingPlatform\Kernel\Traits;

use VSing\ParkingPlatform\ParkingPlatform;
use VSing\ParkingPlatform\Kernel\BaseClient;
use VSing\ParkingPlatform\Kernel\Delegation\DelegationTo;

trait WithAggregator
{
    /**
     * Aggregate.
     */
    protected function aggregate()
    {
        foreach (ParkingPlatform::config() as $key => $value) {
            $this['config']->set($key, $value);
        }
    }

    /**
     * @return bool
     */
    public function shouldDelegate($id)
    {
        return $this['config']->get('delegation.enabled')
            && $this->offsetGet($id) instanceof BaseClient;
    }

    /**
     * @return $this
     */
    public function shouldntDelegate()
    {
        $this['config']->set('delegation.enabled', false);

        return $this;
    }

    /**
     * @param string $id
     *
     * @return VSing\ParkingPlatform\Kernel\Delegation\DelegationTo
     */
    public function delegateTo($id)
    {
        return new DelegationTo($this, $id);
    }
}
