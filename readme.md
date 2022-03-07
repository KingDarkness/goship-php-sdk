# Goship PHP SDK

Goship [https://goship.io](https://goship.io)
Goship document [https://doc.goship.io](https://doc.goship.io)

# Install

Composer

```bash
$ composer require kingdarkness/goship

```

# Usage

#### Create Goship instance

```php
use Kingdarkness\Goship\Goship as GoshipSdk;

# public function __construct(string $clientId, string $clientSecret, string $accessToken = NULL, string $username = NULL,  $password = NULL, string $apiUrl = 'https://api.goship.io' )

$goship = new GoshipSdk($yourClientId, $yourClientSecret, $yourAccessToken);
```

#### Lấy danh sách tỉnh thành

```php
# public function getCities([array $query = [], array $headers = [] )

$cities = $goship->getCities();

# cities
#[
#	{
#		"id": "100000",
#		"name": "Hà Nội"
#	},
#	{
#		"id": "700000",
#		"name": "Hồ Chí Minh"
#	},
#	{
#		"id": "880000",
#		"name": "An Giang"
#	},
#	{
#		"id": "790000",
#		"name": "Bà Rịa - Vũng Tàu"
#	}
#    ....
#]
```

#### Lấy danh sách quận huyện

```php
# public function getDistricts(mixed $cityCode, array $query = [], array $headers = [] )
$districts = $goship->getDistricts(100000);

```

#### Lấy danh sách phường xã

```php
# public function getWards(mixed $districtCode, array $query = [], array $headers = [] )
$wards $goship->getWards(100300);

```

#### Lấy danh sách vận đơn

```php
# public function getShipments(array $query = [], array $headers = [] )
$shipments = $goship->getShipments();

```

#### Lấy chi tiết vận đơn

```php
# public function getShipment(mixed $code, [array $query = [], array $headers = [] )
$shipment = $goship->getShipment(GS0001);

```

#### Lấy link in vận đơn

```php
# public function getPrintUrl(mixed $code, [array $query = [], array $headers = [] )
$shipment = $goship->getPrintUrl(GS0001);

```

#### Hủy vận đơn

```php
# public function cancelShipment(mixed $code, [array $query = [], array $headers = [] )
$gsResponse = $goship->cancelShipment(GS0001);

```

#### Tạo vận đơn mới

```php
# public function createShipment(array $data, array $query = [], array $headers = [] )
$data = [
    'rate'=> 'MTFfMjFfMTc2Mg==', #  (bắt buộc) mã bảng giá lấy từ api get bảng giá
	'from_city'=> 100000, # (bắt buộc) mã tỉnh thành gửi
	'from_district'=> 100300, # (bắt buộc) mã quận huyện gửi
	'from_ward'=> 20, # (bắt buộc) mã phường xã gửi
	'from_street'=> '62 định công', # (bắt buộc) địa chỉ gửi (lấy hàng)
	'sender_name'=> 'Nguyễn gửi', # (bắt buộc) tên ngưởi gửi
	'sender_phone'=> '0123321123', # (bắt buộc) điện thoại người gửi
	'to_city'=> 100000, # (bắt buộc) mã tỉnh thành nhận
	'to_district' => 100300, # (bắt buộc) mã phường xã nhận
	'to_ward'=> 20, # (bắt buộc) mã phường xã
	'to_street'=> '63 định công', # (bắt buộc) địa chỉ nhận
	'receiver_name'=> 'Nguyện nhận', # (bắt buộc) tên người nhận
	'receiver_phone'=> '0321111222', # (bắt buộc) điện thoại người nhận
	'cod'=> 0, # tiền thu hộ
	'amount'=> 0, # giá trị khai giá
	'weight'=> 500, # (bắt buộc) cân nặng (g)
	'payer'=> \Kingdarkness\Goship\V2\Shipment::CUSTOMER_PAY, # người trả phí CUSTOMER_PAY => 0 (người nhận trả), SHOP_PAY => 1 (người gửi trả)
	'package_name'=> 'test package', # tên gói hàng
	'metadata'=> 'ghi chú', # ghi chú
	'order_id'=> '8936487235428', # mã đơn hàng của bạn
    'node_code'=> 'KHONGCHOXEMHANG', # ghi chú bắt buộc nhận 1 trong các giá trị sau ('CHOTHUHANG' : 'Cho thử hàng', 'CHOXEMHANGKHONGTHU' : 'Cho xem hàng, không cho thử', 'KHONGCHOXEMHANG': 'Không cho xem hàng')
	'length'=> 10, # chiều dài
	'width'=> 10, # chiều rộng
	'height'=> 10 # chiều cao
];
try {
        $gsResponse = $goship->createShipment($data);
    } catch (\Kingdarkness\Goship\Exceptions\ValidateException $e) {
        var_dump($e->errors);
    }

```

#### Lấy danh sách giá

```php
# public function getRates(array $data, [array $query = [], array $headers = [] )
$data = [
	'from_city'=> 100000, # (bắt buộc) mã tỉnh thành gửi
	'from_district'=> 100300, # (bắt buộc) mã quận huyện gửi
	'to_city'=> 100000, # (bắt buộc) mã tỉnh thành nhận
	'to_district' => 100300, # (bắt buộc) mã phường xã nhận
	'cod'=> 0, # tiền thu hộ
	'amount'=> 0, # giá trị khai giá
	'weight'=> 500, # (bắt buộc) cân nặng (g)
	'payer'=> \Kingdarkness\Goship\V2\Shipment::CUSTOMER_PAY, # người trả phí CUSTOMER_PAY => 0 (người nhận trả), SHOP_PAY => 1 (người gửi trả)
    'length'=> 10, # chiều dài
	'width'=> 10, # chiều rộng
	'height'=> 10 # chiều cao

];
try {
        $rates = $goship->getRates($data);
    } catch (\Kingdarkness\Goship\Exceptions\ValidateException $e) {
        var_dump($e->errors);
    }
```

#### Lấy danh sách lịch sử giao dịch

```php
# public function getTransactions(array $query = [], array $headers = [] )
$transactions = $goship->getTransactions();

```

#### Lấy danh sách phiên đối soát

```php
# public function getInvoices(array $query = [], array $headers = [] )
$invoices = $goship->getInvoices();

```

#### Lấy danh sách vận đơn thuộc đối soát

```php
# public function getShipmentInInvoice(mixed $invoiceCode, array $query = [], array $headers = [] )
$shipments = $goship->getShipmentInInvoice('INV0001');

```

#### Verify webhook

```php
# public function verifyWebhook()
try {
    $webhookData = $this->verifyWebhook();
	var_dump($webhookData);
    } catch (\Kingdarkness\Goship\Exceptions\UnverifiException $e) {
	echo 'fake data';
    }
```
