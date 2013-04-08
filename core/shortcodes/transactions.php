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
				
				get_payment_processor( $_GET['processor'] );

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

	private function pppro_form( $data ){
?>
	<form id="transaction" method="post" action="">
<?php if( isset( $data['package_id'] ) ): ?>
		<input type="hidden" name="package" id="package" value="<?php echo $data['package_id'] ?>" />
<?php else: ?>
		<input type="hidden" name="post" id="post" value="<?php echo $data['post_id'] ?>" />
<?php endif; ?>
		<input type="hidden" name="user" id="user" value="<?php echo $data['user_id'] ?>" />
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
					<input type="text" name="acct" id="acct" pattern="[0-9]{16}" required />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="expdate"><?php _e( 'Expiration', 'jmembers' ) ?></label></th>
				<td>
					<select name="expdate_month" id="expdate_month">
<?php
	for( $i = 1; $i <= 12; $i++ ){
?>
						<option value="<?php echo str_pad( $i, 2, 0, STR_PAD_LEFT ) ?>"><?php echo str_pad( $i, 2, 0, STR_PAD_LEFT ) ?></option>
<?php
	}
?>
					</select>
					<select name="expdate_year" id="expdate_year">
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
					<input type="text" name="cvv2" id="cvv2" pattern="[0-9]{3}" required />
				</td>
			</tr>
		</tbody>
		</table>
		<p>
			<input type="radio" value="0" name="use_userdata" id="use_userdata_0" checked />
			<label for="use_userdata_0"><?php _e( 'Use your current information?' , 'jmembers' ) ?></label>
		</p>
		<div id="use_userdata">
			<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="user_email"><?php _e( 'Email', 'jmembers' ) ?></label></th>
					<td>
						<input type="text" name="user_email" id="user_email" value="<?php echo $data['user_email'] ?>" readonly />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="first_name"><?php _e( 'First Name', 'jmembers' ) ?></label></th>
					<td>
						<input type="text" name="first_name" id="first_name" value="<?php echo $data['first_name'] ?>" readonly />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="last_name"><?php _e( 'Last Name', 'jmembers' ) ?></label></th>
					<td>
						<input type="text" name="last_name" id="last_name" value="<?php echo $data['last_name'] ?>" readonly />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="address"><?php _e( 'Address', 'jmembers' ) ?></label></th>
					<td>
						<textarea name="address" id="address" readonly><?php echo $data['address'] ?></textarea>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="city"><?php _e( 'City', 'jmembers' ) ?></label></th>
					<td>
						<input type="text" name="city" id="city" value="<?php echo $data['city'] ?>" readonly />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="state"><?php _e( 'State', 'jmembers' ) ?></label></th>
					<td>
<?php
	if( $data['country'] == 'US' ):
		$states = get_states();
?>
						<select name="state" id="state">
<?php
		foreach( $states as $key => $value ):
?>
							<option value="<?php echo $key ?>" <?php if( $data['state'] != $key ) echo 'disabled'; ?>><?php echo $value ?></option>
<?php
		endforeach;
?>
						</select>
<?php
	else:
?>
						<input type="text" name="state" id="state" value="<?php echo $data['state'] ?>" readonly />
<?php
	endif;
?>
					</td>
				</tr>
				<tr valign="top">
					<th><label for="country"><?php _e( 'Country', 'jmembers' ) ?></label></th>
					<td>
						<select name="country" id="country">
<?php
	$countries = get_countries();
	foreach( $countries as $key => $value ):
?>
							<option value="<?php echo $key ?>" <?php if( $data['country'] != $key ) echo 'disabled'; ?>>
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
						<input type="text" name="zip" id="zip" value="<?php echo $data['zip'] ?>" readonly />
					</td>
				</tr>
			</tbody>
			</table>
		</div>
		<br />
		<p>
			<input type="radio" value="1" name="use_userdata" id="use_userdata_1" />
			<label for="use_userdata_1"><?php _e( 'No, use another information', 'jmembers' ) ?></label>
		</p>
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
						<select name="country" id="country" disabled onlick="$(this).prop('disabled',true)">
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
	}
}
new JM_Shotcode_Transactions();