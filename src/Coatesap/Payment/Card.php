<?php

namespace Coatesap\Payment;

class Card
{

    public $number;
    public $code;
    public $expiryMonth = 0;
    public $expiryYear = 0;

    public function __construct()
    {
    }

    /**
     * Find out if the card has expired.
     *
     * @return bool
     */
    public function hasExpired()
    {
        if (version_compare(phpversion(), '5.2.0', '>=')) {
            $dateString = $this->expiryYear . '-' . ($this->expiryMonth + 1) . '-01 00:00:00';
            $expiry_date = \DateTime::createFromFormat('Y-m-d H:i:s', $dateString);
            $today = new \DateTime();

            return ($today >= $expiry_date) ? true : false;
        } else {
            $card = mktime(0, 0, 0, $this->expiryMonth, '01', $this->expiryYear);
            $today = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $cont = $card - $today;

            return ($cont < 0) ? true : false;
        }

    }

    /**
     * Bulk fill the card with data.
     *
     * @param array $data
     */
    public function populate($data)
    {
        if (isset($data['number'])) {
            $this->number = preg_replace('/[^0-9]/', '', $data['number']);
        }
        if (isset($data['code'])) {
            $this->code = preg_replace('/[^0-9]/', '', $data['code']);
        }
        if (isset($data['expiryMonth'])) {
            $expiryMonth = preg_replace('/[^0-9]/', '', $data['expiryMonth']);
            $this->expiryMonth = (int)$expiryMonth;
        }
        if (isset($data['expiryYear'])) {
            $expiryYear = preg_replace('/[^0-9]/', '', $data['expiryYear']);
            $this->expiryYear = (int)$expiryYear;
        }
    }

    /**
     * Validate card details.
     *
     * @param string $message Error message returned by reference
     * @return bool
     */
    public function isValid(&$message = '')
    {
        if (strlen($this->code) < 3) {
            $message = 'Card security code should be at least 3 characters';

            return false;
        }
        if (strlen($this->code) > 4) {
            $message = 'Card security code should be no more than 4 characters';

            return false;
        }
        if ($this->expiryMonth == 0) {
            $message = 'Expiry month is required';

            return false;
        }
        if ($this->expiryMonth > 12) {
            $message = 'Expiry month is not valid';

            return false;
        }
        if ($this->expiryYear == 0) {
            $message = 'Expiry year is required';

            return false;
        }
        if (!$this->luhnCheck($this->number)) {
            $message = 'Card number is not valid';

            return false;
        }
        if ($this->hasExpired()) {
            $message = 'Card has expired';

            return false;
        }

        return true;
    }

    /**
     * Determine whether a card number passes the Luhn check.
     *
     * @param string|int $number Card number
     * @return bool
     */
    private function luhnCheck($number)
    {
        // Strip any non-digits (useful for credit card numbers with spaces and hyphens)
        $number = preg_replace('/\D/', '', $number);

        // Set the string length and parity
        $numberLength = strlen($number);
        $parity = $numberLength % 2;

        // Loop through each digit and do the maths
        $total = 0;
        for ($i = 0; $i < $numberLength; $i++) {
            $digit = $number[$i];
            // Multiply alternate digits by two
            if ($i % 2 == $parity) {
                $digit *= 2;
                // If the sum is two digits, add them together (in effect)
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            // Total up the digits
            $total += $digit;
        }

        // If the total mod 10 equals 0, the number is valid
        return ($total % 10 == 0) ? true : false;

    }

    /**
     * Creates an array representation of the card.
     *
     * @return array
     */
    public function toArray()
    {
        $arr = array();
        $reflection = new \ReflectionObject($this);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
        foreach ($properties as $obj) {
            $arr[$obj->name] = $this->{$obj->name};
        }

        return $arr;
    }
}
