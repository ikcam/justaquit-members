<?php
Class JM_Ajax{
	public function __construct(){
		add_action( 'wp_ajax_nopriv_jmembers_check_user', array( &$this, 'check_user' ) );
		add_action( 'wp_ajax_nopriv_jmembers_check_email', array( &$this, 'check_email' ) );
		add_action( 'wp_ajax_nopriv_jmembers_check_pass', array( &$this, 'check_pass' ) );
		add_action( 'wp_ajax_nopriv_jmembers_user_registration', array( &$this, 'user_registration' ) );
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
		if( !empty( $_POST ) && wp_verify_nonce( $_POST['jmembers_nonce'], 'user_registration' ) ):
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

			wp_signon( array(
					'user_login'    => $userdata['user_login'],
					'user_password' => $userdata['user_pass'],
					'remember'      => false
				) , true );

			$response['url'] = $jmembers_settings['page_transactions'].'?user_id='.$response['user_id'].'&package='.$response['package_id'].'&processor='.$response['payment_processor'];
			
			die( json_encode( $response ) );
		endif;

		die( __( 'Error passing security check.', 'jmembers' ) );
	}

	public function transaction(){
		if( !empty($_POST) && wp_verify_nonce( $_POST['jmembers_nonce'], 'transaction' ) ):

		endif;

		die( __( 'Error passing security check.', 'jmembers' ) );
	}
}
new JM_Ajax();