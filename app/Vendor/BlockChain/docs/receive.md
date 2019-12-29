Receive Documentation
=====================
The simplest way to receive Bitcoin payments. Blockchain forwards all incoming Bitcoin to the address you specify.

Be sure to check out the [official documentation](https://blockchain.info/api/api_receive) for information on callback URLs.

Usage
-----

Call `Receive->generate` on a `Blockchain` object. Pass an address and an optional callback URL. All Bitcoin received at the generated address will be forwarded to the input address. Returns a `ReceiveResponse` object.

```php
$Blockchain = new \Blockchain\Blockchain($api_code);

$my_address = '1xYourBitcoinAddress';
$callback_url = 'http://example.com/transaction?secret=mySecret';

$response = $Blockchain->Receive->generate($my_address, $callback_url);

// Display address to user:
echo "Send coins to " . $response->address;
```

###ReceiveReponse Object

```php
class ReceiveResponse {
    public $address;                    // string
    public $fee_percent;                // int
    public $destination;                // string
    public $callback_url;               // string
}
```