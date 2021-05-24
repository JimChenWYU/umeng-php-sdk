<?php

namespace EasyUmeng\UPush\Kernel;

use EasyUmeng\UPush\Kernel\Contracts\AndroidNotification;

class AndroidCustomizedcast extends AndroidNotification
{
    public function __construct(array $params, array $extras, string $alias, string $aliasType)
    {
        parent::__construct();

        // Set your alias here, and use comma to split them if there are multiple alias.
        // And if you have many alias, you can also upload a file containing these alias, then
        // use file_id to send customized notification.
        $this->setPredefinedKeyValue('alias', $alias);
        // Set your alias_type here
        $this->setPredefinedKeyValue('alias_type', $aliasType);
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
        return 'customizedcast';
    }
}
