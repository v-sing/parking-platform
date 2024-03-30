<?php

namespace VSing\ParkingPlatform\Parking\Order;


use VSing\ParkingPlatform\Parking\Kernel\BaseClient;

class CreateOrder extends BaseClient
{
    /**
     *
     * @var string
     */
    protected string $url = "/jsaims/as";

    /**
     * 按车牌生成订单
     * @var string
     */
    protected $serviceId = '3c.pay.createorderbycarno';

    /**
     * 按车牌生成订单
     * @param $data
     * @return array
     */
    public function get($data): array
    {
        return $this->httpPostJson($this->url, $data);
    }
}
