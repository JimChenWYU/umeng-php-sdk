<?php

namespace EasyUmeng\Tests;

use EasyUmeng\Factory;
use EasyUmeng\UVerify\Application;

class FactoryTest extends TestCase
{
    public function testStaticCall()
    {
        $uVerify = Factory::uVerify([
            'app_key' => 'corpid@123',
            'ali_key' => 'corpid@123',
            'aes_key' => 'corpid@123',
        ]);

        $uVerifyFromMake = Factory::make('uVerify', [
            'app_key' => 'corpid@123',
            'ali_key' => 'corpid@123',
            'aes_key' => 'corpid@123',
        ]);

        self::assertInstanceOf(Application::class, $uVerify);
        self::assertInstanceOf(Application::class, $uVerifyFromMake);

        $expected = [
            'app_key' => 'corpid@123',
            'ali_key' => 'corpid@123',
            'aes_key' => 'corpid@123',
        ];

        self::assertArraySubset($expected, $uVerify['config']->all());
        self::assertArraySubset($expected, $uVerifyFromMake['config']->all());
    }
}
