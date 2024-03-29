<?php

/*
 * This file is part of the ours-outsource/keloop-and-squirrel-house.
 *
 * (c) oursoutsource <email1946367301@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace VSing\ParkingPlatform\Kernel\Contracts;

use ArrayAccess;

/**
 * Interface Arrayable.
 *
 * @author oursoutsource <email1946367301@163.com>
 */
interface Arrayable extends ArrayAccess
{
    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray();
}
