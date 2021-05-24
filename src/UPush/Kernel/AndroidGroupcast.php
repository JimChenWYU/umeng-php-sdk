<?php

namespace EasyUmeng\UPush\Kernel;

use EasyUmeng\UPush\Kernel\Contracts\AndroidNotification;

class AndroidGroupcast extends AndroidNotification
{
    public function __construct(array $params, array $extras, array $tags)
    {
        parent::__construct();

        $_tags = [];
        foreach ($tags as $tag) {
            $_tags[] = [
                'tag' => $tag,
            ];
        }
        $filter = [
            'where' => [
                'and' => [
                    $_tags,
                ],
            ],
        ];

        $this->setPredefinedKeyValue('filter', $filter);
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
        return 'groupcast';
    }
}
