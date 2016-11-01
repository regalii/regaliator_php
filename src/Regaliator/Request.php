<?php
namespace Regalii;

class Request {
  
  const CONTENT_TYPE = 'application/json';
  const ACCEPT = 'application/vnd.regalii.v3.0+json';

  public $api_host;
  public $api_key;
  public $secret;

  public function __construct($api_host, $api_key, $secret) {
    $this->api_host = $api_host;
    $this->api_key = $api_key;
    $this->secret = $secret;
  }

  public function post($endpoint, $content) {
    $content_json = json_encode($content);
    $content_md5 = base64_encode(md5($content_json, true));
    $headers = $this->headers($endpoint, $content_md5);
    return \Requests::post("{$this->api_host}{$endpoint}", $headers, $content_json);
  }

  public function patch($endpoint, $content) {
    $content_json = json_encode($content);
    $content_md5 = base64_encode(md5($content_json, true));
    $headers = $this->headers($endpoint, $content_md5);
    return \Requests::patch("{$this->api_host}{$endpoint}", $headers, $content_json);
  }

  public function get($endpoint, $params = []) {
    $content_md5 = "";
    $headers = $this->headers($endpoint, $content_md5);
    return \Requests::request("{$this->api_host}{$endpoint}", $headers, $params);
  }

  private function now() {
    $date = new \DateTime('UTC');
    return $date->format('D, d M Y H:i:s \G\M\T');
  }

  private function headers($endpoint, $content_md5) {
    $date = $this->now();

    return array(
      'Content-Type' => self::CONTENT_TYPE,
      'Accept' => self::ACCEPT,
      'Date' => $date,
      'Content-MD5' => $content_md5,
      'Authorization' => "APIAuth {$this->api_key}:{$this->auth_hash($endpoint, $content_md5, $date)}"
    );
  }

  private function auth_hash($endpoint, $content_md5, $date) {
    $data_pieces = array(self::CONTENT_TYPE, $content_md5, $endpoint, $date);
    $data = implode(',', $data_pieces);
    $raw_hmac = hash_hmac("sha1", $data, $this->secret, true);
    return base64_encode($raw_hmac);
  }

}
