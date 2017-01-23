# Regalii PHP Client

A PHP client for connecting to the Regalii API.

## Usage

After requiring `regalii/regaliator` in your `composer.json` file, you can use the class like so:

```php
$configuration = new Regaliator\Configuration([
  'version' => '3.1',
  'api_host' => 'api.casiregalii.com',
  'api_key' => getenv('REGALII_API_KEY'),
  'secret_key' => getenv('REGALII_SECRET')
]);
$regaliator = new Regaliator\Regaliator($configuration);

$response = $regaliator->account();

if ($response->success) {
  $data = json_decode($response->body, true);
} else {
  echo "Failed with status code {$response->status_code}";
}
```

The `$response` will be a `Response` object from the [Requests](http://requests.ryanmccue.info/) library.

## Examples

Examples of some common use-cases:

### Creating a credential bill

```php
$response = $regaliator->create_credentials_bill(12376, 'login', 'challengeme');
$bill = json_decode($response->body, true);
echo "Created bill {$bill['id']}\n";
```

### Polling for while bill fetching

```php
function poll_while_updating($regaliator, $id) {
  for($i = 0; $i < 60; $i++) {
    echo "Checking status for bill {$id} after sleeping 1 second\n";
    sleep(1);

    $response = $regaliator->show_bill($id);
    $bill = json_decode($response->body, true);

    if ($bill['status'] !== 'fetching') {
      return $bill;
    }
  }
  // raise exception because bill is still fetching
}

$bill = poll_while_updating($regaliator, $bill['id']);
```

### Answering MFA Challenge

```php
$response = $regaliator->update_bill_mfas($bill['id'], ['mfa_challenges' => [
  [
    'id' => $bill['mfa_challenges'][0]['id'],
    'type' => $bill['mfa_challenges'][0]['type'],
    'response' => '8'
  ]
]]);
```
