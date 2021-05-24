<?php

namespace EasyUmeng\UPush\Kernel;

use EasyUmeng\UPush\Kernel\Contracts\IOSNotification;

class IOSListcast extends IOSNotification
{
    public function __construct(array $params, array $customized, array $deviceTokens)
    {
        parent::__construct();
        $this->setPredefinedKeyValue('device_tokens', implode(',', $deviceTokens));
        foreach ($params as $key => $val) {
            $this->setPredefinedKeyValue($key, $val);
        }
        foreach ($customized as $key => $val) {
            $this->setCustomizedField($key, $val);
        }
    }

    public function toArray()
    {
        return $this->data;
    }

    public function getNotificationType()
    {
        return 'listcast';
    }
}
