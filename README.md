Credit Card Class
=================

Simple PHP class for preparing and validating credit/debit card details. Typically this takes place before they are passed to a payment gateway for processing.


Example Usage
-------------

```PHP
$card_details = array(
	'number' =>'4929000000006', 
	'expiry_year' => 2013,
	'expiry_month' => 5,
	'code' => '123'
);

$card = new Card();
$card->populate($card_details);

if (!$card->is_valid($message)) {
    // show error message
    echo 'There is a problem with your card details: ' . $message;
} else {
    // get the prepped, validated card data as an array
    $card_data = $card->to_array();
    // send $card_data to payment gateway
}
```

## Installation

The credit card class can be installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "coatesap/credit-card-validator": "~1.0"
    }
}
```

Data preparation
----------------
This class does some simple preparation of your card data. This includes removing non-numeric characters, including spaces, from:
- The card number
- The CVC/CV2 value
- The card expiry date

Validation Checks
-----------------
This class also checks:
- That the card hasn't expired
- A valid CVC/CV2 security code has been supplied
- The card number passes the Luhn check
