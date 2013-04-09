<?php
Class JM_Shortcode_User_Registration {
	public function __construct(){
		add_shortcode( 'user_registration', array( &$this, 'shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'scripts' ) );
		add_action( 'wp_head', array(  &$this, 'ajaxurl' ) );
	}

	public function ajaxurl(){
?>
<script type="text/javascript">
	var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
</script>
<?php
	}

	public function scripts(){
		wp_register_script( 'jmembers-user_registration', JMEMBERS_URL . 'core/shortcodes/javascript/user_registration.js', array( 'jquery' ) );
		wp_enqueue_script( 'jmembers-user_registration' );
	}

	public function shortcode(){
		global $jmembers_settings;

		if( get_current_user_id() != 0 )
		 return __('You are already registered.', 'jmembers');

?>
<div id="jmembers-user_registration">
<form id="user_registration" action="" method="post">
	<?php wp_nonce_field( 'user_registration', 'jmembers_nonce' ) ?>
	<h3><?php _e('Basic User Information', 'jmembers') ?></h3>
	<table class="form-table">
	<tbody>
		<tr valign="top" class="user_row">
			<th><label for="user_login"><?php _e( 'Username', 'jmembers' ) ?></label></th>
			<td>
				<input type="text" name="user_login" id="user_login" required value="<?php if( isset( $_POST['user_login'] ) ) echo $_POST['user_login'] ?>" />
				<img id="user_login_waiting" src="<?php echo admin_url('images/wpspin_light.gif') ?>" style="display:none;" />
				<img id="user_login_status" src="<?php echo admin_url('images/wpspin_light.gif') ?>" style="display:none;" />
				<small><span id="user_login_message"></span></small>
			</td>
		</tr>
		<tr valign="top" class="email_row">
			<th><label for="user_email" class="user_email"><?php _e( 'Email', 'jmembers' ) ?></label></th>
			<td>
				<input type="email" name="user_email" id="user_email" required value="<?php if( isset( $_POST['user_email'] ) ) echo $_POST['user_email'] ?>" />
				<img id="user_email_waiting" src="<?php echo admin_url('images/wpspin_light.gif') ?>" style="display:none;" />
				<img id="user_email_status" src="<?php echo admin_url('images/wpspin_light.gif') ?>" style="display:none;" />
				<small><span id="user_email_message"></span></small>
			</td>
		</tr>
		<tr valign="top" class="pass_row">
			<th><label for="user_pass"><?php _e( 'Password', 'jmembers' ) ?></label></th>
			<td>
				<input type="password" name="user_pass" id="user_pass" required value="" />
			</td>
		</tr>
		<tr valign="top" class="pass_row">
			<th><label for="user_pass_confirm"><?php _e( 'Repeat Password', 'jmembers' ) ?></label></th>
			<td>
				<input type="password" name="user_pass_confirm" id="user_pass_confirm" required value="" />
				<img id="user_pass_waiting" src="<?php echo admin_url('images/wpspin_light.gif') ?>" style="display:none;" />
				<img id="user_pass_status" src="<?php echo admin_url('images/wpspin_light.gif') ?>" style="display:none;" />
				<small><span id="user_pass_message"></span></small>
			</td>
		</tr>
	</tbody>
	</table>
	<h3><?php _e('Extra User Information', 'jmembers') ?></h3>
	<table class="form-table">
	<tbody>
		<tr valign="top">
			<th><label for="first_name"><?php _e( 'First Name', 'jmembers' ) ?></label></th>
			<td>
				<input type="text" name="first_name" id="first_name" required value="<?php if( isset( $_POST['first_name'] ) ) echo $_POST['first_name'] ?>" />
			</td>
		</tr>
		<tr valign="top">
			<th><label for="last_name"><?php _e( 'Last Name', 'jmembers' ) ?></label></th>
			<td>
				<input type="text" name="last_name" id="last_name" required value="<?php if( isset( $_POST['last_name'] ) ) echo $_POST['last_name'] ?>" />
			</td>
		</tr>
		<tr>
			<th><label for="address"><?php _e( 'Address', 'jmembers' ) ?></label></th>
			<td>
				<textarea name="address" id="address" required><?php if( isset( $_POST['address'] ) ) echo $_POST['address']; ?></textarea>
			</td>
		</tr>
		<tr valign="top">
			<th><label for="city"><?php _e( 'City', 'jmembers' ) ?></label></th>
			<td>
				<input type="text" name="city" id="city" required value="<?php if( isset( $_POST['city'] ) ) echo $_POST['city'] ?>" />
			</td>
		</tr>
		<tr valign="top">
			<th><label for="state"><?php _e( 'State', 'jmembers' ) ?></label></th>
			<td>
				<select name="state" id="state" disabled style="display:<?php if( isset( $_POST['country'] ) && $_POST['country'] == 'US' ) echo 'inline-block;'; else echo 'none'; ?>">
<?php
$states = get_states();
foreach( $states as $key => $value ):
?>
					<option value="<?php echo $key ?>" <?php if( isset( $_POST['state'] ) && $_POST['state'] == $key ) echo 'selected'; ?>><?php echo $value ?></option>
<?php
endforeach;
?>
				</select>
				<input type="text" name="state" id="state" required value="<?php if( isset( $_POST['state'] ) ) echo $_POST['state'] ?>" />
			</td>
		</tr>
		<tr valign="top">
			<th><label for="country"><?php _e( 'Country', 'jmembers' ) ?></label></th>
			<td>
				<select name="country" id="country" required>
<?php
	$countries = get_countries();
	foreach( $countries as $key => $value ):
?>
					<option value="<?php echo $key ?>" <?php if(  isset( $_POST['country'] ) && ($_POST['country'] == $key) ) echo 'selected'; ?>>
						<?php echo $value ?>
					</option>
<?php
	endforeach;
?>
				</select>
			</td>
		</tr>
		<tr valign="top">
			<th><label for="zip"><?php _e( 'Zip', 'jmembers' ) ?></label></th>
			<td>
				<input type="text" name="zip" id="zip" required value="<?php if( isset( $_POST['zip'] ) ) echo $_POST['zip'] ?>" />
			</td>
		</tr>

	</tbody>
	</table>
	<h3><?php _e('Subscription Options', 'jmembers') ?></h3>
<?php
	$packages = get_packages();

	if( $packages != NULL ):
		foreach( $packages as $package ):
			$display = unserialize($package->display);
			if( $display['display_registration'] == 1 ):
?>	
		<p class="package_row">
			<input type="radio" name="package_id" id="package_<?php echo $package->ID ?>" value="<?php echo $package->ID ?>" <?php if( isset( $_POST['package_id'] ) && $_POST['package_id'] == $package->ID ) echo 'checked'; ?> />
			<label for="package_<?php echo $package->ID ?>"><?php echo get_package_name( $package->ID ) ?></label>
		</p>
<?php
			endif;
		endforeach;
	endif;
?>

	<h3><?php _e( 'Payment Method', 'jmembers' ) ?></h3>
<?php
	if( $jmembers_settings['payment_pppro_active'] == 1 && $jmembers_settings['payment_pppro_memberships'] == 1 ):
?>
	<p class="processor_row">
		<input type="radio" name="payment_processor" value="pppro" id="processor_pppro" />
		<label for="processor_pppro"><?php echo $jmembers_settings['payment_pppro_name'] ?></label>
	</p>
<?php
	endif;
?>
<?php
	if( $jmembers_settings['payment_ppstandard_active'] == 1 && $jmembers_settings['payment_ppstandard_memberships'] == 1 ):
?>
	<p class="processor_row">
		<input type="radio" name="payment_processor" value="ppstandard" id="processor_ppstandard" />
		<label for="processor_ppstandard"><?php echo $jmembers_settings['payment_ppstandard_name'] ?></label>
	</p>
<?php
	endif;
?>
<?php
	if( $jmembers_settings['payment_1sc_active'] == 1 && $jmembers_settings['payment_1sc_memberships'] == 1 ):
?>
	<p class="processor_row">
		<input type="radio" name="payment_processor" value="1sc" id="processor_1sc" />
		<label for="processor_1sc"><?php echo $jmembers_settings['payment_1sc_name'] ?></label>
	</p>
<?php
	endif;
?>
	<h3><?php _e( 'Terms and Conditions', 'jmembers' ) ?></h3>
	<p>
		<input type="checkbox" name="terms" id="terms" <?php if( isset( $_POST['terms'] ) ) echo 'checked'; ?> required />
		<label for="terms"><a href="<?php echo $jmembers_settings['page_terms'] ?>" target="_blank"><?php _e( 'Accept Terms and Conditions', 'jmembers' ) ?></a></label>
	</p>
	<p class="form-submit"><input type="submit" class="button-primary" name="submit" id="submit" value="<?php _e( 'Register', 'jmembers' ) ?>" /></p>
</form>
</div>
<?php
	}
}
new JM_Shortcode_User_Registration();