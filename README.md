# UDSGamePHP

Библиотека для использования API UDS Game

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205-blue.svg)](https://php.net/)
## Installation

```bash
composer require brizhanev/uds-game-php
```

## Usage

```php
<?php

//Подключить библиотеки, установленные через Composer
require('vendor/autoload.php');

//Задать переменные
//Промо-код из приложения UDS Game
$promoCode = "";
//Ключ API из UDS Game Admin компании
$apiKey = "";
//Айди клиента, можно получить по промокоду
$clientId = "";
//Количество баллов, для оплаты части суммы заказа
$scores = 100;
//Полная сумма заказа
$total = 1000;
//Часть суммы заказа, оплаченная деньгами, $cash = $total - $scores
$cash = 900;
//Номер счета
$invoiceNumber = "inv-001";

//Создать объект класса UDSGame
$UDS = new UDSGame\UDSGame($apiKey);

//Получить информацию о клиенте по промо-коду
$clientInfo = $UDS->getClientInfoByCode($promoCode);
$clientId = $clientInfo->id;

//Получить информацию о клиенте по ид клиента
$clientInfo = $UDS->getClientInfoById($clientId);

//Получить информацию о промо-коде
$promoCodeInfo = $UDS->parsePromoCode($promoCode);

//Получить информацию о компании, которой принадлежит ключ API
$companyInfo = $UDS->getCompanyInfo();

//Совершить покупку
$operation = $UDS->makePayment($promoCode, $scores, $total, $cash, $invoiceNumber);

//Отменить операцию
$companyInfo = $UDS->revertOperation($operation->id);

/** 
 * Результатом выполнения всех функций является объект, с полями, информацию о которых можно посмотреть в документации к API
 * https://udsgame.com/api-docs/partner#
 */

```






