<?php
namespace Kingdarkness\Goship\V2;

use Kingdarkness\Goship\Request;

class Invoice extends Request
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

    /**
     * lấy thông tin list vận đơn thuộc đối soát
     *
     * @param mixed $invoiceCode
     * @param array $query (optional)
     * @param array $headers (optional)
     *
     * @return array goship response
     */
    public function getShipments($invoiceCode, array $query = [], array $headers = [])
    {
        $response = $this->request('/api/v2/invoices/' . $invoiceCode . '/shipments', 'GET', [
            'query' => $query,
            'headers' => $headers,
        ]);
        return $response;
    }
}
