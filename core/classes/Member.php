<?php
Class JMembers_Member {
	
	/**
	*
	* @var int
	*/
	public $user_id = 0;

	/**
	*
	* @var int
	*/
	public $package_id = 0;
	
	/**
	*
	* @var string
	*/
	public $status;
	
	/**
	*
	* @var int
	*/
	public $datetime_packjoin;
	
	/**
	*
	* @var int
	*/
	public $datetime_expire;

	/**
	*
	* @var string
	*/
	public $payment;

	public function __construct( $member = null ){
		if( $member == null )
			return;

		foreach( get_object_vars($this) as $key => $value )
			$this->$key = $value;
	}

	public function __get( $key ){
		if( 'membership' == $key && $this->__isset( $key ) ){
			$package = get_package( $this->package_id );

			return $package->membership_id;
		}

		if( 'payment' == $key ){
			return unserialize( $this->payment );
		}
	}

	public function __isset( $key ){
		if( 'user_id' == $key && 0 == $this->user_id )
			return FALSE;

		if( 'package_id' == $key && 0 == $this->package_id )
			return FALSE;

		if( '' == get_user_meta( $this->user_id, '_'.$key, true ) )
			return FALSE;

		return TRUE; 
	}

	public function save(){
		foreach( get_object_vars($this) as $key => $value ){
			if( 'user_id' != $key )
				add_user_meta( $this->user_id, '_'.$key, $value, true ) or update_user_meta( $this->user_id, '_'.$key, $value );
		}
	}
}