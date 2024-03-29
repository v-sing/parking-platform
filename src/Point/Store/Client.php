<?php

namespace VSing\ParkingPlatform\Point\Store;

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
    protected string $url = "/OpenApi/Get_ChainStorePagedV2";


    /**
     * @param $data
     * @return array
     */
    public function get($data): array
    {
        $data['userAccount']=$this->app->config->get('user_account');
        return $this->httpPost($this->url, $data);
    }
}
