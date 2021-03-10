<?php

namespace EasyUmeng\UPush\Kernel;

use EasyUmeng\Kernel\Contracts\Arrayable;
use EasyUmeng\UPush\Kernel\Contracts\IOSNotification;

class IOSFilecast extends IOSNotification implements Arrayable
{
    public function __construct(array $params, array $customized, string $fileId)
    {
        parent::__construct();
        $this->setPredefinedKeyValue('file_id', $fileId);
        foreach ($params as $key => $value) {
            $this->setPredefinedKeyValue($key, $value);
        }
        foreach ($customized as $key => $value) {
            $this->setCustomizedField($key, $value);
        }
    }

    public function toArray()
    {
        return $this->data;
    }

    public function getNotificationType()
    {
        return 'filecast';
    }
}
