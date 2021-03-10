<?php

namespace EasyUmeng\UPush\Kernel;

use EasyUmeng\Kernel\Contracts\Arrayable;
use EasyUmeng\UPush\Kernel\Contracts\AndroidNotification;

class AndroidListcast extends AndroidNotification implements Arrayable
{
    public function __construct(array $params, array $extras, array $deviceTokens)
    {
        parent::__construct();

        $this->setPredefinedKeyValue('device_tokens', implode(',', $deviceTokens));
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
        return 'listcast';
    }
}
