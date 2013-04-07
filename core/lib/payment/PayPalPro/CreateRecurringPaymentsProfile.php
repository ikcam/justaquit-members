<?php
Class PayPalPro_CreateProfile extends JM_Payment_PayPalPro {
	// Credit Card Information
	public $creditcardtype;
	public $acct;
	public $expdate;
	public $cvv2;
	// Billing Information
	public $firstname;
	public $lastname;
	public $address;
	public $city;
	public $state;
	public $country;
	public $zip;
	// Package information
	public $amt;
	public $currencycode;
	public $desc;
	public $billingperiod;
	public $billingfrequency;
	public $profilestartdate;
	
	public function __construct( $profile = NULL ){
		if( $profile == NULL )
			return;

		foreach( get_object_vars($profile) as $key => $value )
			$this->$key = $value;
	}

	public function toString(){
		$string = '';

		foreach( get_object_vars($this) as $key => $value ) 
			$string .= '&'.strtoupper($key).'='.urlencode($value);

		return $string;
	}
}