<?php
function is_administrator(){
	$user_id = get_current_user_id();

	if( $user_id == 0 )
		return FALSE;

	if( get_userdata( $user_id )->user_level == 10 )
		return TRUE;

	return FALSE;
}

function is_user_expired( $user_id = null ){
	if( $user_id == null )
		$user_id = get_current_user_id();

	if( $user_id == null || $user_id == 0 )
		return TRUE;

	$datetime_expire = get_user_meta( $user_id, '_datetime_expire', true );

	if( $datetime_expire == '' )
		return TRUE;

	$datetime_current = strtotime( current_time( 'mysql' ) );

	if( $datetime_current > $datetime_expire )
		return TRUE;

	return FALSE;
}

function is_package_available_register( $package_id ){
	$package = get_package( $package_id );

	$package_display = unserialize($package->display);

	if( $package_display['display_registration'] == 1 )
		return TRUE;

	return FALSE;
}

function is_package_available_upgrade( $package_id ){
	$package = get_package( $package_id );

	$package_display = unserialize($package->display);

	if( $package_display['display_upgrade'] == 1 )
		return TRUE;

	return FALSE;	
}

function is_package_available_extend( $package_id ){
	$package = get_package( $package_id );

	if( $package == NULL )
		return FALSE;

	$package_display = unserialize($package->display);

	if( $package_display['display_extend'] == 1 )
		return TRUE;

	return FALSE;
}

function is_processor_for_package( $package_id, $processor ){
	$package = get_package( $package_id );

	if( $package == NULL )
		return FALSE;

	$package_payment = unserialize( $package->payment );

	if( isset( $package_payment[$processor] ) && $package_payment[$processor] == 1 )
		return TRUE;

	return FALSE;
}
?>