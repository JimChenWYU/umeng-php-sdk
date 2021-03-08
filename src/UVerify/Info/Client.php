<?php

namespace EasyUmeng\UVerify\Info;

use EasyUmeng\UVerify\Kernel\BaseClient;

class Client extends BaseClient
{
    public function get(string $token, string $verifyId = '')
    {
        $query = [
            'appkey' => $this->app->getUmengKey(),
            'verifyId' => $verifyId,
        ];
        $data = [
            'token' => $token,
        ];
        return $this->httpPostJson('api/v1/mobile/info', $data, $query);
    }
}
