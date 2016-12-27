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
