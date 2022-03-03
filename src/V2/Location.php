<?php
namespace Kingdarkness\Goship\V2;

use Kingdarkness\Goship\Request;

class Location extends Request
{
    /**
     * lấy thông tin list tỉnh thành
     *
     * @param array $query (optional)
     * @param array $headers (optional)
     *
     * @return array goship response
     */
    public function getCities(array $query = [], array $headers = [])
    {
        $response = $this->request('/api/v2/cities', 'GET', [
            'query' => $query,
            'headers' => $headers,
        ]);
        return $response;
    }

    /**
     * lấy thông tin list phường xã
     *
     * @param mixed $cityCode
     * @param array $query (optional)
     * @param array $headers (optional)
     *
     * @return array goship response
     */
    public function getDistricts($cityCode, array $query = [], array $headers = [])
    {
        $response = $this->request('/api/v2/cities/' . $cityCode . '/districts', 'GET', [
            'query' => $query,
            'headers' => $headers,
        ]);
        return $response;
    }

    /**
     * lấy thông tin list phường xã
     *
     * @param mixed $districtCode
     * @param array $query (optional)
     * @param array $headers (optional)
     *
     * @return array goship response
     */
    public function getWards($districtCode, array $query = [], array $headers = [])
    {
        $response = $this->request('/api/v2/districts/' . $districtCode . '/wards', 'GET', [
            'query' => $query,
            'headers' => $headers,
        ]);
        return $response;
    }
}
