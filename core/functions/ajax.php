<?php
Class JM_Ajax{
	public function __construct(){
		add_action( 'wp_ajax_nopriv_jmembers_check_user', array( &$this, 'check_user' ) );
		add_action( 'wp_ajax_nopriv_jmembers_check_email', array( &$this, 'check_email' ) );
		add_action( 'wp_ajax_nopriv_jmembers_check_pass', array( &$this, 'check_pass' ) );
		add_action( 'wp_ajax_nopriv_jmembers_user_registration', array( &$this, 'user_registration' ) );
		add_action( 'wp_ajax_jmembers_transaction', array( &$this, 'transaction' ) );
		add_action( 'wp_ajax_jmembers_member_update', array( &$this, 'member_update' ) );
	}

	public function check_user(){
		$user_login = sanitize_user( $_POST['user_login'], true );

		$args = array(
			'search' => $user_login
		);

		$users = get_users( $args );

		if( $users == NULL ){
			if( $user_login == $_POST['user_login'] ){
				$match   = 1;
				$image   = JMEMBERS_URL.'core/shortcodes/images/accept.png';
				$message = __( 'Valid.', 'jmembers' );
			} else {
				$match   = 2;
				$image   = JMEMBERS_URL.'core/shortcodes/images/error.png';
				$message = __( 'Username changed.', 'jmembers' );
			}
		} else {
			$match   = 0;
			$image   = JMEMBERS_URL.'core/shortcodes/images/cancel.png';
			$message = __( 'Already taken.', 'jmembers' );
		}

		$response = array(
			'match'      => $match,
			'user_login' => $user_login,
			'image'      => $image,
			'message'    => $message
		);

		echo json_encode($response);

		die();
	}

	public function check_email(){
		$user_email = sanitize_email($_POST['user_email']);

		$args = array(
			'search' => $user_email
		);

		$users = get_users( $args );

		if( $users == NULL ){
			if( $user_email == $_POST['user_email'] ){
				$match = 1;
				$image = JMEMBERS_URL.'core/shortcodes/images/accept.png';
				$message = __( 'Valid email address.', 'jmembers' );
			} else {
				$match = 2;
				$image = JMEMBERS_URL.'core/shortcodes/images/error.png';
				$message = __( 'Email address changed.', 'jmembers' );
			}
		} else {
			$match = 0;
			$image = JMEMBERS_URL.'core/shortcodes/images/cancel.png';
			$message = __( 'Invalid email address.', 'jmembers' );
		}

		$response = array(
			'match'      => $match,
			'user_email' => $user_email,
			'image'      => $image,
			'message'    => $message
		);

		echo json_encode($response);

		die();
	}

	public function check_pass(){
		$match = (int) $_POST['match'];

		if( $match == 1 ){
			$image = JMEMBERS_URL.'core/shortcodes/images/accept.png';
			$message = __( 'Password match.', 'jmembers' );
		} else {
			$image = JMEMBERS_URL.'core/shortcodes/images/cancel.png';
			$message = __('Password don\'t match.', 'jmembers');
		}

		$response = array(
			'match'   => $match,
			'image'   => $image,
			'message' => $message
		);

		echo json_encode($response);

		die();
	}

	public function user_registration(){
		if( empty( $_POST ) || !wp_verify_nonce( $_POST['jmembers_nonce'], 'user_registration' ) ):
			die( __( 'Error passing security check.', 'jmembers' ) );
		endif;

		global $jmembers_settings;

		// Response array
		$response            = array();
		$response['message'] = '';

		// Userdata to array
		$userdata = array(
			'user_login' => sanitize_user( $_POST['user_login'], true ),
			'user_email' => sanitize_email( $_POST['user_email'] ),
			'user_pass'  => $_POST['user_pass'],
			'first_name' => sanitize_text_field( $_POST['first_name'] ),
			'last_name'  => sanitize_text_field( $_POST['last_name'] ),
			'address'    => sanitize_text_field( $_POST['address'] ),
			'city'       => sanitize_text_field( $_POST['city'] ),
			'state'      => sanitize_text_field( $_POST['state'] ),
			'zip'        => sanitize_text_field( $_POST['zip'] ),
			'country'    => sanitize_text_field( $_POST['country'] )
		);

		// Add Userdata to response array
		$response['userdata'] = $userdata;
		// Add Payment Processor to response array
		$response['payment_processor'] = $_POST['payment_processor'];

		// Verify selected package
		if( !isset( $_POST['package_id'] ) ):
			$response['user_id'] = 0;
			$response['message'] = __( 'You didn\'t select any package.', 'jmembers' );
			
			die( json_encode( $response ) );
		endif;

		// Add Package to response array
		$response['package_id'] = (int) $_POST['package_id'];

		// Get package
		$package = get_package( $_POST['package_id'] );

		if( $package == NULL ):
			$response['user_id'] = 0;
			$response['message'] = __( 'You selected an invalid package.', 'jmembers' );
			
			die( json_encode( $response ) );
		endif;

		$package_display = unserialize( $package->display );

		if( $package_display['display_registration'] == 0 ):
			$response['user_id'] = 0;
			$response['message'] = __( 'You can\'t purchase this package.', 'jmembers' );
			
			die( json_encode( $response ) );
		endif;
		// End: Verify selected package

		$user_id = wp_insert_user( $userdata );

		if( is_wp_error($user_id) ):
			$response['user_id'] = 0;
			$response['message'] = $user_id->get_error_message();

			die( json_encode( $response ) );
		endif;

		// Add User ID to response array
		$response['user_id'] = $user_id;

		add_user_meta( $user_id, '_address', $userdata['address'], TRUE );
		add_user_meta( $user_id, '_city', $userdata['city'], TRUE );
		add_user_meta( $user_id, '_state', $userdata['state'], TRUE );
		add_user_meta( $user_id, '_zip', $userdata['zip'], TRUE );
		add_user_meta( $user_id, '_country', $userdata['country'], TRUE );

		if( process_email_user_registration( $userdata ) == FALSE ):
			$response['message'] = __( 'Error trying to email you.', 'jmembers' );
		endif;

		$user = wp_signon( array(
				'user_login'    => $userdata['user_login'],
				'user_password' => $userdata['user_pass'],
				'remember'      => false
			) , true );

		$response['url'] = $jmembers_settings['page_transactions'].'?package='.$response['package_id'].'&action=new&processor='.$response['payment_processor'];
		
		die( json_encode( $response ) );
	}

	public function transaction(){
		if( empty( $_POST ) || !wp_verify_nonce( $_POST['jmembers_nonce'], 'transaction' ) ):
			die( __( 'Error passing security check.', 'jmembers' ) );
		endif;


		$data = array(
			'user_id'        => intval( $_POST['user_id'] ),
			'package_id'     => intval( $_POST['package_id'] ),
			'post_id'        => intval( $_POST['post_id'] ),
			'creditcardtype' => sanitize_text_field( $_POST['creditcardtype'] ),
			'acct'           => $_POST['acct'],
			'cvv2'           => $_POST['cvv2'],
			'expdate'        => $_POST['expdate'],
			'user_email'     => sanitize_email( $_POST['user_email'] ),
			'first_name'     => sanitize_text_field( $_POST['first_name'] ),
			'last_name'      => sanitize_text_field( $_POST['last_name'] ),
			'address'        => sanitize_text_field( $_POST['address'] ),
			'city'           => sanitize_text_field( $_POST['city'] ),
			'state'          => sanitize_text_field( $_POST['state'] ),
			'zip'            => sanitize_text_field( $_POST['zip'] ),
			'country'        => sanitize_text_field( $_POST['country'] )
		);

		$response = array();
		$response['data'] = $data;

		if( !has_profile_id() ):

			if( $data['package_id'] != 0 ): // Is a package
				$package = get_package( $data['package_id'] );

				// Validate package
				if( $package == NULL ):
					$response['success'] = 0;
					$response['message'] = __( 'Are you trying to buy an invalid package?' , 'jmembers' );

					die( json_encode( $response ) );
				endif;

				if( is_package_recurring( $package->ID ) ):
					$profile                   = new PayPalPro_CreateProfile();
					$profile->creditcardtype   = $data['creditcardtype'];
					$profile->acct             = $data['acct'];
					$profile->expdate          = $data['expdate'];
					$profile->cvv2             = $data['cvv2'];
					// Billing Information
					$profile->firstname        = $data['first_name'];
					$profile->lastname         = $data['last_name'];
					$profile->address          = $data['address'];
					$profile->city             = $data['city'];
					$profile->state            = $data['state'];
					$profile->country          = $data['country'];
					$profile->zip              = $data['zip'];
					// Package information
					$profile->amt              = $package->price;
					$profile->currencycode     = 'USD';
					$profile->desc             = get_package_name( $package->ID );
					$profile->billingperiod    = get_package_period( $package->ID );
					$profile->billingfrequency = $package->duration;
					$profile->profilestartdate = date( 'Y-m-d', strtotime(current_time( 'mysql' )) ).'T00:00:00Z';

					$paypalpro = new JM_Payment_PayPalPro();
					$paypalpro_result = $paypalpro->process( 'CreateRecurringPaymentsProfile', $profile->toString() ); 
					
					if( !$paypalpro_result ):
						$response['success'] = 0;
						$response['message'] = __( 'An error ocurred trying to process your credit card, please go back and a try again.', 'jmembers' );

						die( json_encode( $response ) );
					endif;

					$transaction             = new JMembers_Transaction();
					$transaction->user_id    = $data['user_id'];
					$transaction->package_id = $data['package_id'];
					$transaction->datetime   = strtotime( current_time( 'mysql' ) );
					$transaction->data       = serialize( $paypalpro_result );
					$transaction->add();

					$payment = array(
						'processor'      => 'pppro',
						'profile_id'     => $paypalpro_result['PROFILEID'],
						'profile_status' => $paypalpro_result['PROFILESTATUS']
					);

					$member                    = new JMembers_Member();
					$member->user_id           = $data['user_id'];
					$member->package_id        = $data['package_id'];
					$member->status            = 'Active';
					$member->datetime_packjoin = strtotime( current_time( 'mysql' ) );
					$member->datetime_expire   = process_user_next_expiration( $data['package_id'] );
					$member->payment           = serialize( $payment );
					$member->save();

					process_email_transaction( $data );

					$response['success'] = 1;
					$response['message'] = __( 'Payment process successfuly.', 'jmembers' );

					die( json_encode($response) );
				endif;

			elseif( $data['post_id'] != 0 ): // is a post
				$post = get_post( $data['post_id'] );

				// Validate post
				if( $post == NULL ):
					$response['success'] = 0;
					$response['message'] = __( 'Are you trying to buy an invalid post?' , 'jmembers' );						

					die( json_encode( $response ) );
				endif;

				// Process Payment
			endif; // END - is a post
		endif;
	}

	public function member_update(){
		if( empty( $_POST ) || !wp_verify_nonce( $_POST['jmembers_nonce'], 'member_update' ) ):
			die( __( 'Error passing security check.', 'jmembers' ) );
		endif;

		global $jmembers_settings;
		$response = array();

		$datetime_packjoin = date_parse_from_format('j/n/Y', $_POST['datetime_packjoin']);
		$datetime_packjoin = mktime(0, 0, 0, $datetime_packjoin['month'], $datetime_packjoin['day'], $datetime_packjoin['year']);
		$datetime_expire = date_parse_from_format('j/n/Y', $_POST['datetime_expire']);
		$datetime_expire = mktime(0, 0, 0, $datetime_expire['month'], $datetime_expire['day'], $datetime_expire['year']);

		$payment = array(
			'processor'      => sanitize_text_field( $_POST['payment_processor'] ),
			'profile_id'     => $_POST['payment_profile_id'],
			'profile_status' => $_POST['payment_profile_status']
		);
		$payment = serialize($payment);

		$data = array(
			'_user'              => intval( $_POST['user'] ),
			'_status'            => sanitize_text_field($_POST['status']),
			'_package_id'        => intval( $_POST['package_id'] ),
			'_datetime_packjoin' => $datetime_packjoin,
			'_datetime_expire'   => $datetime_expire,
			'_payment'           => $payment
		);
		$response['data'] = $data;

		if( !get_user_by( 'id', $data['_user'] ) ):
			$response['success'] = 0;
			$response['message'] = __( 'Invalid user.', 'jmembers' );

			die( json_encode($response) );
		endif;

		foreach( $data as $key => $value ):
			if( $key != '_user' ):
				update_user_meta( $data['_user'], $key, $value );
			endif;
		endforeach;

		$response['success'] = 1;
		$response['message'] = __('User updated successfuly', 'jmembers');

		if( $response['success'] == 1 )
			$response['url'] = $jmembers_settings['page_transactions_success'];
		else
			$response['url'] = $jmembers_settings['page_transactions_failure'];

		die( json_encode($response) );
	}
}
new JM_Ajax();