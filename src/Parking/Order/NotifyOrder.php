<?php

namespace VSing\ParkingPlatform\Parking\Order;

use VSing\ParkingPlatform\Parking\Kernel\BaseClient;

class NotifyOrder extends BaseClient
{
    /**
     *
     * @var string
     */
    protected string $url = "/jsaims/as";

    /**
     * 支付结果通知
     * @var string
     */
    protected $serviceId = '3c.pay.notifyorderresult';

    /**
     * 支付结果通知
     * @param $data
     * @return array
     */
    public function get($data): array
    {
        return $this->httpPostJson($this->url, $data);
    }
}
