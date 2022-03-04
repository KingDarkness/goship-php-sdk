<?php
namespace Kingdarkness\Goship\V2;

use Kingdarkness\Goship\Request;

class Auth extends Request
{
    /**
     * đăng nhập hệ thống
     *
     * @param array $data
     * @param array $query (optional)
     * @param array $headers (optional)
     *
     * @return array goship response
     */
    public function login(array $data, array $query = [], array $headers = [])
    {
        $response = $this->request('/api/v2/login', 'POST', [
            'json' => $data,
            'query' => $query,
            'headers' => $headers,
            false
        ]);
        return $response;
    }

    public function refreshToken(array $data, array $query = [], array $headers = [])
    {
        $response = $this->request('/api/v2/refresh_token', 'POST', [
            'json' => $data,
            'query' => $query,
            'headers' => $headers,
            false
        ]);
        return $response;
    }
}
