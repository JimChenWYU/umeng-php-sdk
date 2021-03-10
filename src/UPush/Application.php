<?php

namespace EasyUmeng\UPush;

use EasyUmeng\Kernel\ServiceContainer;

/**
 * Class Application
 *
 * @property-read \EasyUmeng\UPush\Android\Client $android
 * @property-read \EasyUmeng\UPush\IOS\Client     $ios
 */
class Application extends ServiceContainer
{
    protected $providers = [
        Android\ServiceProvider::class,
        IOS\ServiceProvider::class,
    ];

    protected $defaultConfig = [
        'http' => [
            'base_uri' => 'https://msgapi.umeng.com/',
        ],
    ];
}
