<?php
Class JMembers_Membership {
	public $ID;
	private $name;

	public function __construct( $name ){
		$this->name = $name;
	}

	public function add(){
		global $wpdb;
		$table = $wpdb->prefix.'jm_memberships';

		if( $this->exists() )
			return FALSE;

		$data = array(
			'name' => $this->name
		);

		$format = array( '%s' );

		if( !$wpdb->insert( $table, $data, $format ) )
			return FALSE;

		$this->ID = $wpdb->insert_id;

		return TRUE;
	}

	public function update( $ID ){
		global $wpdb;
		$table = $wpdb->prefix.'jm_memberships';

		if( $this->exists( $ID ) ):
			$data = array(
				'name' => $this->name
			);

			$where = array(
				'ID' => $ID
			);

			$format = array( '%s' );

			if( !$wpdb->update( $table, $data, $where, $format ) )
				return FALSE;

			return TRUE;
		endif;

		return FALSE;
	}

	public static function delete( $ID ){
		global $wpdb;
		$table = $wpdb->prefix.'jm_memberships';

		$query = "DELETE FROM $table WHERE ID = %d;";

		$result = $wpdb->query( $wpdb->prepare( $query, $ID ) );

		if( !$result || $result == 0 )
			return FALSE;

		return TRUE;
	}

	private function exists($ID = NULL){
		global $wpdb;
		$table = $wpdb->prefix.'jm_memberships';

		if( $ID == NULL ):
			$query = "SELECT COUNT(*) FROM $table WHERE name = %s";
			$count = $wpdb->get_var( $wpdb->prepare( $query, $this->name ) );
		else:
			$query = "SELECT COUNT(*) FROM $table WHERE ID = %d";
			$count = $wpdb->get_var( $wpdb->prepare( $query, $ID ) );
		endif;

		if( $count > 0 )
			return TRUE;

		return FALSE;
	}
}