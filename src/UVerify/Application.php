<?php

namespace EasyUmeng\UVerify;

use EasyUmeng\Kernel\ServiceContainer;
use EasyUmeng\Kernel\Support\AES;
use EasyUmeng\UVerify\Info\Client;

/**
 * Class Application
 *
 * @property-read \EasyUmeng\UVerify\Verify\Client $verify
 * @property-read Client   $info
 */
class Application extends ServiceContainer
{
    protected $providers = [
        Info\ServiceProvider::class,
        Verify\ServiceProvider::class,
    ];

    /**
     * @var array
     */
    protected $defaultConfig = [
        'http' => [
            'base_uri' => 'https://verify5.market.alicloudapi.com/',
        ],
    ];

    /**
     * @param string $encryptPhone
     * @param string $aesEncryptKey
     * @return string
     */
    public function decryptPhone(string $encryptPhone, string $aesEncryptKey)
    {
        $res = "-----BEGIN RSA PRIVATE KEY-----" . PHP_EOL .
            wordwrap(str_replace(["\r", "\n", PHP_EOL], '', $this->getAESKey()), 64, PHP_EOL, true) .
            PHP_EOL . '-----END RSA PRIVATE KEY-----';
        $aesDecryptKey = '';

        openssl_private_decrypt(base64_decode($aesEncryptKey), $aesDecryptKey, $res);
        return AES::decrypt(base64_decode($encryptPhone), $aesDecryptKey, '');
    }

    public function getUmengKey()
    {
        return $this['config']->app_key;
    }

    public function getAliKey()
    {
        return $this['config']->ali_key;
    }

    public function getAliSecret()
    {
        return $this['config']->ali_secret;
    }

    public function getAESKey()
    {
        return $this['config']->aes_key;
    }
}
