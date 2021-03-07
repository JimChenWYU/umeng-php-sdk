<?php

namespace EasyUmeng\Tests\Kernel;

use EasyUmeng\Kernel\Config;
use EasyUmeng\Kernel\ServiceContainer;
use EasyUmeng\Tests\TestCase;
use GuzzleHttp\Client;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceContainerTest extends TestCase
{
    public function testBasicFeatures()
    {
        $container = new ServiceContainer();
        self::assertNotEmpty($container->getProviders());

        // __set, __get, offsetGet
        self::assertInstanceOf(Config::class, $container['config']);
        self::assertInstanceOf(Config::class, $container->config);

        self::assertInstanceOf(Client::class, $container['http_client']);

        $container['foo'] = 'foo';
        $container->bar = 'bar';

        self::assertSame('foo', $container['foo']);
        self::assertSame('bar', $container['bar']);
    }

    public function testGetId()
    {
        self::assertSame((new ServiceContainer(['appkey' => 'appkey1']))->getId(), (new ServiceContainer(['appkey' => 'appkey1']))->getId());
        self::assertNotSame((new ServiceContainer(['appkey' => 'appkey1']))->getId(), (new ServiceContainer(['appkey' => 'appkey2']))->getId());
    }

    public function testRegisterProviders()
    {
        $container = new DummyContainerForProviderTest();

        self::assertSame('foo', $container['foo']);
    }
}


class DummyContainerForProviderTest extends \EasyUmeng\Kernel\ServiceContainer
{
    protected $providers = [
        FooServiceProvider::class,
    ];
}

class FooServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['foo'] = function () {
            return 'foo';
        };
    }
}
