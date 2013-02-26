<?php
function get_memberships(){
	global $wpdb;
	$table = $wpdb->prefix.'jm_memberships';

	$query = "SELECT * FROM $table ORDER BY ID";

	return $wpdb->get_results( $query );
}

function get_membership( $ID ){
	global $wpdb;
	$table = $wpdb->prefix.'jm_memberships';

	$query = "SELECT * FROM $table WHERE ID = %d";

	return $wpdb->get_row( $wpdb->prepare( $query, $ID ) );
}

function get_packages( $args = NULL ){
	global $wpdb;
	$table = $wpdb->prefix.'jm_packages';

	$defaults = array(
		'membership' => 0,
		'count'      => -1
	);
	$args = wp_parse_args( $args, $defaults );
	extract($args, EXTR_SKIP);
	
	if( $membership == 0 && $count == -1 )
		return $wpdb->get_results( "SELECT * FROM $table ORDER BY ID" );

	if( $count == -1 ):
		$query = "SELECT * FROM $table WHERE membership_id = %d ORDER BY ID";
		return $wpdb->get_results( $wpdb->prepare( $query, $membership ) );
	endif;

	$query = "SELECT * FROM $table WHERE membership_id = %d LIMIT 0, %d ORDER BY ID";
	return $wpdb->get_results( $wpdb->prepare( $query, $membership, $count ) );
}