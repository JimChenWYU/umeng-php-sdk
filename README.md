<h1 align="center"> umeng-php-sdk </h1>

<p align="center"> 友盟SDK.</p>


[![Test Status](https://github.com/JimChenWYU/umeng-php-sdk/workflows/Test/badge.svg)](https://github.com/JimChenWYU/umeng-php-sdk/actions)
[![Lint Status](https://github.com/JimChenWYU/umeng-php-sdk/workflows/Lint/badge.svg)](https://github.com/JimChenWYU/umeng-php-sdk/actions)
[![Latest Stable Version](https://poser.pugx.org/jimchen/umeng-php-sdk/v/stable.svg)](https://packagist.org/packages/JimChenWYU/umeng-php-sdk)
[![License](https://poser.pugx.org/jimchen/umeng-php-sdk/license)](https://packagist.org/packages/JimChenWYU/umeng-php-sdk)

## Installing

```shell
$ composer require jimchen/umeng-php-sdk -vvv
```

## Usage

```php
use EasyUmeng\Factory;

$app = Factory::uVerify([
    'app_key' => '1234xxxxxxxx',
    'ali_key' => '1234xxxxxxxx',
    'ali_secret' => '1234xxxxxxxx',
    'aes_key' => 'Mnfgxxxxxxxxx',
    
    'response_type' => 'array', // Options: 'collection', 'array', 'object', 'raw' 
    
    /**
     * 接口请求相关配置，超时时间等，具体可用参数请参考：
     * http://docs.guzzlephp.org/en/stable/request-config.html
     *
     * - retries: 重试次数，默认 1，指定当 http 请求失败时重试的次数。
     * - retry_delay: 重试延迟间隔（单位：ms），默认 500
     * - log_template: 指定 HTTP 日志模板，请参考：https://github.com/guzzle/guzzle/blob/master/src/MessageFormatter.php
     */
    'http' => [
        'max_retries' => 1,
        'retry_delay' => 500,
        'timeout' => 5.0,
        
        // 'base_uri' => '',
    ],
    
    /**
     * 日志配置
     *
     * level: 日志级别, 可选为：
     *         debug/info/notice/warning/error/critical/alert/emergency
     * path：日志文件位置(绝对路径!!!)，要求可写权限
     * 
     * 如果不进行配置则会使用默认选项，可查看 EasyUmeng\Kernel\Providers\LogServiceProvider::formatLogConfig
     */
    'log' => [
        'default' => 'dev', // 默认使用的 channel，生产环境可以改为下面的 prod
        'channels' => [
            // 测试环境
            'dev' => [
                'driver' => 'single',
                'path' => '/tmp/easyumeng.log',
                'level' => 'debug',
            ],
            // 生产环境
            'prod' => [
                'driver' => 'daily',
                'path' => '/tmp/easyumeng.log',
                'level' => 'info',
            ],
        ],
    ],
]);

/**
 * 一键登录
 * @link https://developer.umeng.com/docs/143070/detail/144783 
 */
$result1 = $app->info->get($token, $verifyId); 
/**
 * 本机号码认证
 * @link https://developer.umeng.com/docs/143070/detail/144784
 */
$result2 = $app->verify->handle($phone, $token, $verifyId);
```

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/JimChenWYU/umeng-php-sdk/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/JimChenWYU/umeng-php-sdk/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT