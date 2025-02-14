<?php
namespace Kingdarkness\Goship\V2;

use Kingdarkness\Goship\Request;

class Shipment extends Request
{
    // Trạng thái vận đơn
    const STATUS_NEW                  = 900;
    const STATUS_PICKUP               = 901;
    const STATUS_PICKINGUP            = 902;
    const STATUS_PICKEDUP             = 903;
    const STATUS_DELIVERING           = 904;
    const STATUS_DELIVERED            = 905;
    const STATUS_FAILED               = 906;
    const STATUS_RETURNING            = 907;
    const STATUS_RETURNED             = 908;
    const STATUS_CROSSCHECKED_CARRIER = 909;
    const STATUS_CROSSCHECKED_SHOP    = 910;
    const STATUS_COD_SHOP             = 911;
    const STATUS_COD_CARRIER          = 912;
    const STATUS_DONE                 = 913;
    const STATUS_CANCEL               = 914;
    const STATUS_DELAY                = 915;
    const STATUS_DELIVERED_PART       = 916;
    const STATUS_LOST                 = 917;
    const STATUS_UNKNOW               = 1000;

    public const CUSTOMER_PAY = 0;
    public const SHOP_PAY = 1;


    public static function getStatusText($statusCode = null)
    {
        $listStatusText = [
            self::STATUS_NEW                  => 'Đơn mới',
            self::STATUS_PICKUP               => 'Chờ lấy hàng',
            self::STATUS_PICKINGUP            => 'Lấy hàng',
            self::STATUS_PICKEDUP             => 'Đã lấy hàng',
            self::STATUS_DELIVERING           => 'Giao hàng',
            self::STATUS_DELIVERED            => 'Giao thành công',
            self::STATUS_FAILED               => 'Giao thất bại',
            self::STATUS_RETURNING            => 'Đang chuyển hoàn',
            self::STATUS_RETURNED             => 'Chuyển hoàn',
            self::STATUS_CROSSCHECKED_CARRIER => 'Đã đối soát', //dang xu ly
            self::STATUS_CROSSCHECKED_SHOP    => 'Đã đối soát khách',
            self::STATUS_COD_SHOP             => 'Đã trả COD cho khách',
            self::STATUS_COD_CARRIER          => 'Chờ thanh toán COD', //sap tra
            self::STATUS_DONE                 => 'Hoàn thành', //da tra
            self::STATUS_CANCEL               => 'Đơn hủy',
            self::STATUS_DELAY                => 'Chậm lấy/giao',
            self::STATUS_DELIVERED_PART       => 'Giao hàng một phần',
            self::STATUS_LOST                 => 'Thất lạc hàng',
            self::STATUS_UNKNOW               => 'Đơn lỗi',
        ];

        if (is_null($statusCode)) {
            return $listStatusText;
        }

        return isset($listStatusText[$statusCode])
            ? $listStatusText[$statusCode]
            : 'Không xác định';
    }

    public static function getHasStatusTextGobox($statusCode = null)
    {
        $listStatusText = [
            self::STATUS_NEW                  => 'Đơn mới',
            self::STATUS_PICKUP               => 'Chờ lấy hàng',
            self::STATUS_PICKINGUP            => 'Lấy hàng',
            self::STATUS_PICKEDUP             => 'Đã lấy hàng',
            self::STATUS_DELIVERING           => 'Giao hàng',
            self::STATUS_DELIVERED            => 'Giao thành công',
            self::STATUS_FAILED               => 'Giao thất bại',
            self::STATUS_RETURNING            => 'Đang chuyển hoàn',
            self::STATUS_RETURNED             => 'Chuyển hoàn',
            self::STATUS_DONE                 => 'Hoàn thành', //da tra
            self::STATUS_CANCEL               => 'Đơn hủy',
            self::STATUS_DELAY                => 'Chậm lấy/giao',
            self::STATUS_DELIVERED_PART       => 'Giao hàng một phần',
            self::STATUS_LOST                 => 'Thất lạc hàng',
            self::STATUS_UNKNOW               => 'Đơn lỗi',
        ];

        if (is_null($statusCode)) {
            return $listStatusText;
        }

        return isset($listStatusText[$statusCode])
            ? $listStatusText[$statusCode]
            : 'Không xác định';
    }

    /**
     * lấy thông tin bảng giá
     *
     * @param array $data
     * @param array $query (optional)
     * @param array $headers (optional)
     *
     * @return array goship response
     */
    public function getRates(array $data, array $query = [], array $headers = [])
    {
        $response = $this->request('/api/v2/rates', 'POST', [
            'json' => $data,
            'query' => $query,
            'headers' => $headers,
        ]);
        return $response;
    }

    /**
     * tạo mới vận đơn
     *
     * @param array $data
     * @param array $query (optional)
     * @param array $headers (optional)
     *
     * @return array goship response
     */
    public function create(array $data, array $query = [], array $headers = [])
    {
        $response = $this->request('/api/v2/shipments', 'POST', [
            'json' => $data,
            'query' => $query,
            'headers' => $headers,
        ]);
        return $response;
    }

    /**
     * lấy thông tin list vận đơn
     *
     * @param array $query (optional)
     * @param array $headers (optional)
     *
     * @return array goship response
     */
    public function getByQuery(array $query = [], array $headers = [])
    {
        try {
            $response = $this->request('/api/v2/shipments', 'GET', [
                'query' => $query,
                'headers' => $headers,
            ]);
            return $response;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * lấy thông tin chi tiết vận đơn
     *
     * @param mixed $code
     * @param array $query (optional)
     * @param array $headers (optional)
     *
     * @return array goship response
     */
    public function getDetail($code, array $query = [], array $headers = [])
    {
        $response = $this->request('/api/v2/shipments/track/' . $code, 'GET', [
            'query' => $query,
            'headers' => $headers,
        ]);
        return $response;
    }

    /**
     * xoá/huỷ vận đơn
     *
     * @param mixed $code
     * @param array $query (optional)
     * @param array $headers (optional)
     *
     * @return array goship response
     */
    public function delete($code, array $query = [], array $headers = [])
    {
        $response = $this->request('/api/v2/shipments/' . $code, 'DELETE', [
            'query' => $query,
            'headers' => $headers,
        ]);
        return $response;
    }

    public function printUrl($code)
    {
        return $this->config->apiUrl . '/api/v2/shipments/print/' . $code;
    }
}
