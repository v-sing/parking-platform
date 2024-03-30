<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) v-sing <email1946367301@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace VSing\ParkingPlatform\Kernel;

use GuzzleHttp\Client;
use Monolog\Logger;
use VSing\ParkingPlatform\Kernel\Cache\Cache;
use VSing\ParkingPlatform\Kernel\Providers\CacheServiceProvider;
use VSing\ParkingPlatform\Kernel\Providers\EventDispatcherServiceProvider;
use VSing\ParkingPlatform\Kernel\Providers\ExtensionServiceProvider;
use VSing\ParkingPlatform\Kernel\Providers\HttpClientServiceProvider;
use VSing\ParkingPlatform\Kernel\Providers\LogServiceProvider;
use VSing\ParkingPlatform\Kernel\Providers\RequestServiceProvider;
use VSing\ParkingPlatform\Kernel\Providers\ConfigServiceProvider;
use VSing\ParkingPlatform\Kernel\Traits\WithAggregator;
use Pimple\Container;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ServiceContainer.
 *
 * @author v-sing <email1946367301@163.com>
 *
 * @property Config $config
 * @property Request $request
 * @property Client $http_client
 * @property Logger $logger
 * @property Cache $cache
 * @property EventDispatcher $events
 */
class ServiceContainer extends Container
{
    use WithAggregator;

    protected $base;
    /**
     * @var string
     */
    protected $id;

    /**
     * @var array
     */
    protected $providers = [];

    /**
     * @var array
     */
    protected $defaultConfig = [];

    /**
     * @var array
     */
    protected $userConfig = [];

    /**
     * Constructor.
     *
     * @param array $config
     * @param array $prepends
     * @param string|null $id
     */
    public function __construct(array $config = [], array $prepends = [], string $id = null)
    {
        $this->userConfig = $config;

        parent::__construct($prepends);

        $this->registerProviders($this->getProviders());

        $this->id = $id;

        $this->aggregate();

        $this->events->dispatch(new Events\ApplicationInitialized($this));
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id ?? $this->id = md5(json_encode($this->userConfig));
    }

    /**
     * @return array
     */
    public function getConfig()
    {
            $base = [
                'http' => [
                    'timeout' => 30.0,
                    'base_uri' => 'https://open.keloop.cn/',
                ],
            ];

        return array_replace_recursive($base, $this->defaultConfig, $this->userConfig);
    }

    /**
     * Return all providers.
     *
     * @return array
     */
    public function getProviders()
    {
        return array_merge([
            ConfigServiceProvider::class,
            CacheServiceProvider::class,
            LogServiceProvider::class,
            RequestServiceProvider::class,
            HttpClientServiceProvider::class,
            ExtensionServiceProvider::class,
            EventDispatcherServiceProvider::class,
        ], $this->providers);
    }

    /**
     * @param string $id
     * @param mixed $value
     */
    public function rebind($id, $value)
    {
        $this->offsetUnset($id);
        $this->offsetSet($id, $value);
    }

    /**
     * Magic get access.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function __get($id)
    {
        if ($this->shouldDelegate($id)) {
            return $this->delegateTo($id);
        }

        return $this->offsetGet($id);
    }

    /**
     * Magic set access.
     *
     * @param string $id
     * @param mixed $value
     */
    public function __set($id, $value)
    {
        $this->offsetSet($id, $value);
    }

    /**
     * @param array $providers
     */
    public function registerProviders(array $providers)
    {
        foreach ($providers as $provider) {
            parent::register(new $provider());
        }
    }
}
