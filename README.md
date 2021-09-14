# PickIt PHP SDK
PHP SDK for PickIt.net services

## Install

`composer require gotrendier/pickit-php-sdk`

## Requirements

- >= PHP 7.4
- CURL

## Usage

```php
$pickIt = new \PickIt\PickIt('API_KEY', 'TOKEN', 'COUNTRY', $sandBox = true);

$response = $pickIt->getMapPoint(1, 10);
```