<?php

namespace VSing\ParkingPlatform\Point\Coin;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use VSing\ParkingPlatform\Kernel\Http\Response;
use VSing\ParkingPlatform\Point\Kernel\BaseClient;

class Client extends BaseClient
{

    /**
     * 增加减少用户积分
     * @var string
     */
    protected string $url = "/OpenApi/Update_MemberPoint";


    /**
     * userAccount    是    工号
     * cardId    是    卡号
     * point    是    增加/扣除积分数
     * meno    是    备注
     * isSendMsg    否    是否发送消息（短信或者微信消息）,true 或者 false
     * @param $data {"userAccount":"10000","cardId":"cardidTest","point":999,"meno":"我是备注信息","isSendMsg":"false"}
     * @return array
     */
    public function get($data): array
    {
        $data['userAccount'] = $this->app->config->get('user_account');
        return $this->httpPost($this->url, $data);
    }
}
