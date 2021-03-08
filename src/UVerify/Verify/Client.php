<?php

namespace EasyUmeng\UVerify\Verify;

use EasyUmeng\UVerify\Kernel\BaseClient;

class Client extends BaseClient
{
    public function handle(string $phoneNumber, string $token, string $verifyId = '')
    {
        $query = [
            'appkey' => $this->app['config']->app_key,
            'verifyId' => $verifyId,
        ];
        $data = [
            'token' => $token,
            'phoneNumber' => $phoneNumber,
        ];

        return $this->httpPostJson('api/v1/mobile/verify', $data, $query);
    }
}
