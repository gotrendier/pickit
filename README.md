# PickIt PHP SDK
PHP SDK for PickIt.net services.
Based on https://dev.pickit.net/

## Install

`composer require gotrendier/pickit-php-sdk`

## Requirements

- `>= PHP 7.4`
- CURL

## Usage

### Initialization

```php
$pickIt = new \PickIt\PickItClient('API_KEY', 'TOKEN', 'COUNTRY', $sandBox = true);
```

### Get MapPoints

```php
$response = $pickIt->getMapPoint(1, 10);
```

### Create Budget

```php

use \PickIt\Entities\Person;
use \PickIt\Entities\Product;
use \PickIt\Entities\Measure;
use \PickIt\Entities\Address;
use \PickIt\Requests\BudgetPetitionRequest;

$products = [
        new Product("Botines dulces",
            new Measure(55, Measure::UNIT_G),
            new Measure(150, Measure::UNIT_CM),
            new Measure(30, Measure::UNIT_CM),
            new Measure(30, Measure::UNIT_CM),
            1
        )
    ];

    $customer = (new Person("Marta", "Fernandez"))
        ->setPid("345345")
        ->setEmail("edualdo@gmail.com")
        ->setAddress(new Address(
            "11320",
            "LAGO COMO 21",
            "ciudad de México",
            "México"
        ))
    ;

    $request = new BudgetPetitionRequest(PickIt::SERVICE_TYPE_PICKIT_POINT,
        PickIt::WORKFLOW_DISPATCH,
        PickIt::OPERATION_TYPE_TO_POINT,
        $products,
        PickIt::SLA_STANDARD,
        $customer);
    $request->setPointId(1086);

    $response = $pickIt->createBudget($request);
```

### Create Transaction

```php

use \PickIt\Requests\TransactionRequest;

$request = new TransactionRequest(PickIt::START_TYPE_AVAILABLE_FOR_COLLECTION, "ORDER_ID");
$response = $pickIt->createTransaction("BUDGET_UUID", $request);
```

### Get Shipment status
```php
$response = $pickIt->getShipmentStatus("TRACKING_CODE");
```


## Development

1. `cp pre-commit.php .git/hooks/pre-commit`
1. `chmod 775 .git/hooks/pre-commit`