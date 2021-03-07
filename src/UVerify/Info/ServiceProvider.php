<?php

namespace EasyUmeng\UVerify\Info;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        !isset($pimple['info']) && $pimple['info'] = function ($app) {
            return new Client($app);
        };
    }
}
