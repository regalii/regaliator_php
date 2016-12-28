# Regalii PHP Client

A PHP client for connecting to the Regalii API.

## Usage

After requiring `regalii/regaliator` in your `composer.json` file, you can use the class like so:

```php
$regaliator = new Regaliator\Regaliator(
  'https://test.casiregalii.com',
  getenv('REGALII_API_KEY'),
  getenv('REGALII_SECRET')
);

$response = $regaliator->account();

if ($response->success) {
  $data = json_decode($response->body, true);
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

### Polling for while bill updating

```php
function poll_while_updating($regaliator, $id) {
  for($i = 0; $i < 60; $i++) {
    echo "Checking status for bill {$id} after sleeping 1 second\n";
    sleep(1);

    $response = $regaliator->show_bill($id);
    $bill = json_decode($response->body, true);

    if ($bill['status'] !== 'updating') {
      return $bill;
    }
  }
  // raise exception because bill is still updating
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
