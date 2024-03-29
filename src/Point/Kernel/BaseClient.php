<?php

namespace VSing\ParkingPlatform\Point\Kernel;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use VSing\ParkingPlatform\Kernel\Contracts\Arrayable;
use VSing\ParkingPlatform\Kernel\Exceptions\InvalidConfigException;
use VSing\ParkingPlatform\Kernel\Http\Response;
use VSing\ParkingPlatform\Kernel\Log\LogManager;
use VSing\ParkingPlatform\Kernel\ServiceContainer;
use VSing\ParkingPlatform\Kernel\Sign\MD5;
use VSing\ParkingPlatform\Kernel\Sign\PointMD5;
use VSing\ParkingPlatform\Kernel\Support\Collection;
use VSing\ParkingPlatform\Kernel\Traits\HasHttpRequests;

class BaseClient
{
    use HasHttpRequests {
        request as performRequest;
    }

    /**
     * @var ServiceContainer
     */
    protected ServiceContainer $app;

    /**
     * @var string
     */
    protected $baseUri;


    /**
     * BaseClient constructor.
     *
     * @param ServiceContainer $app
     */
    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
    }


    /**
     * POST request.
     *
     * @param string $url
     * @param array $body
     * @return array|Response|ResponseInterface
     *
     */
    public function httpPost(string $url, array $body = [])
    {

        $openid       = $this->app->config->get('openid', '');
        $secret       = $this->app->config->get('secret', '');
        $userAccount  = $this->app->config->get('user_account', '');
        $signature    = PointMD5::getSign($body, $openid, $secret);
        $data['data'] = json_encode($body);
        $data         = [
            'data' => json_encode($body)
        ];
        $query        = '?openId=' . $openid . '&signature=' . $signature . '&timestamp=' . time();
        try {
            $res = $this->request($url . $query, 'POST', ['form_params' => $data]);
            return json_decode((string)$res->getBody(), true) ?? ['status' => -1, '请求失败'];
        } catch (GuzzleException $e) {
            return ['status' => -1, '请求失败'];
        }

    }

}
