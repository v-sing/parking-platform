<?php

namespace VSing\ParkingPlatform\Parking\Login;

use VSing\ParkingPlatform\Parking\Kernel\BaseClient;

class Client extends BaseClient
{

    protected $url = '/login';

    public function get($data){

        $this->httpPostJson();
    }
}
