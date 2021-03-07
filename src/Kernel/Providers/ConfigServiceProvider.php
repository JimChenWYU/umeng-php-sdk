<?php

namespace EasyUmeng\Kernel\Providers;

use Pimple\Container;
use EasyUmeng\Kernel\Config;
use Pimple\ServiceProviderInterface;

class ConfigServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        !isset($pimple['config']) && $pimple['config'] = function ($app) {
            return new Config($app->getConfig());
        };
    }
}
