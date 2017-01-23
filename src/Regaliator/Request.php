<?php
namespace Regaliator;

class Request {
  const CONTENT_TYPE = 'application/json';

  public $configuration;

  public function __construct($configuration) {
    $this->configuration = $configuration;
  }

  public function post($endpoint, $content = []) {
    $content_json = json_encode($content);
    $content_md5 = base64_encode(md5($content_json, true));
    $headers = $this->headers($endpoint, $content_md5);
    return \Requests::post("https://{$this->configuration->api_host}{$endpoint}", $headers, $content_json, $this->configuration->options);
  }

  public function patch($endpoint, $content) {
    $content_json = json_encode($content);
    $content_md5 = base64_encode(md5($content_json, true));
    $headers = $this->headers($endpoint, $content_md5);
    return \Requests::patch("https://{$this->configuration->api_host}{$endpoint}", $headers, $content_json, $this->configuration->options);
  }

  public function get($endpoint, $params = []) {
    $content_md5 = "";
    if ($params) {
      $query = "?".http_build_query($params);
    } else {
      $query = "";
    }
    $headers = $this->headers("{$endpoint}{$query}", $content_md5);
    return \Requests::get("https://{$this->configuration->api_host}{$endpoint}{$query}", $headers, $this->configuration->options);
  }

  private function now() {
    $date = new \DateTime('UTC');
    return $date->format('D, d M Y H:i:s \G\M\T');
  }

  private function headers($endpoint, $content_md5) {
    $date = $this->now();

    return array(
      'Content-Type' => self::CONTENT_TYPE,
      'Accept' => $this->configuration->accept(),
      'Date' => $date,
      'Content-MD5' => $content_md5,
      'Authorization' => "APIAuth {$this->configuration->api_key}:{$this->auth_hash($endpoint, $content_md5, $date)}"
    );
  }

  private function auth_hash($endpoint, $content_md5, $date) {
    $data_pieces = array(self::CONTENT_TYPE, $content_md5, $endpoint, $date);
    $data = implode(',', $data_pieces);
    $raw_hmac = hash_hmac("sha1", $data, $this->configuration->secret_key, true);
    return base64_encode($raw_hmac);
  }

}
