<?php

namespace VSing\ParkingPlatform\Parking\Order;

use VSing\ParkingPlatform\Parking\Kernel\BaseClient;

class QueryOrder extends BaseClient
{
    /**
     *
     * @var string
     */
    protected string $url = "/jsaims/as";

    /**
     * 订单结果查询接口
     * @var string
     */
    protected $serviceId = '3c.pay.queryorder';

    /**
     * 订单结果查询接口
     * @param $data
     * @return array
     */
    public function get($data): array
    {
        return $this->httpPostJson($this->url, $data);
    }
}
