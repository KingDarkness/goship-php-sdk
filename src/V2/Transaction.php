<?php
namespace Kingdarkness\Goship\V2;

use Kingdarkness\Goship\Request;

class Transaction extends Request
{
    /**
     * lấy thông tin list đối soát
     *
     * @param array $query (optional)
     * @param array $headers (optional)
     *
     * @return array goship response
     */
    public function getByQuery(array $query = [], array $headers = [])
    {
        $response = $this->request('api/v2/invoices', 'GET', [
            'query' => $query,
            'headers' => $headers,
        ]);
        return $response;
    }
}
