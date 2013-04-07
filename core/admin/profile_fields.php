<?php
Class JM_Admin_User_Fields{
	public function __construct(){
		// Mostrar campos al momento de ver y editar el perfil
		add_action( 'show_user_profile', array( &$this, 'content' ) );
		add_action( 'edit_user_profile', array( &$this, 'content' ) );
		// Al momento de actualizar el perfil del usuario
		add_action( 'personal_options_update', array( &$this, 'save' ) );
		add_action( 'edit_user_profile_update', array( &$this, 'save' ) );
	}

	public function save( $user_id ){
		if ( !current_user_can( 'edit_user', $user_id ) )
			return FALSE;

		$address = sanitize_text_field( $_POST['address'] );
		$city    = sanitize_text_field( $_POST['city'] );
		$state   = sanitize_text_field( $_POST['state'] );
		$zip     = sanitize_text_field( $_POST['zip'] );
		$country = sanitize_text_field( $_POST['country'] );

		add_user_meta( $user_id, '_address', $address, true ) or update_user_meta( $user_id, '_address', $address );
		add_user_meta( $user_id, '_city', $city, true ) or update_user_meta( $user_id, '_city', $city );
		add_user_meta( $user_id, '_state', $state, true ) or update_user_meta( $user_id, '_state', $state );
		add_user_meta( $user_id, '_zip', $zip, true ) or update_user_meta( $user_id, '_zip', $zip );
		add_user_meta( $user_id, '_country', $country, true ) or update_user_meta( $user_id, '_country', $country );
	}

	public function content( $user ){
?>
		<h3><?php _e( 'Extra Profile Information', 'jmembers' ) ?></h3>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="address"><?php _e( 'Address', 'jmembers' ) ?></label></th>
				<td>
					<textarea rows="5" cols="30" id="address" name="address"><?php echo get_user_meta( $user->ID, '_address', true ) ?></textarea>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="city"><?php _e( 'City', 'jmembers' ) ?></label></th>
				<td>
					<input type="text" name="city" id="city" class="regular-text" value="<?php echo get_user_meta( $user->ID, '_city', true ) ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="state"><?php _e( 'State', 'jmembers' ) ?></label></th>
				<td>
					<input type="text" name="state" id="state" class="regular-text" value="<?php echo get_user_meta( $user->ID, '_state', true ) ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="zip"><?php _e( 'ZIP Code', 'jmembers' ) ?></th>
				<td>
					<input type="text" id="zip" name="zip" value="<?php echo get_user_meta( $user->ID, '_zip', true ) ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="country"><?php _e( 'Country', 'jmembers' ) ?></label></th>
				<td>
					<select name="country" id="country" readonly>
<?php
	$countries = get_countries();
	foreach( $countries as $key => $value ):
?>
						<option value="<?php echo $key ?>" <?php if( get_user_meta( $user->ID, '_country', true ) == $key ) echo 'selected'; ?>>
							<?php echo $value ?>
						</option>
<?php
	endforeach;
?>
					</select>
				</td>
			</tr>
		</table>
<?php
	}
}
new JM_Admin_User_Fields();
?>