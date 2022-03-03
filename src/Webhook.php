<?php
namespace Kingdarkness\Goship;

class Webhook
{
    public static function verify($data, $webhookHmac, $clientSecret)
    {
        $hashed = hash_hmac('sha256', json_encode($data), $clientSecret, true);
        $comparedHmac = base64_encode($hashed);
        return $webhookHmac == $comparedHmac;
    }
}
