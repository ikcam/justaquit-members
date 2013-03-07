<?php
function has_bought_post( $post_id, $user_id ){
	global $wpdb;
	$table = $wpdb->prefix.'jm_puchase_posts';

	$user_id = (int) $user_id;
	$post_id = (int) $post_id;

	$query = "SELECT COUNT (*) FROM $table WHERE post_id = %d AND user_id = %d";

	$count = $wpdb->get_var( $wpdb->prepare( $query, $post_id, $user_id ) );

	if( $count > 0 )
		return TRUE;

	return FALSE;
}