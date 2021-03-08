<?php

namespace EasyUmeng\UVerify\Kernel;

use Closure;
use EasyUmeng\Kernel\Exceptions\InvalidConfigException;
use EasyUmeng\Kernel\ServiceContainer;
use EasyUmeng\Kernel\Support\Collection;
use EasyUmeng\Kernel\Traits\HasHttpRequests;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

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
     * @param array  $data
     *
     * @return ResponseInterface|Collection|array|object|string
     *
     * @throws InvalidConfigException
     * @throws GuzzleException
     */
    public function httpPost(string $url, array $data = [])
    {
        return $this->request($url, 'POST', ['form_params' => $data]);
    }

    /**
     * JSON request.
     *
     * @param string $url
     * @param array  $data
     * @param array  $query
     *
     * @return ResponseInterface|Collection|array|object|string
     *
     * @throws InvalidConfigException
     * @throws GuzzleException
     */
    public function httpPostJson(string $url, array $data = [], array $query = [])
    {
        return $this->request($url, 'POST', ['query' => $query, 'json' => $data]);
    }

    /**
     * @param string $url
     * @param string $method
     * @param array  $options
     * @param bool   $returnRaw
     *
     * @return ResponseInterface|Collection|array|object|string
     *
     * @throws InvalidConfigException
     * @throws GuzzleException
     */
    public function request(string $url, string $method = 'GET', array $options = [], $returnRaw = false)
    {
        if (empty($this->middlewares)) {
            $this->registerHttpMiddlewares();
        }

        $query = $options['query'];
        $path = '/' . ltrim(parse_url($url, PHP_URL_PATH), '/');

        $baseHeaders = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-Ca-Version' => 1,
            'X-Ca-Signature-Headers' => 'X-Ca-Version,X-Ca-Stage,X-Ca-Key,X-Ca-Timestamp,X-Ca-Nonce',
            'X-Ca-Stage' => 'RELEASE',
            'X-Ca-Key' => $this->app['config']->alikey,
            'X-Ca-Nonce' => md5(json_encode($query) . uniqid('', false)),
            'X-Ca-Timestamp' => (string)(time() * 1000),
        ];
        $baseHeaders['X-Ca-Signature'] = Utils::generateSign($method, $path, $query, [], $baseHeaders, $this->app['config']->secret);

        $options = array_merge([
            'headers' => $baseHeaders
        ], $options);

        $this->app['logger']->debug("signature:{$baseHeaders['X-Ca-Signature']}");
        $this->app['logger']->debug("GuzzleRequestOption", $options);

        $response = $this->performRequest($url, $method, $options);

        return $returnRaw ? $response : $this->castResponseToType($response, $this->app['config']->get('response_type'));
    }

    /**
     * Register Guzzle middlewares.
     */
    protected function registerHttpMiddlewares()
    {
        // retry
        $this->pushMiddleware($this->retryMiddleware(), 'retry');
    }

    /**
     * Return retry middleware.
     *
     * @return Closure
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
                // Retry on server errors
                return $retries < $this->app->config->get(
                    'http.max_retries',
                    1
                ) && $response && $response->getStatusCode() >= 500;
            },
            function () {
                return abs($this->app->config->get('http.retry_delay', 500));
            }
        );
    }
}
