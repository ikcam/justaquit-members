<?php
Class JMembers_Page_Settings{
	public function __construct(){
		add_action( 'admin_menu', array( &$this, 'init' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'scripts' ) );
		add_action( 'admin_enqueue_styles', array( &$this, 'stylesheets' ) );
		add_action( 'admin_init', array( &$this, 'settings' ) );
	}

	public function init(){
		add_submenu_page( 'jmembers', __( 'Settings', 'jmembers' ), __( 'Settings', 'jmembers' ), 'manage_options', 'jmembers_settings', array( &$this, 'page' ) );
	}

	public function settings(){
		register_setting( 'jmembers', 'jmembers_settings', array( &$this, 'save' ) );
	}

	public function scripts(){
		wp_register_script( 'jmembers_page_settings', JMEMBERS_URL . 'core/javascript/page-settings.js', array( 'jquery-ui-tabs', 'jquery-ui-core', 'jquery' ) );
		wp_enqueue_script( 'jmembers_page_settings' );
		wp_enqueue_script( 'editor' );
    wp_enqueue_script( 'thickbox' );
		wp_enqueue_script( 'media-upload' );
	}

	public function stylesheets(){
    wp_enqueue_style( 'thickbox' );
	}

	public function page(){
		// Hide all content by default

?>
<div class="wrap">
	<h2><?php _e( 'Settings', 'jmembers' ) ?></h2>

	<form method="post" action="options.php">
		<?php settings_fields('jmembers'); ?>
<?php
	$settings = get_option( 'jmembers_settings' );
?>
		<div id="tabs-1">
			<ul>
				<li><a href="#tab-general"><?php _e( 'General', 'jmembers' ) ?></a></li>
				<li><a href="#tab-pages"><?php _e( 'Pages', 'jmembers' ) ?></a></li>
				<li><a href="#tab-messages"><?php _e( 'Messages', 'jmembers' ) ?></a></li>
				<li><a href="#tab-email"><?php _e( 'E-mail', 'jmembers' ) ?></a></li>
				<li><a href="#tab-payment"><?php _e( 'Payment', 'jmembers' ) ?></a></li>
			</ul>
			<div id="tab-general">
				<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><label for="hide_content"><?php _e( 'Hide content?', 'jmembers' ) ?></label></th>
						<td>
							<input type="radio" name="jmembers_settings[hide_content]" id="hide_content_1" value="1" <?php if( $settings['hide_content'] == 1 ) echo 'checked'; ?> /> <label for="hide_content_1"><?php _e( 'Yes', 'jmembers' ) ?></label>
							&nbsp;
							<input type="radio" name="jmembers_settings[hide_content]" id="hide_content_0" value="0" <?php if( $settings['hide_content'] == 0 ) echo 'checked'; ?> /> <label for="hide_content_0"><?php _e( 'No', 'jmembers' ) ?></label>
						</td>
					</tr>
					
				</tbody>
				</table>
			</div>

			<div id="tab-pages">
				<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><label for="page_registration"><?php _e( 'User Registration page', 'jmembers' ) ?></label></th>
						<td>
							<input class="regular-text" type="text" name="jmembers_settings[page_registration]" id="page_registration" value="<?php echo $settings['page_registration'] ?>" />
							<span class="description"><?php _e( 'URL for the User Registration page' , 'jmembers' ) ?></span>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="page_transactions"><?php _e( 'Transactions page', 'jmembers' ) ?></label></th>
						<td>
							<input class="regular-text" type="text" name="jmembers_settings[page_transactions]" id="page_transactions" value="<?php echo $settings['page_transactions'] ?>" />
							<span class="description"><?php _e( 'URL for the  Transactions page' , 'jmembers' ) ?></span>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="page_terms"><?php _e( 'Terms and Conditions page', 'jmembers' ) ?></label></th>
						<td>
							<input class="regular-text" type="text" name="jmembers_settings[page_terms]" id="page_terms" value="<?php echo $settings['page_terms'] ?>" />
							<span class="description"><?php _e( 'URL for the Terms and Coditions page' , 'jmembers' ) ?></span>
						</td>
					</tr>
				</tbody>
				</table>
			</div>

			<div id="tab-messages">
				<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><label for="message_hide_content"><?php _e( 'Hide content message:', 'jmembers' ) ?></label></th>
						<td>
							<?php wp_editor($settings['message_hide_content'], 'message_hide_content', array( 'textarea_name' => 'jmembers_settings[message_hide_content]', 'teeny' => true ) 	); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="message_transaction_error"><?php _e( 'Transaction error message:', 'jmembers' ) ?></label></th>
						<td>
							<?php wp_editor($settings['message_transaction_error'], 'message_transaction_error', array( 'textarea_name' => 'jmembers_settings[message_transaction_error]', 'teeny' => true ) 	); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="message_transaction_correct"><?php _e( 'Transaction successful message:', 'jmembers' ) ?></label></th>
						<td>
							<?php wp_editor($settings['message_transaction_correct'], 'message_transaction_correct', array( 'textarea_name' => 'jmembers_settings[message_transaction_correct]', 'teeny' => true ) 	); ?>
						</td>
					</tr>
				</tbody>
				</table>
			</div>
			<div id="tab-email">
				<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><label for="email_user_registration_subject"><?php _e( 'Subject new user email:' ) ?></label></th>
						<td>
							<input class="regular-text" type="text" name="jmembers_settings[email_user_registration_subject]" id="email_user_registration_subject" value="<?php echo $settings['email_user_registration_subject'] ?>" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="email_user_registration"><?php _e( 'New user email:' ) ?></label></th>
						<td>
							<?php wp_editor($settings['email_user_registration'], 'email_user_registration', array( 'textarea_name' => 'jmembers_settings[email_user_registration]', 'teeny' => true ) 	); ?>
						</td>
					</tr>
				</tbody>
				</table>
			</div>
			<div id="tab-payment">
				<div id="accordion-proccesors">
					<h3><?php _e( 'PayPal Payments Pro', 'jmembers' ) ?></h3>
					<div>
						<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row"><label for="payment_pppro_active"><?php _e( 'Active', 'jmembers' ) ?></label></th>
								<td>
									<input type="radio" name="jmembers_settings[payment_pppro_active]" id="payment_pppro_active_1" <?php if( $settings['payment_pppro_active'] == 1 ) echo 'checked'; ?> value="1" />
									<label for="payment_pppro_active_1"><?php _e( 'Yes', 'jmembers' ) ?></label>
									<input type="radio" name="jmembers_settings[payment_pppro_active]" id="payment_pppro_active_0" <?php if( $settings['payment_pppro_active'] == 0 ) echo 'checked'; ?> value="0" />
									<label for="payment_pppro_active_0"><?php _e( 'No', 'jmembers' ) ?></label>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><label for="payment_pppro_sandbox"><?php _e( 'Test mode', 'jmembers' ) ?></label></th>
								<td>
									<input type="radio" name="jmembers_settings[payment_pppro_sandbox]" id="payment_pppro_sandbox_1" value="1" <?php if( $settings['payment_pppro_sandbox'] == 1 ) echo 'checked'; ?> />
									<label for="payment_pppro_sandbox_1"><?php _e( 'Yes', 'jmembers' ) ?></label>
									<input type="radio" name="jmembers_settings[payment_pppro_sandbox]" id="payment_pppro_sandbox_0" value="0" <?php if( $settings['payment_pppro_sandbox'] == 0 ) echo 'checked'; ?> />
									<label for="payment_pppro_sandbox_0"><?php _e( 'No', 'jmembers' ) ?></label>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><label for="payment_pppro_email"><?php _e( 'Email', 'jmembers' ) ?></label></th>
								<td>
									<input class="regular-text" type="text" name="jmembers_settings[payment_pppro_email]" id="payment_pppro_email" value="<?php echo $settings['payment_pppro_email'] ?>" />
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><label for="payment_pppro_username"><?php _e( 'Username', 'jmembers' ) ?></label></th>
								<td>
									<input class="regular-text" type="text" name="jmembers_settings[payment_pppro_username]" id="payment_pppro_username" value="<?php echo $settings['payment_pppro_username'] ?>" />
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><label for="payment_pppro_password"><?php _e( 'Password', 'jmembers' ) ?></label></th>
								<td>
									<input class="regular-text" type="text" name="jmembers_settings[payment_pppro_password]" id="payment_pppro_password" value="<?php echo $settings['payment_pppro_password'] ?>" />
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><label for="payment_pppro_signature"><?php _e( 'Signature', 'jmembers' ) ?></label></th>
								<td>
									<input class="regular-text" type="text" name="jmembers_settings[payment_pppro_signature]" id="payment_pppro_signature" value="<?php echo $settings['payment_pppro_signature'] ?>" />
								</td>
							</tr>
							<tr vlaign="top">
								<th scope="row"><label for="payment_pppro_ppp"><?php _e( 'Pay Per Posts', 'jmembers' ) ?></label></th>
								<td>
									<input type="radio" value="1" name="jmembers_settings[payment_pppro_ppp]" id="payment_pppro_ppp_1" <?php if( $settings['payment_pppro_ppp'] == 1 ) echo 'checked'; ?> />
									<label for="payment_pppro_ppp_0"><?php _e( 'Yes', 'jmembers' ) ?></label>
									<input type="radio" value="0" name="jmembers_settings[payment_pppro_ppp]" id="payment_pppro_ppp_0" <?php if( $settings['payment_pppro_ppp'] == 0 ) echo 'checked'; ?> />
									<label for="payment_pppro_ppp_0"><?php _e( 'No', 'jmembers' ) ?></label>
								</td>
							</tr>
							<tr vlaign="top">
								<th scope="row"><label for="payment_pppro_memberships"><?php _e( 'Memberships', 'jmembers' ) ?></label></th>
								<td>
									<input type="radio" value="1" name="jmembers_settings[payment_pppro_memberships]" id="payment_pppro_memberships_1" <?php if( $settings['payment_pppro_memberships'] == 1 ) echo 'checked'; ?> />
									<label for="payment_pppro_memberships_0"><?php _e( 'Yes', 'jmembers' ) ?></label>
									<input type="radio" value="0" name="jmembers_settings[payment_pppro_memberships]" id="payment_pppro_memberships_0" <?php if( $settings['payment_pppro_memberships'] == 0 ) echo 'checked'; ?> />
									<label for="payment_pppro_memberships_0"><?php _e( 'No', 'jmembers' ) ?></label>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><label for="payment_pppro_name"><?php _e( 'Name' , 'jmembers' ) ?></label></th>
								<td>
									<input class="regular-text" type="text" name="jmembers_settings[payment_pppro_name]" id="payment_pppro_name" value="<?php echo $settings['payment_pppro_name'] ?>" />
								</td>
							</tr>
						</tbody>
						</table>
					</div>
					<h3><?php _e( 'PayPal Payments Standard', 'jmembers' ) ?></h3>
					<div>
						<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row"><label for="payment_ppstandard_active"><?php _e( 'Active', 'jmembers' ) ?></label></th>
								<td>
									<input type="radio" name="jmembers_settings[payment_ppstandard_active]" id="payment_ppstandard_active_1" <?php if( $settings['payment_ppstandard_active'] == 1 ) echo 'checked'; ?> value="1" />
									<label for="payment_ppstandard_active_1"><?php _e( 'Yes', 'jmembers' ) ?></label>
									<input type="radio" name="jmembers_settings[payment_ppstandard_active]" id="payment_ppstandard_active_0" <?php if( $settings['payment_ppstandard_active'] == 0 ) echo 'checked'; ?> value="0" />
									<label for="payment_ppstandard_active_0"><?php _e( 'No', 'jmembers' ) ?></label>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><label for="payment_ppstandard_sandbox"><?php _e( 'Test mode', 'jmembers' ) ?></label></th>
								<td>
									<input type="radio" name="jmembers_settings[payment_ppstandard_sandbox]" id="payment_ppstandard_sandbox_1" value="1" <?php if( $settings['payment_ppstandard_sandbox'] == 1 ) echo 'checked'; ?> /><label for="payment_ppstandard_sandbox_1"><?php _e( 'Yes', 'jmembers' ) ?></label>
									<input type="radio" name="jmembers_settings[payment_ppstandard_sandbox]" id="payment_ppstandard_sandbox_0" value="0" <?php if( $settings['payment_ppstandard_sandbox'] == 0 ) echo 'checked'; ?> /><label for="payment_ppstandard_sandbox_0"><?php _e( 'No', 'jmembers' ) ?></label>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><label for="payment_ppstandard_email"><?php _e( 'Email', 'jmembers' ) ?></label></th>
								<td>
									<input class="regular-text" type="text" name="jmembers_settings[payment_ppstandard_email]" id="payment_ppstandard_email" value="<?php echo $settings['payment_ppstandard_email'] ?>" />
								</td>
							</tr>
							<tr vlaign="top">
								<th scope="row"><label for="payment_ppstandard_ppp"><?php _e( 'Pay Per Posts', 'jmembers' ) ?></label></th>
								<td>
									<input type="radio" value="1" name="jmembers_settings[payment_ppstandard_ppp]" id="payment_ppstandard_ppp_1" <?php if( $settings['payment_ppstandard_ppp'] == 1 ) echo 'checked'; ?> />
									<label for="payment_ppstandard_ppp_0"><?php _e( 'Yes', 'jmembers' ) ?></label>
									<input type="radio" value="0" name="jmembers_settings[payment_ppstandard_ppp]" id="payment_ppstandard_ppp_0" <?php if( $settings['payment_ppstandard_ppp'] == 0 ) echo 'checked'; ?> />
									<label for="payment_ppstandard_ppp_0"><?php _e( 'No', 'jmembers' ) ?></label>
								</td>
							</tr>
							<tr vlaign="top">
								<th scope="row"><label for="payment_ppstandard_memberships"><?php _e( 'Memberships', 'jmembers' ) ?></label></th>
								<td>
									<input type="radio" value="1" name="jmembers_settings[payment_ppstandard_memberships]" id="payment_ppstandard_memberships_1" <?php if( $settings['payment_ppstandard_memberships'] == 1 ) echo 'checked'; ?> />
									<label for="payment_ppstandard_memberships_0"><?php _e( 'Yes', 'jmembers' ) ?></label>
									<input type="radio" value="0" name="jmembers_settings[payment_ppstandard_memberships]" id="payment_ppstandard_memberships_0" <?php if( $settings['payment_ppstandard_memberships'] == 0 ) echo 'checked'; ?> />
									<label for="payment_ppstandard_memberships_0"><?php _e( 'No', 'jmembers' ) ?></label>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><label for="payment_ppstandard_name"><?php _e( 'Name' , 'jmembers' ) ?></label></th>
								<td>
									<input class="regular-text" type="text" name="jmembers_settings[payment_ppstandard_name]" id="payment_ppstandard_name" value="<?php echo $settings['payment_ppstandard_name'] ?>" />
								</td>
							</tr>
						</tbody>
						</table>
					</div>
					<h3><?php _e( '1Shopping Cart', 'jmembers' ) ?></h3>
					<div>
						<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row"><label for="payment_1sc_active"><?php _e( 'Active', 'jmembers' ) ?></label></th>
								<td>
									<input type="radio" value="1" name="jmembers_settings[payment_1sc_active]" id="payment_1sc_active_1" <?php if( $settings['payment_1sc_active'] == 1 ) echo 'checked'; ?> />
									<label for="payment_1sc_active_1"><?php _e( 'Yes', 'jmembers' ) ?></label>
									<input type="radio" value="0" name="jmembers_settings[payment_1sc_active]" id="payment_1sc_active_0" <?php if( $settings['payment_1sc_active'] == 0 ) echo 'checked'; ?> />
									<label for="payment_1sc_active_0"><?php _e( 'No', 'jmembers' ) ?></label>
								</td>
							</tr>
							<tr vlaign="top">
								<th scope="row"><label for="payment_1sc_ppp"><?php _e( 'Pay Per Posts', 'jmembers' ) ?></label></th>
								<td>
									<input type="radio" value="1" name="jmembers_settings[payment_1sc_ppp]" id="payment_1sc_ppp_1" <?php if( $settings['payment_1sc_ppp'] == 1 ) echo 'checked'; ?> />
									<label for="payment_1sc_ppp_0"><?php _e( 'Yes', 'jmembers' ) ?></label>
									<input type="radio" value="0" name="jmembers_settings[payment_1sc_ppp]" id="payment_1sc_ppp_0" <?php if( $settings['payment_1sc_ppp'] == 0 ) echo 'checked'; ?> />
									<label for="payment_1sc_ppp_0"><?php _e( 'No', 'jmembers' ) ?></label>
								</td>
							</tr>
							<tr vlaign="top">
								<th scope="row"><label for="payment_1sc_memberships"><?php _e( 'Memberships', 'jmembers' ) ?></label></th>
								<td>
									<input type="radio" value="1" name="jmembers_settings[payment_1sc_memberships]" id="payment_1sc_memberships_1" <?php if( $settings['payment_1sc_memberships'] == 1 ) echo 'checked'; ?> />
									<label for="payment_1sc_memberships_0"><?php _e( 'Yes', 'jmembers' ) ?></label>
									<input type="radio" value="0" name="jmembers_settings[payment_1sc_memberships]" id="payment_1sc_memberships_0" <?php if( $settings['payment_1sc_memberships'] == 0 ) echo 'checked'; ?> />
									<label for="payment_1sc_memberships_0"><?php _e( 'No', 'jmembers' ) ?></label>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><label for="payment_1sc_name"><?php _e( 'Name' , 'jmembers' ) ?></label></th>
								<td>
									<input class="regular-text" type="text" name="jmembers_settings[payment_1sc_name]" id="payment_1sc_name" value="<?php echo $settings['payment_1sc_name'] ?>" />
								</td>
							</tr>
						</tbody>
						</table>
					</div>
				</div>
				<table class="form-table">
				<tbody>
				</tbody>
				</table>
			</div>
		</div>
		<p class="form-submit">
			<input type="submit" class="button-primary" name="submit" id="submit" value="<?php _e( 'Save Changes', 'jmembers' ) ?>" />
		</p>
	</form>
</div>
<?php
	}

	public function save($input){
		$input['message_hide_content'] = $input['message_hide_content'];

		return $input;
	}

}
new JMembers_Page_Settings();