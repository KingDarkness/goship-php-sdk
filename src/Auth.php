<?php
namespace Kingdarkness\Goship;

use GuzzleHttp\Client as HttpClient;
use Kingdarkness\Goship\Exceptions\ValidateException;
use Kingdarkness\Goship\V2\Auth as AuthRequester;

class Auth
{
    public $apiUrl;
    public $clientId;
    public $clientSecret;
    public $username;
    public $password;
    public $token;

    public function __construct($clientId, $clientSecret, $token = null, $username = null, $password = null, $apiUrl = 'https://api.goship.io')
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->token = $token;
        $this->username = $username;
        $this->password = $password;
        $this->apiUrl = $apiUrl;
    }

    public function getToken()
    {
        if (!$this->token) {
            if (!($this->username && $this->password)) {
                throw new ValidateException(['message' => 'missing username & password']);
            } else {
                $this->token = $this->requestAuth()['access_token'];
            }
        }
        if (strpos($this->token, 'Bearer ') === false) {
            $this->token = 'Bearer ' . $this->token;
        }
        return $this->token;
    }

    public function requestAuth()
    {
        $requester = new AuthRequester(new HttpClient(), $this);
        $response = $requester->login([
            'username' => $this->username,
            'password' => $this->password,
            'client_id' => $this->clientId,
            'clientSecret' => $this->clientSecret
        ]);
        return $response;
    }
}
