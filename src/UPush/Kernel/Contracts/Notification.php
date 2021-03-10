<?php

namespace EasyUmeng\UPush\Kernel\Contracts;

abstract class Notification
{
    /*
     * $data is designed to construct the json string for POST request. Note:
     * 1)The key/value pairs in comments are optional.
     * 2)The value for key 'payload' is set in the subclass(AndroidNotification or IOSNotification), as their payload structures are different.
     */
    protected $data = [
        'appkey' => null,
        'timestamp' => null,
        'type' => null,
        //"device_tokens"  => "xx",
        //"alias"          => "xx",
        //"file_id"        => "xx",
        //"filter"         => "xx",
        //"policy"         => array("start_time" => "xx", "expire_time" => "xx", "max_send_num" => "xx"),
        'production_mode' => 'true',
        //"feedback"       => "xx",
        //"description"    => "xx",
        //"thirdparty_id"  => "xx"
    ];

    protected $DATA_KEYS = [
        'appkey', 'timestamp', 'type', 'device_tokens', 'alias', 'alias_type', 'file_id', 'filter', 'production_mode',
        'feedback', 'description', 'thirdparty_id',

        'mipush', 'mi_activity',
    ];

    protected $POLICY_KEYS = ['start_time', 'expire_time', 'max_send_num', 'out_biz_no'];

    public function __construct()
    {
        $this->data['type'] = $this->getNotificationType();
    }

    abstract public function getNotificationType();
}
