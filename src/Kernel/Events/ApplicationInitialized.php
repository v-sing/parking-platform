<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) v-sing <email1946367301@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace VSing\ParkingPlatform\Kernel\Events;

use VSing\ParkingPlatform\Kernel\ServiceContainer;

/**
 * Class ApplicationInitialized.
 *
 * @author v-sing <email1946367301@163.com>
 */
class ApplicationInitialized
{
    /**
     * @var ServiceContainer
     */
    public $app;

    /**
     * @param ServiceContainer $app
     */
    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
    }
}
