<?php

namespace EasyUmeng\UPush\IOS;

use EasyUmeng\UPush\Kernel\BaseClient;
use EasyUmeng\UPush\Kernel\IOSBroadcast;
use EasyUmeng\UPush\Kernel\IOSCustomizedcast;
use EasyUmeng\UPush\Kernel\IOSFilecast;
use EasyUmeng\UPush\Kernel\IOSGroupcast;
use EasyUmeng\UPush\Kernel\IOSUnicast;

class Client extends BaseClient
{
    public function sendBroadcast(array $params, array $customized)
    {
        return $this->send(new IOSBroadcast($params, $customized));
    }

    public function sendUnicast(array $params, array $customized, string $deviceTokens)
    {
        return $this->send(new IOSUnicast($params, $customized, $deviceTokens));
    }

    public function sendFilecast(array $params, array $customized, string $content)
    {
        return $this->send(new IOSFilecast($params, $customized, $this->uploadContents($content)));
    }

    public function sendGroupcast(array $params, array $customized, array $tags)
    {
        return $this->send(new IOSGroupcast($params, $customized, $tags));
    }

    public function sendCustomizedcast(array $params, array $customized, string $alias, string $aliasType)
    {
        return $this->send(new IOSCustomizedcast($params, $customized, $alias, $aliasType));
    }

    public function sendCustomizedcastFileId(array $params, array $customized, string $content)
    {
        $customizedcast = new IOSCustomizedcast($params, $customized, '', '');
        $customizedcast->setPredefinedKeyValue('file_id', $this->uploadContents($content));
        return $this->send($customizedcast);
    }
}
