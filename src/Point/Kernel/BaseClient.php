<?php

namespace VSing\ParkingPlatform\Point\Kernel;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LogLevel;
use VSing\ParkingPlatform\Kernel\Contracts\Arrayable;
use VSing\ParkingPlatform\Kernel\Events\HttpResponseCreated;
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
            return $res ?? ['status' => -1, '请求失败'];
        } catch (GuzzleException $e) {
            return ['status' => -1, '请求失败'];
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
