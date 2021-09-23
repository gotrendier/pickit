# PickIt PHP SDK
PHP SDK for PickIt.net services.
Based on https://dev.pickit.net/

## Install

`composer require gotrendier/pickit-php-sdk`

## Requirements

- `>= PHP 7.4`
- CURL

## Usage

```php
$pickIt = new \PickIt\PickIt('API_KEY', 'TOKEN', 'COUNTRY', $sandBox = true);

$response = $pickIt->getMapPoint(1, 10);
```

## Development

1. `cp pre-commit.php .git/hooks/pre-commit`
1. `chmod 775 .git/hooks/pre-commit`