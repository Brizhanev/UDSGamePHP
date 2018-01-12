<?php

class UDSGame {
  
  /**
   * Содержит ключ API UDS Game
   * 
   * @var string;
   */
  private $apiKey;

  /** 
   * Конструктор
   * 
   * @param string $apiKey
   */
  public function __construct($apiKey) {
    $this->apiKey = $apiKey;
  }

  /** 
   * Получает информацию о платежах, скрытую за промо-кодом
   * 
   * @param string $promoCode
   * @return stdClass
   */
  public function parsePromoCode($promoCode) {

    $api_url = 'https://udsgame.com/v1/partner/promo-code?code=' . $promoCode;
    
		$ch = curl_init($api_url);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Accept: application/json',
      'X-Api-Key: ' . $this->apiKey,
      'X-Origin-Request-Id: ' . $this->makeUUID(),
      'X-Timestamp: ' . date('c') 
    ));

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = json_decode(curl_exec($ch));
    
    return $response;

  }

  /** 
   * Выбирает клиента по промо-коду. Если клиент является участником, он содержит оценки и уровень структуры
   * 
   * @param string $promoCode
   * @return stdClass
   */
  public function getClientInfoByCode($promoCode) {

    $api_url = 'https://udsgame.com/v1/partner/customer?code=' . $promoCode;
    
		$ch = curl_init($api_url);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Accept: application/json',
      'X-Api-Key: ' . $this->apiKey,
      'X-Origin-Request-Id: ' . $this->makeUUID(),
      'X-Timestamp: ' . date('c') 
    ));

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = json_decode(curl_exec($ch));
    
    return $response;

  }

  /** 
   * Выбирает клиента по идентификатору клиента. Если клиент является участником, он содержит оценки и уровень структуры
   * 
   * @param string $clientId
   * @return stdClass
   */
  public function getClientInfoById($clientId) {

    $api_url = 'https://udsgame.com/v1/partner/customer?customerId=' . $clientId;
    
		$ch = curl_init($api_url);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Accept: application/json',
      'X-Api-Key: ' . $this->apiKey,
      'X-Origin-Request-Id: ' . $this->makeUUID(),
      'X-Timestamp: ' . date('c') 
    ));

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = json_decode(curl_exec($ch));
    
    return $response;

  }

  /** 
   * Выбирает настройки маркетинга и промо-код для приглашений. Параметры маркетинга можно настроить в Game Admin
   * 
   * @param string $clientId
   * @return stdClass
   */
  public function getCompanyInfo() {

    $api_url = 'https://udsgame.com/v1/partner/company';
    
		$ch = curl_init($api_url);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Accept: application/json',
      'X-Api-Key: ' . $this->apiKey,
      'X-Origin-Request-Id: ' . $this->makeUUID(),
      'X-Timestamp: ' . date('c') 
    ));

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = json_decode(curl_exec($ch));
    
    return $response;

  }

  /** 
   * Выполните операцию покупки. При успешной работе появляется в списке операций в UDS Game Admin, и клиент получает push-уведомление о покупке
   * 
   * @param string $promoCode
   * @param float $scores
   * @param float $total
   * @param float $cash
   * @param string $invoiceNumber
   * @param string $cashierExternalId
   * @return stdClass
   */
  public function makePayment($promoCode, $scores, $total, $cash, $invoiceNumber = NULL, $cashierExternalId = NULL) {

    $api_url = 'https://udsgame.com/v1/partner/purchase';

		$ch = curl_init($api_url);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Accept: application/json',
      'X-Api-Key: ' . $this->apiKey,
      'Content-Type: application/json',
      'X-Origin-Request-Id: ' . $this->makeUUID(),
      'X-Timestamp: ' . date('c') 
    ));

    $data = json_encode(array(
      "scores" => (float)$scores,
      "total" => (float)$total,
      "cash" => (float)$cash,
      "code" => $promoCode
    ));

    if ($invoiceNumber != NULL) {
      $data['invoiceNumber'] = $invoiceNumber;
    }

    if ($cashierExternalId != NULL) {
      $data['cashierExternalId'] = $cashierExternalId;
    }

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = json_decode(curl_exec($ch));
    
    return $response;

  }

  /** 
   * Отмена операции
   * 
   * @param int $id
   * @return stdClass
   */
  public function revertOperation($id) {

    $api_url = 'https://udsgame.com/v1/partner/revert/' . $id;

		$ch = curl_init($api_url);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Accept: application/json',
      'X-Api-Key: ' . $this->apiKey,
      'Content-Type: application/json',
      'X-Origin-Request-Id: ' . $this->makeUUID(),
      'X-Timestamp: ' . date('c') 
    ));

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = json_decode(curl_exec($ch));
    
    return $response;

  }

  private function makeUUID() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

      // 32 bits for "time_low"
      mt_rand(0, 0xffff), mt_rand(0, 0xffff),

      // 16 bits for "time_mid"
      mt_rand(0, 0xffff),

      // 16 bits for "time_hi_and_version",
      // four most significant bits holds version number 4
      mt_rand(0, 0x0fff) | 0x4000,

      // 16 bits, 8 bits for "clk_seq_hi_res",
      // 8 bits for "clk_seq_low",
      // two most significant bits holds zero and one for variant DCE1.1
      mt_rand(0, 0x3fff) | 0x8000,

      // 48 bits for "node"
      mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
  }

}