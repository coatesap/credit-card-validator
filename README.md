credit-card-class
=================

Simple PHP class for validating a credit/debit card before passing to a payment gateway.

Validation Checks
-----------------
- That the card hasn't expired
- A valid CVC/CV2 security code has been supplied
- The card number passes the Luhn check

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

if (!$card->is_valid($message)) die($message);

$card_data = $card->to_array();
```
