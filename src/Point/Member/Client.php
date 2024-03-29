<?php

namespace VSing\ParkingPlatform\Point\Member;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use VSing\ParkingPlatform\Kernel\Http\Response;
use VSing\ParkingPlatform\Point\Kernel\BaseClient;

class Client extends BaseClient
{

    /**
     * 获取店铺接口地址
     * @var string
     */
    protected string $url = "/OpenApi/Get_MemberInfo";


    /**
     * @param $data
     * @return array
     */
    public function get($data): array
    {
        return $this->httpPost($this->url, $data);
    }
}
