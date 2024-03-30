<?php

namespace VSing\ParkingPlatform\Parking\Kernel;

use GuzzleHttp\Exception\GuzzleException;
use VSing\ParkingPlatform\Factory;
use VSing\ParkingPlatform\Kernel\Cache;
use VSing\ParkingPlatform\Kernel\Contracts\Arrayable;
use VSing\ParkingPlatform\Kernel\Events\HttpResponseCreated;
use VSing\ParkingPlatform\Kernel\Exceptions\InvalidConfigException;
use  VSing\ParkingPlatform\Kernel\Http\Response;
use VSing\ParkingPlatform\Kernel\ServiceContainer;
use VSing\ParkingPlatform\Kernel\Sign\MD5;
use VSing\ParkingPlatform\Kernel\Sign\ParkMD5;
use VSing\ParkingPlatform\Kernel\Sign\SquireelMD5;
use VSing\ParkingPlatform\Kernel\Support\Collection;
use  VSing\ParkingPlatform\Kernel\Traits\HasHttpRequests;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LogLevel;

class BaseClient
{
    use HasHttpRequests {
        request as performRequest;
    }


    /**
     * @var ServiceContainer
     */
    protected $app;

    /**
     * @var string
     */
    protected $baseUri;

    protected $requestType = 'DATA';
    protected $serviceId = '';

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
     * GET request.
     *
     * @param string $url
     * @param array $query
     *
     * @return array|object|Arrayable|Response|Collection|ResponseInterface
     *
     * @throws GuzzleException|InvalidConfigException
     */
    public function httpGet(string $url, array $query = [])
    {
        return $this->request($url, 'GET', ['query' => $query]);
    }

    /**
     * POST request.
     *
     * @param string $url
     * @param array $body
     * @return array|Arrayable|Collection|object|Response|ResponseInterface
     *
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function httpPost(string $url, array $body = [])
    {

        $dev_key      = $this->app->config->get("dev_key", '');
        $team_token   = $this->app->config->get("team_token", '');
        $version      = $this->app->config->get("version", 1);
        $ticket       = $this->app->config->get("ticket", '');
        $dev_secret   = $this->app->config->get("dev_secret", '');
        $data         = [
            "version"    => $version,
            "timestamp"  => time(),
            "team_token" => $team_token,
            "dev_key"    => $dev_key,
            "ticket"     => $ticket,
            "body"       => json_encode($body)
        ];
        $data['sign'] = MD5::getSign($data, $dev_secret);
        return $this->request($url, 'POST', ['form_params' => $data]);
    }

    /**
     * JSON request.
     *
     * @param string $url
     * @param array $data
     * @param array $query
     *
     * @return array
     *
     */
    public function httpPostJson(string $url, array $data = [], $item = [], array $query = []): array
    {
        $cacheKey = 'login-token';
        $token    = Cache::get($cacheKey);
        if (!$token) {
            $result = $this->app['login']->get();
            if ($result['resultCode'] !== 0) {
                return $result;
            }
            $token = $result['token'];
            Cache::set($cacheKey, $token, ((int)$this->app->config->get('expire')) * 60);
        }
        $body = [
            'cid' => $this->app->config->get('cid'),
            'v'   => $this->app->config->get('v'),
            'tn'  => $token,
        ];
        $p    = [
            'serviceId'   => $this->serviceId,
            'requestType' => $this->requestType,
            'attributes'  => $data,
        ];
        if ($item) {
            $p['dataItems'] = $item;
        }
        $body['p']  = json_encode($p);
        $body['sn'] = ParkMD5::makeSign($p, $this->app->config->get('signKey'));
        try {
            return $this->request($url . "?" . http_build_query($body), 'POST');
        } catch (GuzzleException $e) {
            return ['resultCode' => 1, 'message' => 'GuzzleException失败'];
        } catch (InvalidConfigException $e) {
            return ['resultCode' => 1, 'message' => 'InvalidConfigException失败'];
        }
    }


    /**
     * @param string $url
     * @param string $method
     * @param array $options
     *
     * @return array|object|Arrayable|Response|Collection|ResponseInterface
     *
     * @throws InvalidConfigException
     * @throws GuzzleException
     */
    public function request(string $url, string $method = 'GET', array $options = [], $returnRaw = false)
    {

        if (empty($this->middlewares)) {
            $this->registerHttpMiddlewares();
        }

        $response = $this->performRequest($url, $method, $options);
        $this->app->events->dispatch(new HttpResponseCreated($response));

        return $returnRaw ? $response : $this->castResponseToType($response, $this->app->config->get('response_type'));
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $options
     *
     * @return Response
     *
     * @throws InvalidConfigException
     * @throws GuzzleException
     */
    public function requestRaw(string $url, string $method = 'GET', array $options = [])
    {
        return Response::buildFromPsrResponse($this->request($url, $method, $options, true));
    }

    /**
     * Register Guzzle middlewares.
     */
    protected function registerHttpMiddlewares()
    {
        // retry
        $this->pushMiddleware($this->retryMiddleware(), 'retry');
        // log
        $this->pushMiddleware($this->logMiddleware(), 'log');
    }

    /**
     * Log the request.
     *
     * @return \Closure
     */
    protected function logMiddleware()
    {
        $formatter = new MessageFormatter($this->app['config']['http.log_template'] ?? MessageFormatter::DEBUG);
        return Middleware::log($this->app['logger'], $formatter, LogLevel::DEBUG);
    }

    /**
     * Return retry middleware.
     *
     * @return \Closure
     */
    protected function retryMiddleware()
    {
        return Middleware::retry(
            function (
                $retries,
                RequestInterface $request,
                ResponseInterface $response = null
            ) {
                // Limit the number of retries to 2
                if ($retries < $this->app->config->get('http.max_retries', 1) && $response && $body = $response->getBody()) {
                    // Retry on server errors
                    $response = json_decode($body, true);

                    if (!empty($response['errcode']) && in_array(abs($response['errcode']), [40001, 40014, 42001], true)) {
                        $this->app['logger']->debug('Retrying with refreshed access token.');
                        return true;
                    }
                }

                return false;
            },
            function () {
                return abs($this->app->config->get('http.retry_delay', 500));
            }
        );
    }
}
