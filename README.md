Credit Card Class
=================

Simple PHP class for validating a credit/debit card details before passing to a payment gateway.


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
}

// get the prepped, validated card data as an array
$card_data = $card->to_array();
```

Data preparation
----------------
- Non-numeric characters, including spaces removed from:
- - Card number
- - CVC/CV2
- - Expiry date parts

Validation Checks
-----------------
- That the card hasn't expired
- A valid CVC/CV2 security code has been supplied
- The card number passes the Luhn check
