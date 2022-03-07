<?php
namespace Kingdarkness\Goship;

use GuzzleHttp\Client as HttpClient;
use Kingdarkness\Goship\V2\Invoice;
use Kingdarkness\Goship\V2\Location;
use Kingdarkness\Goship\V2\Shipment;
use Kingdarkness\Goship\V2\Transaction;

class Goship
{
    /**
    * @var Auth
    */
    public $config;

    /**
     * Goship class
     *
     * @param string $clientId [client id goship]
     * @param string $clientSecret [client secret goship]
     * @param string $accessToken (optional) [accessToken goship]
     * @param string $username (optional) [username goship]
     * @param $string $password (optional) [password goship]
     * @param $string $apiUrl (optional) [goship api url]
     */
    public function __construct($clientId, $clientSecret, $accessToken = null, $username = null, $password = null, $apiUrl = 'https://api.goship.io')
    {
        $this->config = new Auth($clientId, $clientSecret, $accessToken, $username, $password, $apiUrl);
    }

    /**
     * Lấy danh sách tỉnh thành
     *
     * @param array $query (optional) [query string]
     * @param array $headers (optional) [custom headers]
     *
     * @return array
     */
    public function getCities($query = [], $headers = [])
    {
        $requester = new Location(new HttpClient(), $this->config);
        $response = $requester->getCities($query, $headers);
        return Arr::get($response, 'data', []);
    }

    /**
     * Lấy danh sách quận huyện thuộc tỉnh thành
     *
     * @param mixed $cityCode [mã tỉnh thành]
     * @param array $query (optional) [query string]
     * @param array $headers (optional) [custom headers]
     *
     * @return array
     */
    public function getDistricts($cityCode, $query = [], $headers = [])
    {
        $requester = new Location(new HttpClient(), $this->config);
        $response = $requester->getDistricts($cityCode, $query, $headers);
        return Arr::get($response, 'data', []);
    }

    /**
     * Lấy danh sách phường xã thuộc quận huyện
     *
     * @param mixed $districtCode [mã quận huyện]
     * @param array $query (optional) [query string]
     * @param array $headers (optional) [custom headers]
     *
     * @return array
     */
    public function getWards($districtCode, $query = [], $headers = [])
    {
        $requester = new Location(new HttpClient(), $this->config);
        $response = $requester->getWards($districtCode, $query, $headers);
        return Arr::get($response, 'data', []);
    }

    /**
     * Lấy danh sách vận đơn
     *
     * @param array $query (optional) [query string]
     * @param array $headers (optional) [custom headers]
     *
     * @return array
     */
    public function getShipments($query = [], $headers = [])
    {
        $requester = new Shipment(new HttpClient(), $this->config);
        $response = $requester->getByQuery($query, $headers);
        return Arr::get($response, 'data', []);
    }

    /**
     * Lấy chi tiết đơn hàng
     *
     * @param mixed $code [mã vận đơn]
     * @param array $query (optional) [query string]
     * @param array $headers (optional) [custom headers]
     *
     * @return array
     */
    public function getShipment($code, $query = [], $headers = [])
    {
        $requester = new Shipment(new HttpClient(), $this->config);
        $response = $requester->getDetail($code, $query, $headers);
        $shipments = Arr::get($response, 'data', []);
        if (count($shipments)) {
            return $shipments[0];
        }
        return null;
    }

    /**
     * Lấy link in vận đơn
     *
     * @param mixed $code [mã vận đơn]
     * @param array $query (optional) [query string]
     * @param array $headers (optional) [custom headers]
     *
     * @return string
     */
    public function getPrintUrl($code, $query = [], $headers = [])
    {
        $requester = new Shipment(new HttpClient(), $this->config);
        return $requester->printUrl($code, $query, $headers);
    }

    /**
     * Hủy đơn hàng
     *
     * @param mixed $code [mã vận đơn]
     * @param array $query (optional) [query string]
     * @param array $headers (optional) [custom headers]
     *
     * @return array
     */
    public function cancelShipment($code, $query = [], $headers = [])
    {
        $requester = new Shipment(new HttpClient(), $this->config);
        $response = $requester->delete($code, $query, $headers);
        return Arr::get($response, 'data', []);
    }

    /**
     * tạo đơn hàng
     *
     * @param array $data [thông tin đơn hàng]
     * @param array $query (optional) [query string]
     * @param array $headers (optional) [custom headers]
     *
     * @return array
     */
    public function createShipment($data, $query = [], $headers = [])
    {
        $sendData = [
          'shipment' => [
            'rate' => $data['rate'],
            'payer' => Arr::get($data, 'payer', Shipment::CUSTOMER_PAY),
            'order_id' => Arr::get($data, 'order_id'),
            'metadata' => Arr::get($data, 'metadata'),
            'node_code' => Arr::get($data, 'node_code', 'KHONGCHOXEMHANG'),
            'address_from' => [
              'name' => $data['sender_name'],
              'phone' => $data['sender_phone'],
              'street' => $data['from_street'],
              'city' => $data['from_city'],
              'district' => $data['from_district'],
              'ward' => $data['from_ward'],
            ],
            'address_to' => [
              'name' => $data['receiver_name'],
              'phone' => $data['receiver_phone'],
              'street' => $data['to_street'],
              'city' => $data['to_city'],
              'district' => $data['to_district'],
              'ward' => $data['to_ward'],
            ],
            'parcel' => [
              'name' => Arr::get($data, 'package_name', 'kiện hàng'),
              'cod' => Arr::get($data, 'cod', 0),
              'amount' => Arr::get($data, 'amount', 0),
              'weight' => Arr::get($data, 'weight', 500),
              'metadata' => Arr::get($data, 'metadata'),
              'length' => Arr::get($data, 'length', 0),
              'width' => Arr::get($data, 'width', 0),
              'height' => Arr::get($data, 'height', 0),
            ],
          ],
        ];

        $requester = new Shipment(new HttpClient(), $this->config);
        return $requester->create($sendData, $query, $headers);
    }

    /**
     * lấy danh sách bảng giá
     *
     * @param array $data [thông tin đơn hàng]
     * @param array $query (optional) [query string]
     * @param array $headers (optional) [custom headers]
     *
     * @return array
     */
    public function getRates($data, $query = [], $headers = [])
    {
        $sendData = [
          'shipment' => [
            'address_from' => [
              'city' => $data['from_city'],
              'district' => $data['from_district'],
            ],
            'address_to' => [
              'city' => $data['to_city'],
              'district' => $data['to_district'],
            ],
            'parcel' => [
              'cod' => Arr::get($data, 'cod', 0),
              'amount' => Arr::get($data, 'amount', 0),
              'weight' => Arr::get($data, 'weight', 500),
              'length' => Arr::get($data, 'length', 0),
              'width' => Arr::get($data, 'width', 0),
              'height' => Arr::get($data, 'height', 0),
            ],
          ],
        ];

        $requester = new Shipment(new HttpClient(), $this->config);
        $response = $requester->getRates($sendData, $query, $headers);
        return Arr::get($response, 'data', []);
    }

    /**
     * Lấy danh sách lịch sử giao dịch
     *
     * @param array $query (optional) [query string]
     * @param array $headers (optional) [custom headers]
     *
     * @return array
     */
    public function getTransaction($query = [], $headers = [])
    {
        $requester = new Transaction(new HttpClient(), $this->config);
        $response = $requester->getByQuery($query, $headers);
        return Arr::get($response, 'data', []);
    }

    /**
     * Lấy danh sách đối soát
     *
     * @param array $query (optional) [query string]
     * @param array $headers (optional) [custom headers]
     *
     * @return array
     */
    public function getInvoices($query = [], $headers = [])
    {
        $requester = new Invoice(new HttpClient(), $this->config);
        $response = $requester->getByQuery($query, $headers);
        return Arr::get($response, 'data', []);
    }

    /**
     * Lấy danh sách đơn hàng thuộc đối soát
     *
     * @param mixed $invoiceCode [mã đối soát]
     * @param array $query (optional) [query string]
     * @param array $headers (optional) [custom headers]
     *
     * @return array
     */
    public function getShipmentInInvoice($invoiceCode, $query = [], $headers = [])
    {
        $requester = new Invoice(new HttpClient(), $this->config);
        $response = $requester->getShipments($invoiceCode, $query, $headers);
        return Arr::get($response, 'data', []);
    }

    /**
     * verify Webhook
     *
     * @throws KingDarkness\Goship\Exceptions\UnverifiException
     *
     * @return array [data Webhook]
     */
    public function verifyWebhook()
    {
        $webhook_hmac = $_SERVER['X-Goship-Hmac-SHA256'];
        $data = json_decode(file_get_contents('php://input'), true);
        $verified = Webhook::verify($data, $webhook_hmac, $this->config->clientSecret);

        if ($verified) {
            return $data;
        }

        throw new \KingDarkness\Goship\Exceptions\UnverifiException();
    }
}
