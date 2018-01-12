<?php

require("src/UDSGame.php");
require("tests/Data.php");

use PHPUnit\Framework\TestCase;

class UDSGameTest extends TestCase {

  public function testParsePromoCode() {
    $UDS = new UDSGame(Data::$apiKey);
    $promoCodeInfo = $UDS->parsePromoCode(Data::$promoCode);
    $this->assertEquals($promoCodeInfo->code, Data::$promoCode);
  }

  public function testGetClientInfoByCode() {
    $UDS = new UDSGame(Data::$apiKey);
    $clientInfo = $UDS->getClientInfoByCode(Data::$promoCode);
    $this->assertObjectHasAttribute('id', $clientInfo);
  }

  public function testGetClientInfoById() {
    $UDS = new UDSGame(Data::$apiKey);
    $clientInfo = $UDS->getClientInfoById(Data::$clientId);
    $this->assertObjectHasAttribute('id', $clientInfo);
  }

  public function testGetCompanyInfo() {
    $UDS = new UDSGame(Data::$apiKey);
    $companyInfo = $UDS->getCompanyInfo();
    $this->assertObjectHasAttribute('id', $companyInfo);
  }

}