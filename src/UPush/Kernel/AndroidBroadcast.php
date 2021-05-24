<?php

namespace EasyUmeng\UPush\Kernel;

use EasyUmeng\UPush\Kernel\Contracts\AndroidNotification;

class AndroidBroadcast extends AndroidNotification
{
    public function __construct(array $params, array $extras)
    {
        parent::__construct();
        foreach ($params as $key => $value) {
            $this->setPredefinedKeyValue($key, $value);
        }
        unset($key, $value);
        foreach ($extras as $key => $value) {
            $this->setExtraField($key, $value);
        }
        unset($key, $value);
    }

    public function toArray()
    {
        return $this->data;
    }

    public function getNotificationType()
    {
        return 'broadcast';
    }
}
