<?php
Class JMembers_Package {
	
	/**
	*
	* @var int
	*/
	public $ID;

	/**
	*
	* @var int
	*/
	public $membership_id;

	/**
	*
	* @var int
	*/
	public $duration;
	
	/**
	*
	* @var int
	*/
	public $duration_type;
	
	/**
	*
	* @var float
	*/
	public $price ;
	
	/**
	*
	* @var int
	*/
	public $billing;

	/**
	*
	* @var string
	*/
	public $description;

	/**
	*
	* @var int
	*/
	public $expired_package;

	/**
	*
	* @var string
	*/
	public $display;

	/**
	*
	* @var int
	*/
	public $menu_order;

	/**
	*
	* @var string
	*/
	public $payment;

	public function __construct( $package = NULL ){
		if( $package == NULL )
			return TRUE;

		foreach( get_object_vars($package) as $key=>$value )
			$this->$key = $value;
	}

	public function add(){
		global $wpdb;
		$table = $wpdb->prefix.'jm_packages';

		$data   = get_object_vars($this);
		unset( $data['ID'] );

		if( !$wpdb->insert( $table, $data ) )
			return FALSE;

		$this->ID = $wpdb->insert_id;

		return TRUE;
	}

	public function update( $ID ){
		global $wpdb;
		$table = $wpdb->prefix.'jm_packages';

		$ID = (int) $ID;

		$data   = get_object_vars($this);
		unset( $data['ID'] );

		$where  = array( 'ID' => $ID );

		if( !$wpdb->update( $table, $data, $where ) )
			return FALSE;

		return TRUE;
	}

	public static function delete( $ID ){
		global $wpdb;
		$table = $wpdb->prefix.'jm_packages';

		$ID = (int) $ID;

		$query = "DELETE FROM $table WHERE ID = %d";

		if( !$wpdb->query( $wpdb->prepare( $query, $ID ) ) )
			return FALSE;

		return TRUE;
	}

}