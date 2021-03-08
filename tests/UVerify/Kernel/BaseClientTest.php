<?php

namespace EasyUmeng\Tests\UVerify\Kernel;

use EasyUmeng\Kernel\Http\Response;
use EasyUmeng\Kernel\ServiceContainer;
use EasyUmeng\Tests\TestCase;
use EasyUmeng\UVerify\Application;
use EasyUmeng\UVerify\Kernel\BaseClient;
use Mockery;
use function sprintf;

class BaseClientTest extends TestCase
{
    private function makeApp($config = [])
    {
        return new Application(array_merge([
            'appkey' => 'corpid@123',
            'alisecret' => 'corpid@123',
            'alikey' => 'corpid@123',
            'aes_prikey' => 'corpid@123',
        ], $config));
    }

    public function makeClient($methods = [], ServiceContainer $app = null)
    {
        $methods = !empty($methods) ? sprintf('[%s]', implode(',', (array) $methods)) : '';
        $app = $app ?? Mockery::mock(ServiceContainer::class);

        return Mockery::mock(BaseClient::class."{$methods}", [$app])->makePartial();
    }

    public function testHttpPost()
    {
        $client = $this->makeClient('request');
        $url = 'http://easyumeng.org';

        $data = ['foo' => 'bar'];
        $client->expects()->request($url, 'POST', ['form_params' => $data])->andReturn('mock-result');
        self::assertSame('mock-result', $client->httpPost($url, $data));
    }

    public function testHttpPostJson()
    {
        $client = $this->makeClient('request');
        $url = 'http://easyumeng.org';

        $data = ['foo' => 'bar'];
        $query = ['appid' => 1234];
        $client->expects()->request($url, 'POST', ['query' => $query, 'json' => $data])->andReturn('mock-result');
        self::assertSame('mock-result', $client->httpPostJson($url, $data, $query));
    }

    public function testRequest()
    {
        $app = $this->makeApp();
        $url = 'http://easyumeng.org/api/post';
        $options = [
            'foo' => 'bar',
            'json' => ['foo' => 'bar'],
            'query' => ['foo' => 'bar'],
        ];

        $method = 'POST';
        $client = $this->makeClient(['registerHttpMiddlewares', 'performRequest', 'castResponseToType'], $app)
            ->shouldAllowMockingProtectedMethods();
        $mockResponse = new Response(200, [], 'response-content');
        // default value
        $client->expects()->performRequest($url, $method, Mockery::on(function ($options) {
            self::assertSame('bar', $options['foo']);
            $jsonInOptions = $options['json'];
            $queryInOptions = $options['query'];
            self::assertIsArray($jsonInOptions);
            self::assertIsArray($queryInOptions);
            self::assertSame($jsonInOptions['foo'], $options['foo']);
            self::assertSame($queryInOptions['foo'], $options['foo']);

            return true;
        }))->times(3)->andReturn($mockResponse);
        $client->expects()->castResponseToType()
            ->with($mockResponse, Mockery::any())
            ->andReturn(['foo' => 'mock-bar']);

        // $returnResponse = false
        self::assertSame(['foo' => 'mock-bar'], $client->request($url, $method, $options, false));
        // $returnResponse = true
        self::assertInstanceOf(Response::class, $client->request($url, $method, $options, true));
        self::assertSame('response-content', $client->request($url, $method, $options, true)->getBodyContents());
    }
}
