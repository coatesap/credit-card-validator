Credit Card Validator
=====================

A simple PHP class for preparing and validating credit/debit card details. Typically this takes place before they are passed to a payment gateway for processing.


## Example Usage

```PHP
require '../vendor/autoload.php';

$cardDetails = array(
    'number' =>'4929000000006',
    'expiryYear' => 2013,
    'expiryMonth' => 5,
    'code' => '123'
);

$card = new Coatesap\Payment\Card();
$card->populate($cardDetails);

if (!$card->isValid($message)) {
    // show error message (set by reference)
    echo 'There is a problem with your card details: ' . $message;
} else {
    // get the prepped, validated card data as an array
    $cardData = $card->toArray();
    // send $cardData to payment gateway
}
```

## Installation

The credit card class can be installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "coatesap/credit-card-validator": "~2.0"
    }
}
```

## Data preparation

This class does some simple preparation of your card data. This includes removing non-numeric characters, including spaces, from:
- The card number
- The CVC/CV2 value
- The card expiry date

## Validation Checks

This class also checks:
- That the card hasn't expired
- A valid CVC/CV2 security code has been supplied
- The card number passes the Luhn check
