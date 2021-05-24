<?php

namespace EasyUmeng\UPush\Kernel;

use EasyUmeng\UPush\Kernel\Contracts\AndroidNotification;

class AndroidFilecast extends AndroidNotification
{
    public function __construct(array $params, array $extras, string $fileId)
    {
        parent::__construct();

        $this->setPredefinedKeyValue('file_id', $fileId);
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
        return 'filecast';
    }
}
