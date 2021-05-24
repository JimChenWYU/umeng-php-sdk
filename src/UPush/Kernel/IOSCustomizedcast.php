<?php

namespace EasyUmeng\UPush\Kernel;

use EasyUmeng\UPush\Kernel\Contracts\IOSNotification;

class IOSCustomizedcast extends IOSNotification
{
    public function __construct(array $params, array $customized, string $alias, string $aliasType)
    {
        parent::__construct();
        // Set your alias here, and use comma to split them if there are multiple alias.
        // And if you have many alias, you can also upload a file containing these alias, then
        // use file_id to send customized notification.
        $this->setPredefinedKeyValue('alias', $alias);
        // Set your alias_type here
        $this->setPredefinedKeyValue('alias_type', $aliasType);
        foreach ($params as $key => $val) {
            $this->setPredefinedKeyValue($key, $val);
        }
        foreach ($customized as $key => $val) {
            $this->setCustomizedField($key, $val);
        }
    }

    public function getNotificationType()
    {
        return 'customizedcast';
    }

    public function toArray()
    {
        return $this->data;
    }
}
