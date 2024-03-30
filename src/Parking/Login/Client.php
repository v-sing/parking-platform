<?php

namespace VSing\ParkingPlatform\Parking\Login;

use GuzzleHttp\Exception\GuzzleException;
use VSing\ParkingPlatform\Factory;
use VSing\ParkingPlatform\Kernel\Cache;
use VSing\ParkingPlatform\Kernel\Exceptions\InvalidConfigException;
use VSing\ParkingPlatform\Parking\Kernel\BaseClient;

class Client extends BaseClient
{

    protected $url = 'jsaims/login';

    public function get()
    {
        $data = [
            'cid'      => $this->app->config->get('cid'),
            'user'     => $this->app->config->get('user'),
            'password' => $this->app->config->get('password')
        ];
        $dat  = http_build_query($data);
        try {
            $res = $this->request($this->url . '?' . $dat, 'GET');
            return $res ?? ['resultCode' => 1, 'message' => '请求失败'];
        } catch (GuzzleException|InvalidConfigException $e) {
            return ['resultCode' => 1, 'message' => '请求失败'];
        }

    }
}
