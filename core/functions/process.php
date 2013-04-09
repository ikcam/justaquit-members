<?php
function process_email_user_registration( $userdata ){
	global $jmembers_settings;

	$pattern = array(
		'/{user_login}/',
		'/{user_email}/',
		'/{user_pass}/',
		'/{first_name}/',
		'/{last_name}/',
		'/{address}/',
		'/{city}/',
		'/{state}/',
		'/{zip}/',
		'/{country}/'
	);

	$replacement = array();

	foreach( $userdata as $key => $value )
		$replacement[] = $value;

	$subject = $jmembers_settings['email_user_registration_subject'];
	$subject = preg_replace($pattern, $replacement, $subject);

	$message = $jmembers_settings['email_user_registration'];
	$message = preg_replace($pattern, $replacement, $message);

	return wp_mail( $userdata['user_email'], $subject, $message );
}

function process_user_next_expiration( $package_id ){
	$package = get_package( $package_id );

	$current_time = strtotime(current_time( 'mysql' ));

	switch( $package->duration_type ){
		case 1:
			for( $i = 1; $i <= $package->duration; $i++ ){
				$output = strtotime( "+1 year", $current_time );
			}
			break;
		case 2:
			for( $i = 1; $i <= $package->duration; $i++ ){
				$output = strtotime( "+1 month", $current_time );
			}
			break;
		case 3:
			for( $i = 1; $i <= $package->duration; $i++ ){
				$output = strtotime( "+1 week", $current_time );
			}
			break;
		case 4:
			for( $i = 1; $i <= $package->duration; $i++ ){
				$output = strtotime( "+1 day", $current_time );
			}
			break;
	}

	return $output;
}
?>