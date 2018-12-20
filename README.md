# laravel-payment

This is a aliyun expansion for the laravel

[![License](https://poser.pugx.org/xutl/laravel-payment/license.svg)](https://packagist.org/packages/xutl/laravel-payment)
[![Latest Stable Version](https://poser.pugx.org/xutl/laravel-payment/v/stable.png)](https://packagist.org/packages/xutl/laravel-payment)
[![Total Downloads](https://poser.pugx.org/xutl/laravel-payment/downloads.png)](https://packagist.org/packages/xutl/laravel-payment)

## 接口支持
- WeChat
- AliPay

## 环境需求

- PHP >= 7.0

## Installation

```bash
composer require xutl/laravel-payment
```

## for Laravel

This service provider must be registered.

```php
// config/app.php

'providers' => [
    '...',
    XuTL\Payment\PaymentServiceProvider::class,
];
```


## Use

```php
try {
	$wechat = Payment::get('wechat');
	
} catch (\Exception $e) {
	print_r($e->getMessage());
}
```