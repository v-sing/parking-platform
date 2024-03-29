<?php

namespace VSing\ParkingPlatform\Parking\Query;


use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use VSing\ParkingPlatform\Kernel\Contracts\Arrayable;
use VSing\ParkingPlatform\Parking\Kernel\BaseClient;
use VSing\ParkingPlatform\Kernel\Exceptions\InvalidConfigException;
use VSing\ParkingPlatform\Kernel\Http\Response;
use VSing\ParkingPlatform\Kernel\Support\Collection;

class QueryParkSpace extends BaseClient
{
    /**
     *
     * @var string
     */
    protected $url = "";

    /**
     * 服务标识
     * @var string
     */
    protected $serviceId = '3c.park.queryparkspace';

    /**
     *
     * @param $data
     * @return array|object|ResponseInterface|Arrayable|Response|Collection
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function get($data)
    {
        return $this->httpPostJson($this->url, $data);
    }
}
