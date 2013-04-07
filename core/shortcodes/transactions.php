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
		global $jmembers_settings;

		if( !empty( $_POST ) ):
			if( $package == NULL ):
				_e( 'You selected and invalid package.', 'jmembers' );
				return;
			endif;

			$profile = new PayPalPro_CreateProfile();
			$profile->creditcardtype     = sanitize_text_field( $_POST['creditcardtype'] );
			$profile->acct               = sanitize_text_field( $_POST['acct'] );
			$profile->expdate            = $_POST['expdate_month'] . $_POST['expdate_year'];
			$profile->cvv2               = $_POST['cvv2'];
			// Billing Information
			$profile->firstname          = sanitize_text_field( $_POST['first_name'] );
			$profile->lastname           = sanitize_text_field( $_POST['last_name'] );
			$profile->address            = sanitize_text_field( $_POST['address'] );
			$profile->city               = sanitize_text_field( $_POST['city'] );
			$profile->state              = sanitize_text_field( $_POST['state'] );
			$profile->country            = sanitize_text_field( $_POST['country'] );
			$profile->zip                = sanitize_text_field( $_POST['zip'] );
			// Package information
			$package = get_package( $_POST['package'] );

			switch( $package->duration_type ){
				case 1:
					$billing_period = 'Year';
					break;
				case 2:
					$billing_period = 'Month';
					break;
				case 3:
					$billing_period = 'Week';
					break;
				case 4:
					$billing_period = 'Day';
					break;
				default:
					return;
			}

			$profile->amt                = $package->price;
			$profile->currencycode       = 'USD';
			$profile->desc               = get_package_name( $package->ID );
			$profile->billingperiod      = $billing_period;
			$profile->billingfrequency   = $package->duration;
			$profile->profilestartdate   = date('Y-m-d', strtotime(current_time( 'mysql' ))).'T00:00:00Z';

			$paypalpro = new JM_Payment_PayPalPro();
			$result = $paypalpro->process( $profile->toString(), 'CreateRecurringPaymentsProfile' );

			if( $result == FALSE ):
				echo $jmembers_settings['message_transaction_error'];
				return;
			endif;
			
			$user_id = (int) $_POST['user'];
			$package_id = (int) $_POST['package'];

			add_user_meta( $user_id, '_package', $package_id, true );
			add_user_meta( $user_id, '_status', $result['PROFILESTATUS'], true );
			add_user_meta( $user_id, '_profile_id', $result['PROFILEID'], true );
			add_user_meta( $user_id, '_datetime_join', strtotime(current_time( 'mysql' )), true );

			$transaction             = new JMembers_Transaction();
			$transaction->user_id    = (int) $_POST['user'];
			$transaction->package_id = (int) $_POST['package'];
			$transaction->datetime   = strtotime( current_time( 'mysql' ) );
			$transaction->data       = serialize( $result );
			$transaction->add();

			echo $jmembers_settings['message_transaction_correct'];

			return;
		endif;

		if( isset( $_GET['user_id'] ) && isset( $_GET['package'] ) && isset( $_GET['processor'] ) ):
			$user = get_userdata( $_GET['user_id'] );
			$package = get_package( $_GET['package'] );

			if( !$user ){
				return  'Usuario invalido';
			}

			if( $package == NULL ){
				return 'Paquete invalido.';
			}
?>

	<form method="post" action="">
		<input type="hidden" name="processor" id="processor" value="ppro" />
		<input type="hidden" name="package" id="package" value="<?php echo $_POST['package_id'] ?>" />
		<input type="hidden" name="user" id="user" value="<?php echo $user_id ?>" />
		<?php wp_nonce_field( 'transactions', 'jmembers_nonce' ) ?>
		<h3><?php _e( 'Credit Cart Information', 'jmembers' ) ?></h3>
		<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><label for="creditcardtype"><?php _e( 'Type', 'jmembers' ) ?></label></th>
				<td>
					<select name="creditcardtype" id="creditcardtype">
						<option value="Visa">Visa</option>
						<option value="MasterCard">MasterCard</option>
						<option value="Discover">Discover</option>
						<option value="Amex">American Express</option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="acct"><?php _e( 'Number', 'jmembers' ) ?></label></th>
				<td>
					<input type="text" name="acct" id="acct" pattern="[0-9]{16}" value="<?php if( isset( $_POST['acct'] ) ) echo $_POST['acct']; ?>" required />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="expdate"><?php _e( 'Expiration', 'jmembers' ) ?></label></th>
				<td>
					<select name="expdate_month">
<?php
	for( $i = 1; $i <= 12; $i++ ){
?>
						<option value="<?php echo str_pad( $i, 2, 0, STR_PAD_LEFT ) ?>"><?php echo str_pad( $i, 2, 0, STR_PAD_LEFT ) ?></option>
<?php
	}
?>
					</select>
					<select name="expdate_year">
<?php
	for( $i = 2013; $i <= 2036; $i++ ){
?>
						<option value="<?php echo $i ?>"><?php echo $i ?></option>
<?php
	}
?>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="cvv2"><?php _e( 'CVV2', 'jmembers' ) ?></label></th>
				<td>
					<input type="text" name="cvv2" id="cvv2" pattern="[0-9]{3}" value="<?php if( isset( $_POST['cvv2'] ) ) echo $_POST['cvv2']; ?>" required />
				</td>
			</tr>
		</tbody>
		</table>

		<input type="radio" value="0" name="use_userdata" id="use_userdata_0" checked />
		<label for="use_userdata_0"><?php _e( 'Use your current information?' , 'jmembers' ) ?></label>
		<div id="use_userdata">
			<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="user_email"><?php _e( 'Email', 'jmembers' ) ?></label></th>
					<td>
						<input type="text" name="user_email" id="user_email" value="<?php echo $userdata['user_email'] ?>" readonly />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="first_name"><?php _e( 'First Name', 'jmembers' ) ?></label></th>
					<td>
						<input type="text" name="first_name" id="first_name" value="<?php echo $userdata['first_name'] ?>" readonly />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="last_name"><?php _e( 'Last Name', 'jmembers' ) ?></label></th>
					<td>
						<input type="text" name="last_name" id="last_name" value="<?php echo $userdata['last_name'] ?>" readonly />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="address"><?php _e( 'Address', 'jmembers' ) ?></label></th>
					<td>
						<textarea name="address" id="address" readonly><?php echo sanitize_text_field( $_POST['address'] )?></textarea>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="city"><?php _e( 'City', 'jmembers' ) ?></label></th>
					<td>
						<input type="text" name="city" id="city" value="<?php echo sanitize_text_field( $_POST['city'] ) ?>" readonly />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="state"><?php _e( 'State', 'jmembers' ) ?></label></th>
					<td>
						<input type="text" name="state" id="state" value="<?php echo sanitize_text_field( $_POST['state'] ) ?>" readonly />
					</td>
				</tr>
				<tr valign="top">
					<th><label for="country"><?php _e( 'Country', 'jmembers' ) ?></label></th>
					<td>
						<input type="hidden" name="country" id="country" value="<?php echo sanitize_text_field( $_POST['country'] ) ?>" />
						<select onmouseover="this.disabled=true;" onmouseout="this.disabled=false;">
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
					<th scope="row"><label for="zip"><?php _e( 'ZIP', 'jmembers' ) ?></label></th>
					<td>
						<input type="text" name="zip" id="zip" value="<?php echo sanitize_text_field( $_POST['zip'] ) ?>" readonly />
					</td>
				</tr>
			</tbody>
			</table>
		</div>
		<br />
		<input type="radio" value="1" name="use_userdata" id="use_userdata_1" />
		<label for="use_userdata_1"><?php _e( 'No, use another information', 'jmembers' ) ?></label>
		<div id="use_userdata_no" style="display:none;">
			<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="user_email"><?php _e( 'Email', 'jmembers' ) ?></label></th>
					<td>
						<input type="text" name="user_email" id="user_email" value="" required disabled />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="first_name"><?php _e( 'First Name', 'jmembers' ) ?></label></th>
					<td>
						<input type="text" name="first_name" id="first_name" value="" required disabled />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="last_name"><?php _e( 'Last Name', 'jmembers' ) ?></label></th>
					<td>
						<input type="text" name="last_name" id="last_name" value="" required disabled />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="address"><?php _e( 'Address', 'jmembers' ) ?></label></th>
					<td>
						<textarea name="address" id="address" required disabled></textarea>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="city"><?php _e( 'City', 'jmembers' ) ?></label></th>
					<td>
						<input type="text" name="city" id="city" value="" required disabled />
					</td>
				</tr>
				<tr valign="top">
					<th><label for="state"><?php _e( 'State', 'jmembers' ) ?></label></th>
					<td>
						<select name="state" id="state" disabled style="display:none;">
<?php
$states = get_states();
foreach( $states as $key => $value ):
?>
							<option value="<?php echo $key ?>"><?php echo $value ?></option>
<?php
endforeach;
?>
						</select>
						<input type="text" name="state" id="state" required disabled />
					</td>
				</tr>
				<tr valign="top">
					<th><label for="country"><?php _e( 'Country', 'jmembers' ) ?></label></th>
					<td>
						<select name="country" id="country" disabled>
<?php
	$countries = get_countries();
	foreach( $countries as $key => $value ):
?>
							<option value="<?php echo $key ?>"><?php echo $value ?></option>
<?php
	endforeach;
?>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="zip"><?php _e( 'ZIP', 'jmembers' ) ?></label></th>
					<td>
						<input type="text" name="zip" id="zip" required disabled />
					</td>
				</tr>
			</tbody>
			</table>
		</div>
		<p class="form-submit">
			<input class="button-primary" type="submit" name="submit" id="submit" value="<?php _e( 'Pay Now', 'jmembers' ) ?>" />
		</p>
	</form>
<?php
		endif;
	}
}
new JM_Shotcode_Transactions();