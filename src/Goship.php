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

    public function __construct($clientId, $clientSecret, $accessToken = null, $username = null, $password = null, $apiUrl = 'https://api.goship.io')
    {
        $this->config = new Auth($clientId, $clientSecret, $accessToken, $username, $password, $apiUrl);
    }

    public function getCities($query = [], $headers)
    {
        $requester = new Location(new HttpClient(), $this->config);
        return $requester->getCities($query, $headers);
    }

    public function getDistrict($cityCode, $query = [], $headers)
    {
        $requester = new Location(new HttpClient(), $this->config);
        return $requester->getDistricts($cityCode, $query, $headers);
    }

    public function getWards($districtCode, $query = [], $headers)
    {
        $requester = new Location(new HttpClient(), $this->config);
        return $requester->getWards($districtCode, $query, $headers);
    }

    public function getShipments($query = [], $headers)
    {
        $requester = new Shipment(new HttpClient(), $this->config);
        return $requester->getByQuery($query, $headers);
    }

    public function getShipment($code, $query = [], $headers)
    {
        $requester = new Shipment(new HttpClient(), $this->config);
        return $requester->getDetail($code, $query, $headers);
    }

    public function getPrintUrl($code, $query = [], $headers)
    {
        $requester = new Shipment(new HttpClient(), $this->config);
        return $requester->printUrl($code, $query, $headers);
    }

    public function cancelShipment($code, $query = [], $headers)
    {
        $requester = new Shipment(new HttpClient(), $this->config);
        return $requester->delete($code, $query, $headers);
    }

    public function createShipment($data, $query = [], $headers)
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

    public function getRates($data, $query = [], $headers)
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
        return $requester->getRates($sendData, $query, $headers);
    }

    public function getTransaction($query = [], $headers = [])
    {
        $requester = new Transaction(new HttpClient(), $this->config);
        return $requester->getByQuery($query, $headers);
    }

    public function getInvoices($query = [], $headers = [])
    {
        $requester = new Invoice(new HttpClient(), $this->config);
        return $requester->getByQuery($query, $headers);
    }

    public function getShipmentInInvoice($invoiceCode, $query = [], $headers = [])
    {
        $requester = new Invoice(new HttpClient(), $this->config);
        return $requester->getShipments($invoiceCode, $query, $headers);
    }

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
