<?php

namespace EasyUmeng\Tests\UVerify\Verify;

use EasyUmeng\Tests\TestCase;
use EasyUmeng\UVerify\Application;
use EasyUmeng\UVerify\Verify\Client;

class ClientTest extends TestCase
{
    private function makeApp($config = [])
    {
        return new Application(array_merge([
            'appkey' => 'corpid@123',
            'secret' => 'corpid@123',
            'alikey' => 'corpid@123',
            'aes_prikey' => 'corpid@123',
        ], $config));
    }

    public function testHandle()
    {
        $client = $this->mockApiClient(Client::class, ['httpPostJson'], $this->makeApp());
        $client->expects()->httpPostJson('api/v1/mobile/verify', [
            'phoneNumber' => '212121212',
            'token' => 'token-12345678',
        ], [
            'appkey' => 'corpid@123',
            'verifyId' => '12345678',
        ])->andReturn('mock-result');

        self::assertSame('mock-result', $client->handle('212121212', 'token-12345678', '12345678'));
    }
}
