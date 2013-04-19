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

function process_email_transaction( $data ){
	global $jmembers_settings;

	if( $data['package_id'] == 0 ):
		$product_name = get_package_name( $data['package_id'] );
	else:
		$post         = get_post( $data['post_id'] );
		$product_name = $post->post_tile;
	endif;

	$replacement = array(
		$product_name,
		$data['user_email'],
		$data['first_name'],
		$data['last_name'],
		$data['address'],
		$data['city'],
		$data['state'],
		$data['zip'],
		$data['country']
	);

	$pattern = array(
		'/{product_name}/',
		'/{user_email}/',
		'/{first_name}/',
		'/{last_name}/',
		'/{address}/',
		'/{city}/',
		'/{state}/',
		'/{zip}/',
		'/{country}/',
	);

	$subject = $jmembers_settings['email_transaction_subject'];
	$subject = preg_replace($pattern, $replacement, $subject);

	$message = $jmembers_settings['email_transaction'];
	$message = preg_replace($pattern, $replacement, $message);

	return wp_mail( $data['user_email'], $subject, $message );
}

function process_user_next_expiration( $package_id ){
	$package = get_package( $package_id );

	$time = strtotime(current_time( 'mysql' ));

	switch( $package->duration_type ){
		case 1:
			for( $i = 1; $i <= $package->duration; $i++ ){
				$time = strtotime( "+1 year", $time );
			}
			break;
		case 2:
			for( $i = 1; $i <= $package->duration; $i++ ){
				$time = strtotime( "+1 month", $time );
			}
			break;
		case 3:
			for( $i = 1; $i <= $package->duration; $i++ ){
				$time = strtotime( "+1 week", $time );
			}
			break;
		case 4:
			for( $i = 1; $i <= $package->duration; $i++ ){
				$time = strtotime( "+1 day", $time );
			}
			break;
	}

	return $time;
}
?>