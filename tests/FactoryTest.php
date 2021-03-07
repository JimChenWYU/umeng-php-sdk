<?php

namespace EasyUmeng\Tests;

use EasyUmeng\Factory;

class FactoryTest extends TestCase
{
    public function testStaticCall()
    {
        $uVerify = Factory::uVerify([
            'appkey' => 'corpid@123',
            'secret' => 'corpid@123',
            'alikey' => 'corpid@123',
            'aes_prikey' => 'corpid@123',
        ]);

        $uVerifyFromMake = Factory::make('uVerify', [
            'appkey' => 'corpid@123',
            'secret' => 'corpid@123',
            'alikey' => 'corpid@123',
            'aes_prikey' => 'corpid@123',
        ]);

        self::assertInstanceOf(\EasyUmeng\UVerify\Application::class, $uVerify);
        self::assertInstanceOf(\EasyUmeng\UVerify\Application::class, $uVerifyFromMake);

        $expected = [
            'appkey' => 'corpid@123',
            'secret' => 'corpid@123',
            'alikey' => 'corpid@123',
            'aes_prikey' => 'corpid@123',
        ];

        self::assertArraySubset($expected, $uVerify['config']->all());
        self::assertArraySubset($expected, $uVerifyFromMake['config']->all());
    }
}
