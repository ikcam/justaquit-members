<?php
Class JMembers_Transaction {
	
	/**
	*
	* @var int
	*/
	public $ID;

	/**
	*
	* @var int
	*/
	public $user_id;

	/**
	*
	* @var int
	*/
	public $package_id = NULL;

	/**
	*
	* @var int
	*/
	public $post_id    = NULL;

	/**
	*
	* @var int
	*/
	public $datetime;

	/**
	*
	* @var string
	*/
	public $data;

	public function __construct( $transaction = NULL ){
		if( $transaction == NULL )
			return;

		foreach( get_object_vars($this) as $key => $value )
			$this->$key = $value;
	}

	public function add(){
		global $wpdb;
		$table = $wpdb->prefix.'jm_transactions';

		$data = get_object_vars($this);
		unset( $data['ID'] );

		if( !$wpdb->insert( $table, $data ) )
			return FALSE;

		$this->ID = $wpdb->insert_id;

		return TRUE;
	}

	public function update( $ID ){
		global $wpdb;
		$table = $wpdb->prefix.'jm_transactions';

		$ID = (int) $ID;

		$data = get_object_vars($this);
		unset( $data['ID'] );

		$where = array( 'ID' => $ID	);

		if( !$wpdb->update( $table, $data, $where ) )
			return FALSE;

		return TRUE;
	}

	public function delete( $ID ){
		global $wpdb;
		$table = $wpdb->prefix.'jm_transactions';

		$ID = (int) $ID;

		$query = "DELETE FROM $table WHERE ID = %d";

		if( !$wpdb->query( $wpdb->prepare( $query, $ID ) ) )
			return FALSE;

		return TRUE;
	}
}