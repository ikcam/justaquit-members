<?php
Class JMembers_Package {
	public $ID;
	private $membership_id;
	private $duration;
	private $duration_type;
	private $price ;
	private $billing;
	private $description;
	private $expired_package;
	private $display; // Array
	private $menu_order;
	private $payment; // Array

	public function __construct( $membership_id, $duration = 0, $duration_type = 0, $price = 0, $billing = 0, $description = NULL, $expired_package = 0, $display = NULL, $menu_order = 0, $payment = NULL ){
		$this->membership_id   = (int)$membership_id;
		$this->duration        = (int)$duration;
		$this->duration_type   = (int)$duration_type;
		$this->price           = (float)$price;
		$this->billing         = (int)$billing;
		$this->description     = (String)$description;
		$this->expired_package = (int)$expired_package;
		$this->display         = serialize($display);
		$this->menu_order      = (int)$menu_order;
		$this->payment         = serialize($payment);
	}

	public function add(){
		global $wpdb;
		$table = $wpdb->prefix.'jm_packages';

		$data   = get_object_vars($this);
		unset( $data['ID'] );

		echo '<script>alert("'.var_dump($data).'")</script>';

		$format = array( '%d', '%d', '%d', '%f', '%d', '%s', '%d', '%s', '%d', '%s' );

		if( !$wpdb->insert( $table, $data, $format ) )
			return FALSE;

		$this->ID = $wpdb->insert_id;

		return TRUE;
	}

	public function update( $ID ){
		global $wpdb;
		$table = $wpdb->prefix.'jm_packages';

		$data   = get_object_vars($this);
		var_dump($data);
		$where  = array( 'ID' => $ID );
		$format = array( '%d', '%d', '%d', '%f', '%d', '%s', '%d', '%s', '%d', '%s' );

		if( !$wpdb->update( $table, $data, $where, $format ) )
			return FALSE;

		return TRUE;
	}

	public static function delete( $ID ){
		global $wpdb;
		$table = $wpdb->prefix.'jm_packages';

		$query = "DELETE FROM $table WHERE ID = %d";

		$result = $wpdb->query( $wpdb->prepare( $query, $ID ) );

		if( !$result || $result == 0 )
			return FALSE;

		return TRUE;
	}

}