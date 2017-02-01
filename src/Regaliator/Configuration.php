<?php
namespace Regaliator;

class Configuration {
  const CLIENT_VERSION = '1.0.1';

  const DEFAULT_OPTIONS = [
    'version'    => '3.1',
    'timeout'    => 60,
    'api_host'   => 'api.casiregalii.com',
    'api_key'    => 'your-api-key',
    'secret_key' => 'your-secret-key',
    'useragent'  => 'Regaliator PHP v'.self::CLIENT_VERSION
  ];

  public $options = [];
  public $version;
  public $api_host;
  public $api_key;
  public $secret_key;

  public function __construct($options = []) {
    $this->options = array_merge(self::DEFAULT_OPTIONS, $options);

    $this->version = $this->options['version'];
    unset($this->options['version']);

    $this->api_host = $this->options['api_host'];
    unset($this->options['api_host']);

    $this->api_key = $this->options['api_key'];
    unset($this->options['api_key']);

    $this->secret_key = $this->options['secret_key'];
    unset($this->options['secret_key']);
  }

  public function accept() {
    return "application/vnd.regalii.v{$this->version}+json";
  }

  public function __set($name, $value) {
    $this->options[$name] = $value;
  }

  public function __get($name) {
    if (array_key_exists($name, $this->options)) {
      return $this->options[$name];
    }

    $trace = debug_backtrace();
    trigger_error(
      'Undefined property via __get(): ' . $name .
      ' in ' . $trace[0]['file'] .
      ' on line ' . $trace[0]['line'],
      E_USER_NOTICE);
    return null;
  }
}
