<?php

namespace Kingdarkness\Goship;

use GuzzleHttp\Client as HttpClient;
use Kingdarkness\Goship\Exceptions\ValidateException;

abstract class Request
{
    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var Auth
     */
    protected $config;

    public function __construct(HttpClient $httpClient, Auth $config)
    {
        $this->httpClient = $httpClient;
        $this->config = $config;
    }

    public function request(string $url, string $method = 'GET', array $request = [], $withAuth = true)
    {
        $request['headers'] = array_merge(Arr::get($request, 'headers', []), $this->defaultHeaders($withAuth));
        $request['verify'] = false;
        $request['curl'] = [
            CURLOPT_SSLVERSION => CURL_SSLVERSION_MAX_TLSv1_2,
            CURLOPT_SSL_CIPHER_LIST => 'DEFAULT@SECLEVEL=1'
        ];


        try {
            $response =  json_decode($this->httpClient->request($method, $this->config->apiUrl . $url, $request)->getBody()->getContents(), true);
        } catch (\GuzzleHttp\Exception\ClientException $ex) {
            switch ($ex->getResponse()->getStatusCode()) {
                case 422:
                    $response = json_decode($ex->getResponse()->getBody()->getContents(), true);
                    throw new ValidateException($response);
                default:
                    throw $ex;
            }
        }


        return $response;
    }

    private function defaultHeaders($withAuth = true)
    {
        if ($withAuth) {
            return [
                'Authorization' => $this->config->getToken(),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'

            ];
        }

        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ];
    }
}
