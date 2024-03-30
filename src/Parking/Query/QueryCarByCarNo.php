<?php

namespace VSing\ParkingPlatform\Parking\Query;


use VSing\ParkingPlatform\Parking\Kernel\BaseClient;

class QueryCarByCarNo extends BaseClient
{
    /**
     *
     * @var string
     */
    protected string $url = "/jsaims/as";

    /**
     * 查询相似车辆
     * @var string
     */
    protected $serviceId = '3c.pay.querycarbycarno';

    /**
     *
     * @param $data
     * @return array
     */
    public function get($data): array
    {
        return $this->httpPostJson($this->url, $data);
    }
}
