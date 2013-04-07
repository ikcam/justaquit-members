<?php
Class JM_Payment_PayPalPro{
	private $user;
	private $pwd;
	private $signature;
	private $version;
	private $method;
	private $sandbox;

	public function __construct(){
		global $jmembers_settings;

		$this->user      = $jmembers_settings['payment_pppro_username'];
		$this->pwd       = $jmembers_settings['payment_pppro_password'];
		$this->signature = $jmembers_settings['payment_pppro_signature'];
		$this->sandbox   = $jmembers_settings['payment_pppro_sandbox'];
		$this->version   = '86.0';
	}

	public function toString(){
		$string = '';

		foreach( get_object_vars($this) as $key => $value )
			$string .= '&'.strtoupper($key).'='.urlencode($value);

		return $string;
	}

	public function process( $string, $method ){
		$this->method = $method;

		$curl = curl_init();

		curl_setopt( $curl, CURLOPT_VERBOSE, 1 );
		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, FALSE );
		curl_setopt( $curl, CURLOPT_TIMEOUT, 30 );
		if( $this->sandbox == 1 ):
			curl_setopt( $curl, CURLOPT_URL, 'https://api-3t.sandbox.paypal.com/nvp' );
		else:
			curl_setopt( $curl, CURLOPT_URL, 'https://api-3t.sandbox.paypal.com/nvp' );
		endif;
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $curl, CURLOPT_POSTFIELDS, $this->toString().$string );

		$result = curl_exec( $curl );
		curl_close( $curl );

		$result = $this->NVPToArray( $result );

		if( $result['ACK'] == 'Success' )
			return $result;

		return FALSE;
	}

	private function NVPToArray($NVPString){
		$proArray = array();

		while( strlen( $NVPString ) ){
			// name
			$keypos            = strpos( $NVPString, '=' );
			$keyval            = substr( $NVPString, 0, $keypos );
			// value
			$valuepos          = strpos( $NVPString, '&' ) ? strpos( $NVPString, '&' ): strlen( $NVPString );
			$valval            = substr( $NVPString, $keypos + 1, $valuepos - $keypos - 1 );
			// decoding the respose
			$proArray[$keyval] = urldecode( $valval );
			$NVPString         = substr( $NVPString, $valuepos + 1, strlen( $NVPString ) );
		}

		return $proArray;
	}
}