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

	$message = $jmembers_settings['email_user_registration'];
	$message = preg_replace($pattern, $replacement, $message);

	$subject = $jmembers_settings['email_user_registration_subject'];
	$subject = preg_replace($pattern, $replacement, $subject);

	return wp_mail( $userdata['user_email'], $subject , $message );
}

function process_email_transaction_confirmation( $data ){

}

function process_email_transaction_decline( $data ){

}
?>