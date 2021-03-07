<?php

namespace EasyUmeng\UVerify\Verify;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        !isset($pimple['verify']) && $pimple['verify'] = function ($app) {
            return new Client($app);
        };
    }
}
