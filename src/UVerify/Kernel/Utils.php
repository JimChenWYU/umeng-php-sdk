<?php

namespace EasyUmeng\UVerify\Kernel;

class Utils
{
    public static function generateSign(string $httpMethod, string $path, array $query, array $form, array $headers, string $secret)
    {
        $sortParams = array_merge($query, $form);
        ksort($sortParams);
        ksort($headers);

        $sb = $httpMethod . "\n";
        $sb .= $headers['Accept'] . "\n\n";
        $sb .= $headers['Content-Type'] . "\n\n";
        unset(
            $headers['Content-Type'],
            $headers['Accept'],
            $headers['Content-MD5'],
            $headers['Date'],
            $headers['X-Ca-Signature-Headers'],
            $headers['X-Ca-Signature']
        );
        foreach ($headers as $itemKey => $itemValue) {
            $sb .= "{$itemKey}:{$itemValue}\n";
        }
        $sb .= "{$path}?" . http_build_query($sortParams);

        return base64_encode(hash_hmac('sha256', $sb, $secret, true));
    }
}
