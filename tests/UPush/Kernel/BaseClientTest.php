<?php

namespace EasyUmeng\Tests\UPush\Kernel;

use EasyUmeng\Kernel\Contracts\Arrayable;
use EasyUmeng\Kernel\Http\Response;
use EasyUmeng\Kernel\ServiceContainer;
use EasyUmeng\Tests\TestCase;
use EasyUmeng\UPush\Application;
use EasyUmeng\UPush\Kernel\BaseClient;
use Mockery;

class BaseClientTest extends TestCase
{
    private function makeApp($config = [])
    {
        return new Application(array_merge([
            'app_key' => 'corpid@123',
            'production_mode' => false,
            'secret' => 'corpid@123',
        ], $config));
    }

    public function makeClient($methods = [], ServiceContainer $app = null)
    {
        $methods = !empty($methods) ? sprintf('[%s]', implode(',', (array) $methods)) : '';
        $app = $app ?? Mockery::mock(ServiceContainer::class);

        return Mockery::mock(BaseClient::class."{$methods}", [$app])->makePartial();
    }

    public function testHttpPostJson()
    {
        $client = $this->makeClient('request');
        $url = 'http://easyumeng.org';

        $data = ['foo' => 'bar'];
        $query = [];
        $expectedResponse = new Response(200, [], 'mock-result');
        $client->expects()->request($url, 'POST', ['query' => $query, 'json' => $data])->andReturn($expectedResponse);
        self::assertSame($expectedResponse, $client->httpPostJson($url, $data, $query));
    }

    public function testSend()
    {
        $client = $this->makeClient('httpPostJson');
        $url = 'api/send';
        $data = [
            'foo' => 'bar',
        ];
        $client->expects()->httpPostJson($url, $data)->andReturn('mock-result');
        self::assertSame('mock-result', $client->send(new DummyArrayable($data)));
    }

    public function testUploadContents()
    {
        $client = $this->makeClient('httpPostJson', $this->makeApp());
        $data = [ 'content' => 'foobar' ];
        $expectsResponse = [
            'data' => [ 'file_id' => 123 ]
        ];
        $client->expects()->httpPostJson('upload', $data)->andReturn($expectsResponse);
        self::assertSame('123', $client->uploadContents('foobar'));
    }
}

class DummyArrayable implements Arrayable
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function toArray()
    {
        return $this->data;
    }
}
