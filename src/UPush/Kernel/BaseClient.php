<?php

namespace EasyUmeng\UPush\Kernel;

use Closure;
use EasyUmeng\Kernel\Contracts\Arrayable;
use EasyUmeng\Kernel\Exceptions\InvalidConfigException;
use EasyUmeng\Kernel\ServiceContainer;
use EasyUmeng\Kernel\Support\Collection;
use EasyUmeng\Kernel\Traits\HasHttpRequests;
use EasyUmeng\UVerify\Application;
use GuzzleHttp\Exception\GuzzleException;
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
     * @var Application|ServiceContainer
     */
    protected $app;

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
     * @param Arrayable $params
     * @return array|Collection|object|ResponseInterface|string
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function send(Arrayable $params)
    {
        return $this->httpPostJson('api/send', $params->toArray());
    }

    /**
     * @param string $content
     * @return string
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function uploadContents(string $content): string
    {
        $result = $this->httpPostJson('upload', [
            'content' => $content,
        ]);
        return $result['data']['file_id'];
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
     * @param false  $returnRaw
     * @return array|Collection|object|ResponseInterface|string
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function request(string $url, $method = 'GET', $options = [], $returnRaw = false)
    {
        if (empty($this->middlewares)) {
            $this->registerHttpMiddlewares();
        }

        $options['json'] = $options['json'] ?? [];
        $options['json'] = array_merge($options['json'], [
            'appkey' => $this->app['config']->app_key,
            'timestamp' => time(),
            'production_mode' => $this->app['config']->production_mode,
        ]);
        $base = $this->app->config->get('http.base_uri');
        $options['query'] = array_merge($options['query'], [
            'sign' => md5('POST' . "{$base}{$url}" . $this->jsonEncode($options['json'], JSON_UNESCAPED_UNICODE) . $this->app['config']->secret),
        ]);

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
        // log
        $this->pushMiddleware($this->logMiddleware(), 'log');
    }

    /**
     * Log the request.
     *
     * @return Closure
     */
    protected function logMiddleware()
    {
        $formatter = new MessageFormatter($this->app['config']['http.log_template'] ?? MessageFormatter::DEBUG);

        return Middleware::log($this->app['logger'], $formatter, LogLevel::DEBUG);
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
