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

namespace VSing\ParkingPlatform\Kernel\Delegation;

use VSing\ParkingPlatform\Factory;
use VSing\ParkingPlatform\Kernel\Http\DelegationResponse;
use VSing\ParkingPlatform\Kernel\ServiceContainer;

class Hydrate
{
    /**
     * @var array
     */
    protected $attributes;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @return array
     */
    public function handle()
    {
        $app = $this->createsApplication()->shouldntDelegate();

        foreach ($this->attributes['identifiers'] as $identifier) {
            $app = $app->$identifier;
        }

        return call_user_func_array([$app, $this->attributes['method']], $this->attributes['arguments']);
    }

    /**
     * @return ServiceContainer
     */
    protected function createsApplication()
    {
        $application = $this->attributes['application'];

//        if ($application === ParkingPlatform\OpenPlatform\Authorizer\OfficialAccount\Application::class) {
//            return $this->createsOpenPlatformApplication('officialAccount');
//        }
//
//        if ($application === ParkingPlatform\OpenPlatform\Authorizer\MiniProgram\Application::class) {
//            return $this->createsOpenPlatformApplication('miniProgram');
//        }

        return new $application($this->buildConfig($this->attributes['config']));
    }

    protected function createsOpenPlatformApplication($type)
    {
        $config = $this->attributes['config'];

        $authorizerAppId = $config['app_id'];

        $config['app_id'] = $config['component_app_id'];

        return Factory::openPlatform($this->buildConfig($config))->$type($authorizerAppId, $config['refresh_token']);
    }

    protected function buildConfig(array $config)
    {
        $config['response_type'] = DelegationResponse::class;

        return $config;
    }
}
