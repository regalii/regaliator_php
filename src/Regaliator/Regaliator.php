<?php
namespace Regaliator;

class Regaliator {

  public $request;

  public function __construct($api_host, $api_key, $secret) {
    $this->request = new Request($api_host, $api_key, $secret);
  }

  public function account() {
    return $this->request->get('/account');
  }

  public function bills($params = []) {
    return $this->request->get('/bills', $params);
  }

  public function create_credentials_bill($biller_id, $login, $password) {
    $content = array(
      'biller_id' => $biller_id,
      'login' => $login,
      'password' => $password
    );
    return $this->request->post('/bills', $content);
  }

  public function create_account_number_bill($biller_id, $account_number) {
    $content = array(
      'biller_id' => $biller_id,
      'account_number' => $account_number
    );
    return $this->request->post('/bills', $content);
  }

  public function update_bill_mfas($bill_id, $mfas) {
    return $this->request->patch("/bills/{$bill_id}", $mfas);
  }

  public function show_bill($bill_id) {
    return $this->request->get("/bills/{$bill_id}");
  }

  public function show_bill_xdata($bill_id) {
    return $this->request->get("/bills/{$bill_id}/xdata");
  }

  public function xpay_bill($bill_id, $content) {
    return $this->request->post("/bills/{$bill_id}/pay", $content);
  }

  public function xchange_bill($bill_id, $content) {
    return $this->request->post("/bills/{$bill_id}/xchange", $content);
  }

  public function update_bill($bill_id, $content) {
    return $this->request->patch("/bills/{$bill_id}", $content);
  }

  public function billers($type, $params = []) {
    return $this->request->get("/billers/{$type}", $params);
  }

  public function rates() {
    return $this->request->get("/rates");
  }

  public function transactions($params = []) {
    return $this->request->get("/transactions", $params);
  }

}
