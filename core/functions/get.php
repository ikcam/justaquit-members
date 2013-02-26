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

function get_package( $ID ){
	global $wpdb;
	$table = $wpdb->prefix.'jm_packages';

	$query = "SELECT * FROM $table WHERE ID = %d";

	return $wpdb->get_row( $wpdb->prepare( $query, $ID ) );
}

function get_package_name( $ID ){
	$package = get_package( $ID );
	$membership = get_membership( $package->membership_id );

	$output = $membership->name.' - ';
	$output .= $package->price.' USD ';
	
	if( $package->duration_type == 0 ):
		$output .= __( 'per Lifetime', 'jmembers' );
		return $output;
	endif;

	$output .= __( 'per', 'jmembers' );
	$output .= ' '.$package->duration.' ';

	switch( $package->duration_type ){
		case 1:
			$output .= __( 'years', 'jmembers' );
			break;
		case 2:
			$output .= __( 'months', 'jmembers' );
			break;
		case 3:
			$output .= __( 'weeks', 'jmembers' );
			break;
		case 4:
			$output .= __( 'days', 'jmembers' );
			break;
	}

	$output .= ' - ';

	switch( $package->billing	){
		case 0:
			$output .= __( 'Auto Renew', 'jmembers' );
			break;
		case 1:
			$output .= __( 'One Time', 'jmembers' );
			break;
	}

	return $output;
}