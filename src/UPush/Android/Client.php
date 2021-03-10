<?php

namespace EasyUmeng\UPush\Android;

use EasyUmeng\UPush\Kernel\AndroidBroadcast;
use EasyUmeng\UPush\Kernel\AndroidCustomizedcast;
use EasyUmeng\UPush\Kernel\AndroidFilecast;
use EasyUmeng\UPush\Kernel\AndroidGroupcast;
use EasyUmeng\UPush\Kernel\AndroidUnicast;
use EasyUmeng\UPush\Kernel\BaseClient;

class Client extends BaseClient
{
    public function sendBroadcast(array $params, array $extras)
    {
        return $this->send(new AndroidBroadcast($params, $extras));
    }

    public function sendUnicast(array $params, array $extras, string $deviceTokens)
    {
        return $this->send(new AndroidUnicast($params, $extras, $deviceTokens));
    }

    public function sendFilecast(array $params, array $extras, string $content)
    {
        return $this->send(new AndroidFilecast($params, $extras, $this->uploadContents($content)));
    }

    public function sendGroupcast(array $params, array $extras, array $tags)
    {
        return $this->send(new AndroidGroupcast($params, $extras, $tags));
    }

    public function sendCustomizedcast(array $params, array $extras, string $alias, string $aliasType)
    {
        return $this->send(new AndroidCustomizedcast($params, $extras, $alias, $aliasType));
    }

    public function sendCustomizedcastFileId(array $params, array $extras, string $content)
    {
        $customizedcast = new AndroidCustomizedcast($params, $extras, '', '');
        $customizedcast->setPredefinedKeyValue('file_id', $this->uploadContents($content));
        return $this->send($customizedcast);
    }
}
