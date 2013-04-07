<?php
Class JMembers_Membership {

	/**
	*
	* @var int
	*/
	public $ID;
	
	/**
	*
	* @var string
	*/
	public $name;

	public function __construct( $membership = NULL ){
		if( $membership == NULL )
			return TRUE;

		foreach( get_object_vars($membership) as $key=>$value )
			$this->$key = $value;
	}

	public function add(){
		global $wpdb;
		$table = $wpdb->prefix.'jm_memberships';

		if( $this->exists() )
			return FALSE;

		$data = get_object_vars($this);
		unset( $data['ID'] );

		if( !$wpdb->insert( $table, $data ) )
			return FALSE;

		$this->ID = $wpdb->insert_id;

		return TRUE;
	}

	public function update( $ID ){
		global $wpdb;
		$table = $wpdb->prefix.'jm_memberships';

		$ID = (int) $ID;

		if( $this->exists() ):
			$data = get_object_vars($this);
			unset( $data['ID'] );

			$where = array( 'ID' => $ID );

			if( !$wpdb->update( $table, $data, $where ) )
				return FALSE;

			return TRUE;
		endif;

		return FALSE;
	}

	public static function delete( $ID ){
		global $wpdb;
		$table = $wpdb->prefix.'jm_memberships';

		$ID = (int) $ID;

		if( get_package( $ID ) == NULL )
			return FALSE;

		$query = "DELETE FROM $table WHERE ID = %d";

		if( !$wpdb->query( $wpdb->prepare( $query, $ID ) ) )
			return FALSE;

		return TRUE;
	}

	private function exists(){
		global $wpdb;
		$table = $wpdb->prefix.'jm_memberships';

		$query = "SELECT COUNT(*) FROM $table WHERE name = %s";
		$count = $wpdb->get_var( $wpdb->prepare( $query, $this->name ) );

		if( $count > 0 )
			return TRUE;

		return FALSE;
	}
}
