<?php

namespace EasyUmeng\Tests\UVerify\Info;

use EasyUmeng\Tests\TestCase;
use EasyUmeng\UVerify\Application;
use EasyUmeng\UVerify\Info\Client;

class ClientTest extends TestCase
{
    private function makeApp($config = [])
    {
        return new Application(array_merge([
            'app_key' => 'corpid@123',
            'ali_secret' => 'corpid@123',
            'ali_key' => 'corpid@123',
            'aes_key' => 'corpid@123',
        ], $config));
    }

    public function testGet()
    {
        $client = $this->mockApiClient(Client::class, ['httpPostJson'], $this->makeApp());

        $client->expects()->httpPostJson('api/v1/mobile/info', [
            'token' => 'token-12345678',
        ], [
            'appkey' => 'corpid@123',
            'verifyId' => '12345678',
        ])->andReturn('mock-result');

        self::assertSame('mock-result', $client->get('token-12345678', '12345678'));
    }
}
