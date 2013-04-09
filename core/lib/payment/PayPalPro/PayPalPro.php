<?php
Class JM_Payment_PayPalPro{
	private $user;
	private $pwd;
	private $signature;
	private $version;
	private $method;
	private $sandbox;

	public function __construct(){
		global $jmembers_settings;

		$this->user      = $jmembers_settings['payment_pppro_username'];
		$this->pwd       = $jmembers_settings['payment_pppro_password'];
		$this->signature = $jmembers_settings['payment_pppro_signature'];
		$this->sandbox   = $jmembers_settings['payment_pppro_sandbox'];
		$this->version   = '86.0';
	}

	public function toString(){
		$string = '';

		foreach( get_object_vars($this) as $key => $value )
			$string .= '&'.strtoupper($key).'='.urlencode($value);

		return $string;
	}

	public function process( $method, $string ){
		$this->method = $method;

		$curl = curl_init();

		curl_setopt( $curl, CURLOPT_VERBOSE, 1 );
		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, FALSE );
		curl_setopt( $curl, CURLOPT_TIMEOUT, 30 );
		if( $this->sandbox == 1 ):
			curl_setopt( $curl, CURLOPT_URL, 'https://api-3t.sandbox.paypal.com/nvp' );
		else:
			curl_setopt( $curl, CURLOPT_URL, 'https://api-3t.sandbox.paypal.com/nvp' );
		endif;
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $curl, CURLOPT_POSTFIELDS, $this->toString().$string );

		$result = curl_exec( $curl );
		curl_close( $curl );

		$result = $this->NVPToArray( $result );

		if( $result['ACK'] == 'Success' )
			return $result;

		return FALSE;
	}

	private function NVPToArray( $NVPString ){
		$proArray = array();

		while( strlen( $NVPString ) ){
			// name
			$keypos            = strpos( $NVPString, '=' );
			$keyval            = substr( $NVPString, 0, $keypos );
			// value
			$valuepos          = strpos( $NVPString, '&' ) ? strpos( $NVPString, '&' ): strlen( $NVPString );
			$valval            = substr( $NVPString, $keypos + 1, $valuepos - $keypos - 1 );
			// decoding the respose
			$proArray[$keyval] = urldecode( $valval );
			$NVPString         = substr( $NVPString, $valuepos + 1, strlen( $NVPString ) );
		}

		return $proArray;
	}

	public function form( $data ){
?>
<form id="transaction" method="post" action="">
<?php if( isset( $data['package_id'] ) ): ?>
	<input type="hidden" name="package" id="package" value="<?php echo $data['package_id'] ?>" />
	<input type="hidden" name="post" id="post" value="0" />
<?php else: ?>
	<input type="hidden" name="package" id="package" value="0" />
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
	}
}