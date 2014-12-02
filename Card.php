<?php 
namespace Coatesap\Card;

class Card {
	
	public $number;
	public $code;
	public $expiry_month = 0;
	public $expiry_year = 0;
	
	function __construct()
	{	
	}
	
	public function has_expired()
	{
		if (version_compare(phpversion(), '5.2.0', '>='))
		{
			$date_string = $this->expiry_year . '-' . ($this->expiry_month+1) . '-01 00:00:00';
			$expiry_date = \DateTime::createFromFormat('Y-m-d H:i:s', $date_string);
			$today = new \DateTime();
			return ($today >= $expiry_date) ? true : false ;	
		} 
		else
		{
			$card = mktime(0, 0, 0, $this->expiry_month, '01', $this->expiry_year);
			$today = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
			$cont = $card-$today;
			return ($cont < 0) ? true : false ;
		}
		
	}
	
	public function populate($data)
	{
		if (isset($data['number'])) $this->number = preg_replace('/[^0-9]/', '', $data['number']);
		if (isset($data['code'])) $this->code = preg_replace('/[^0-9]/', '', $data['code']);
		if (isset($data['expiry_month'])) {
			$expiry_month = preg_replace('/[^0-9]/', '', $data['expiry_month']);
			$this->expiry_month = (int) $expiry_month;
		}
		if (isset($data['expiry_year'])) {
			$expiry_year = preg_replace('/[^0-9]/', '', $data['expiry_year']);
			$this->expiry_year = (int) $expiry_year;
		}
	}
	
	public function is_valid(&$message='')
	{
		if (strlen($this->code) < 3) {
			$message = 'Card security code should be at least 3 characters';
			return false;	
		}
		if (strlen($this->code) > 4) {
			$message = 'Card security code should be no more than 4 characters';
			return false;	
		}
		if ($this->expiry_month == 0) {
			$message = 'Expiry month is required';
			return false;	
		}
		if ($this->expiry_month > 12) {
			$message = 'Expiry month is not valid';
			return false;	
		}
		if ($this->expiry_year == 0) {
			$message = 'Expiry year is required';
			return false;	
		}
		if (!$this->luhn_check($this->number)) {
			$message = 'Card number is not valid';
			return false;
		}
		if ($this->has_expired()) {
			$message = 'Card has expired';
			return false;	
		}
		
		return true;
	}
	
	private function luhn_check($number) 
	{		
		// Strip any non-digits (useful for credit card numbers with spaces and hyphens)
		$number = preg_replace('/\D/', '', $number);
		
		// Set the string length and parity
		$number_length = strlen($number);
		$parity = $number_length % 2;
		
		// Loop through each digit and do the maths
		$total = 0;
		for ($i = 0; $i < $number_length; $i++) {
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
		return ($total % 10 == 0) ? TRUE : FALSE;
	
	}
	
	// return an array representation of this card
	public function to_array()
	{
		$arr = array();
		$reflection = new ReflectionObject($this);
		$properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
		foreach ($properties as $obj) {
			$arr[$obj->name] = $this->{$obj->name};	
		}
		return $arr;
	}
		
}
