<?php

namespace EasyUmeng\UPush\Kernel;

use EasyUmeng\Kernel\Contracts\Arrayable;
use EasyUmeng\UPush\Kernel\Contracts\IOSNotification;

class IOSGroupcast extends IOSNotification implements Arrayable
{
    public function __construct(array $params, array $customized, array $tags)
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
        return 'groupcast';
    }
}
