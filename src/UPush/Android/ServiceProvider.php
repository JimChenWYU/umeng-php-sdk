<?php

namespace EasyUmeng\UPush\Android;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        !isset($pimple['android']) && $pimple['android'] = function ($app) {
            return new Client($app);
        };
    }
}
