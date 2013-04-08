<?php
function has_bought_post( $post_id, $user_id ){
	global $wpdb;
	$table = $wpdb->prefix.'jm_purchase_posts';

	$user_id = (int) $user_id;
	$post_id = (int) $post_id;

	$query = "SELECT COUNT(*) FROM $table WHERE post_id = %d AND user_id = %d";

	$count = $wpdb->get_var( $wpdb->prepare( $query, $post_id, $user_id ) );

	if( $count > 0 )
		return TRUE;

	return FALSE;
}

function has_profile_id( $user_id = null ){
	if( $user_id == null )
		$user_id = get_current_user_id();

	if( $user_id == null || $user_id == 0 )
		return FALSE;

	$profile_id = get_user_meta( $user_id, '_profile_id', true );

	if( $profile_id == '' )
		return FALSE;

	return TRUE;
}