<?php

namespace VSing\ParkingPlatform\Parking\Order;


use VSing\ParkingPlatform\Parking\Kernel\BaseClient;

class GenerateOrder extends BaseClient
{
    /**
     *
     * @var string
     */
    protected string $url = "/jsaims/as";

    /**
     * 按车牌生成订单（不限定车场）
     * @var string
     */
    protected $serviceId = '3c.pay.generateorderbycarno';

    /**
     * 按车牌生成订单（不限定车场）
     * @param $data
     * @return array
     */
    public function get($data): array
    {
        return $this->httpPostJson($this->url, $data);
    }
}
