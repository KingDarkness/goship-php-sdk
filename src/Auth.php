<?php
namespace Kingdarkness\Goship;

class Auth
{
    public $apiUrl = 'https://api.goship.io';
    public $clientId;
    public $clientSecret;
    public $token;

    public function __construct($clientId, $clientSecret, $token = null)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }
}
