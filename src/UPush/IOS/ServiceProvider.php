<?php

namespace EasyUmeng\UPush\IOS;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        !isset($pimple['ios']) && $pimple['ios'] = function ($app) {
            return new Client($app);
        };
    }
}
