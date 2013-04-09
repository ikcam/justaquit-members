<?php
Class JM_Shotcode_Transactions{
	public function __construct(){
		add_shortcode( 'transactions', array( &$this, 'shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'scripts' ) );
	}

	public function scripts(){
		wp_register_script( 'jmembers-transactions', JMEMBERS_URL . 'core/shortcodes/javascript/transactions.js', array( 'jquery' ) );
		wp_enqueue_script( 'jmembers-transactions' );
	}

	public function shortcode(){
		$user_id = get_current_user_id();
		
		if( $user_id == 0 ):
			_e( 'You need to be login to access this page.', 'jmembers' );

			return;
		endif;

		$userdata = get_userdata( $user_id );

		$data = array(
			'user_id'    => $user_id,
			'user_login' => $userdata->user_login,
			'user_email' => $userdata->user_email,
			'first_name' => $userdata->user_firstname,
			'last_name'  => $userdata->user_lastname,
			'address'    => get_user_meta( $user_id, '_address', true ),
			'city'       => get_user_meta( $user_id, '_city', true ),
			'state'      => get_user_meta( $user_id, '_state', true ),
			'zip'        => get_user_meta( $user_id, '_zip', true ),
			'country'    => get_user_meta( $user_id, '_country', true )
		);

		if( isset( $_GET['package'] ) && isset( $_GET['action'] ) && isset( $_GET['processor'] ) ):
			$data['package_id'] = (int) $_GET['package'];

			if( $_GET['action'] == 'new' && is_package_available_register( $data['package_id'] ) ):
				if( !is_processor_for_package( $data['package_id'], $_GET['processor'] ) ):
					_e( 'Payment module not available for this package.', 'jmembers' );
					return;
				endif;
				
				get_payment_form( $_GET['processor'], $data );

				return;
			elseif( $_GET['action'] == 'upgrade' && is_package_available_upgrade( $data['package_id'] ) ):


				return;
			elseif( $_GET['action'] == 'extend' && is_package_available_extend( $data['package_id'] ) ):


				return;
			else:

				return;
			endif;
		elseif( isset( $_GET['post'] ) && isset( $_GET['processor'] ) ):
			$data['post_id'] = (int) $_GET['post_id'];

			if( $_GET['processor'] == '' ):

				return;
			elseif( $_GET['processor'] == '' ):

				return;
			elseif( $_GET['processor'] == '' ):

				return;
			else:

				return;
			endif;
		else:
			_e( 'So... why are you here?', 'jmembers' );
			return;
		endif;
	}
}
new JM_Shotcode_Transactions();