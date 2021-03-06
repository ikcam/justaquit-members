<?php
Class JMembers_Page_Memberships{
	public function __construct(){
		add_action( 'admin_menu', array( &$this, 'init' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'scripts' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'stylesheets' ) );
		add_action( 'wp_ajax_jmembers_membership_add', array( &$this, 'ajax_membership_add' ) );
		add_action( 'wp_ajax_jmembers_membership_delete', array( &$this, 'ajax_membership_delete' ) );
	}

	public function init(){
		add_submenu_page( 'jmembers', __( 'Manage Memberships', 'jmembers' ), __( 'Manage Memberships', 'jmembers' ), 'manage_options', 'jmembers_memberships', array( &$this, 'page' ) );
	}

	public function stylesheets(){
		wp_enqueue_style( 'jquery-ui-core', 'http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css' );
	}

	public function scripts(){
		wp_register_script( 'jmembers_page_memberships', JMEMBERS_URL . 'core/javascript/page-memberships.js', array( 'jquery' ) );
		wp_enqueue_script( 'jmembers_page_memberships' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-accordion' );
	}

	public function page(){
?>
<div class="wrap">
	<h2><?php _e( 'Memberships', 'jmembers' ) ?></h2>

	<h3><?php _e( 'List of Memberships', 'jmembers' ) ?></h3>
<?php
	$memberships = get_memberships();
	if( $memberships == NULL ):
		_e( 'You haven\'t created any membership yet.', 'jmembers' );
	else:
?>
	<div id="accordion-memberships">
<?php
		foreach( $memberships as $membership ):
?>
	<h3 id="membership-<?php echo $membership->ID ?>"><?php echo $membership->name ?></h3>
	<div id="membership-<?php echo $membership->ID ?>">
		<div id="accordion-packages-<?php echo $membership->ID ?>">
<?php
		$packages = get_packages( array( 'membership' => $membership->ID ) );

			foreach( $packages as $package ):
?>
			<h3 id="package-<?php echo $package->ID ?>"><?php echo get_package_name( $package->ID ) ?></h3>
			<div id="package-<?php echo $package->ID ?>">
				<form id="package-update-<?php echo $package->ID ?>" method="post" action="">
					<?php wp_nonce_field( 'package_update', 'jmember_nonce' )  ?>
					<input type="hidden" name="package_id" id="package_id" value="<?php echo $package->ID ?>" />
					<input type="hidden" name="membership_id" id="membership_id" value="<?php echo $membership->ID ?>" />
					<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row"><label for="duration"><?php _e( 'Duration', 'jmembers' ) ?></label></th>
							<td>
								<input type="text" name="duration" id="duration" value="<?php echo $package->duration ?>" />
								<select name="duration_type" id="duration_type">
									<option value="0" <?php if( $package->duration_type == 0 ) echo 'selected'; ?>><?php _e( 'Lifetime', 'jmembers' ) ?></option>
									<option value="1" <?php if( $package->duration_type == 1 ) echo 'selected'; ?>><?php _e( 'Years', 'jmembers' ) ?></option>
									<option value="2" <?php if( $package->duration_type == 2 ) echo 'selected'; ?>><?php _e( 'Months', 'jmembers' ) ?></option>
									<option value="3" <?php if( $package->duration_type == 3 ) echo 'selected'; ?>><?php _e( 'Weeks', 'jmembers' ) ?></option>
									<option value="4" <?php if( $package->duration_type == 4 ) echo 'selected'; ?>><?php _e( 'Days', 'jmembers' ) ?></option>
								</select>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="price"><?php _e( 'Price', 'jmembers' ) ?>:</label></th>
							<td><input type="text" id="price" name="price" value="<?php echo $package->price ?>" /> USD</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="billing"><?php _e( 'Billing', 'jmembers' ) ?></label></th>
							<td>
								<input type="radio" name="billing" id="billing_0" value="0" <?php if( $package->billing == 0 ) echo 'checked'; ?>/> <label for="billing_0"><?php _e( 'Auto Renew', 'jmembers' ) ?></label>
								&nbsp;
								<input type="radio" name="billing" id="billing_1" value="1" <?php if( $package->billing == 1 ) echo 'checked'; ?>/> <label for="billing_1"><?php _e( 'One Time', 'jmembers' ) ?></label>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="description"><?php _e( 'Description', 'jmembers' ) ?>:</label></th>
							<td><textarea name="description" id="description"><?php echo $package->description ?></textarea></td>
						</tr>
						<tr valign="top">
					<th scope="row"><label for="expired_package"><?php _e( 'Package after expiration', 'jmembers' ) ?>:</label></th>
						<td>
							<select name="expired_package" id="expired_package">
								<option vale="0" <?php if( $package->expired_package == 0 ) echo 'selected'; ?>><?php _e( 'None', 'jmembers' ) ?></option>
<?php
		$items = get_packages();
		foreach( $items as $item ):
?>
							<option value="<?php $item->ID ?>" <?php if( $package->expired_package == 1 ) echo 'selected'; ?>><?php echo get_package_name( $item->ID ) ?></option>
<?php
		endforeach;
?>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label><?php _e( 'Display Settings', 'jmembers' ) ?></label></th>
						<td>
<?php
	$display = unserialize($package->display);
?>
							<input type="checkbox" name="display_registration" id="display_registration" <?php if( $display['display_registration'] == 1 ) echo 'checked'; ?>/>
							<label for="display_registration"><?php _e( 'Register page', 'jmembers' ) ?></label>
							&nbsp;
							<input type="checkbox" name="display_upgrade" id="display_upgrade" <?php if( $display['display_upgrade'] == 1 ) echo 'checked'; ?>/>
							<label for="display_upgrade"><?php _e( 'Upgrade page', 'jmembers' ) ?></label>
							&nbsp;
							<input type="checkbox" name="display_extend" id="display_extend" <?php if( $display['display_extend'] == 1 ) echo 'checked'; ?>/>
							<label for="display_extend"><?php _e( 'Extend page', 'jmembers' ) ?></label>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label><?php _e( 'Payment Settings', 'jmembers' ) ?></label></th>
						<td>
<?php
	$payment = unserialize($package->payment);
?>
							<input type="checkbox" name="payment_pppro" id="payment_pppro" <?php if( $payment['pppro'] == 1 ) echo 'checked'; ?> />
							<label for="payment_pppro"><?php _e( 'PayPal Pro', 'jmembers' ) ?></label>
							&nbsp;
							<input type="checkbox" name="payment_ppstandard" id="payment_ppstandard" <?php if( $payment['ppstandard'] == 1 ) echo 'checked'; ?> />
							<label for="payment_ppstandard"><?php _e( 'PayPal Standard', 'jmembers' ) ?></label>
							&nbsp;
							<input type="checkbox" name="payment_1sc" id="payment_1sc" <?php if( $payment['1sc'] == 1 ) echo 'checked'; ?> />
							<label for="payment_1sc"><?php _e( '1Shopping Cart', 'jmembers' ) ?></label>
							&nbsp;
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="menu_order"><?php _e( 'Menu Order', 'jmembers' ) ?>:</label></th>
						<td>
							<input type="text" name="menu_order" id="menu_order" value="<?php echo $package->menu_order ?>" />
						</td>
					</tr>
					</tbody>
					</table>
					<p class="form-submit">
						<input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e( 'Update Package', 'jmembers' ) ?>" />
						<img class="waiting" src="<?php echo admin_url( ) ?>images/wpspin_light.gif" height="16" width="16" style="display:none" />
					</p>
				</form>
						or
				<form id="package-delete-<?php echo $package->ID ?>" action="" method="post">
					<?php wp_nonce_field( 'package_delete', 'jmember_nonce' ) ?>
					<input type="hidden" name="package_id" id="package_id" value="<?php echo $package->ID ?>" />
					<p>
						<input type="submit" name="submit" id="submit" class="button-secondary" value="<?php _e( 'Delete Package', 'jmembers' ) ?>" />
						<img class="waiting" src="<?php echo admin_url( ) ?>images/wpspin_light.gif" height="16" width="16" style="display:none" />
					</p>
				</form>
			</div>
<?php
			endforeach;
?>
		</div>
		<h4><?php _e( 'Add New Package', 'jmembers' ) ?></h4>
		<form id="package-add-<?php echo $membership->ID ?>" action="" method="post">
			<?php wp_nonce_field( 'package_add', 'jmember_nonce' ) ?>
			<input type="hidden" name="membership_id" id="membership_id" value="<?php echo $membership->ID ?>" />
			<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="duration"><?php _e( 'Duration', 'jmembers' ) ?>:</label></th>
					<td>
						<input type="text" name="duration" id="duration" value="0" />
						<select name="duration_type" id="duration_type">
							<option value="0" selected="selected"><?php _e( 'Lifetime', 'jmembers' ) ?></option>
							<option value="1"><?php _e( 'Years', 'jmembers' ) ?></option>
							<option value="2"><?php _e( 'Months', 'jmembers' ) ?></option>
							<option value="3"><?php _e( 'Weeks', 'jmembers' ) ?></option>
							<option value="4"><?php _e( 'Days', 'jmembers' ) ?></option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="price"><?php _e( 'Price', 'jmembers' ) ?>:</label></th>
					<td><input type="text" id="price" name="price" value="0.00" /> USD</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="billing"><?php _e( 'Billing', 'jmembers' ) ?></label></th>
					<td>
						<input type="radio" name="billing" id="billing_0" value="0" checked="checked" /> <label for="billing_0"><?php _e( 'Auto Renew', 'jmembers' ) ?></label>
						&nbsp;
						<input type="radio" name="billing" id="billing_1" value="1" /> <label for="billing_1"><?php _e( 'One Time', 'jmembers' ) ?></label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="description"><?php _e( 'Description', 'jmembers' ) ?>:</label></th>
					<td><textarea name="description" id="description"></textarea></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="expired_package"><?php _e( 'Package after expiration', 'jmembers' ) ?>:</label></th>
					<td>
						<select name="expired_package" id="expired_package">
							<option vale="0"><?php _e( 'None', 'jmembers' ) ?></option>
<?php
		$items = get_packages();
		foreach( $items as $item ):
?>
							<option value="<?php $item->ID ?>"><?php echo get_package_name( $item->ID ) ?></option>
<?php
		endforeach;
?>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label><?php _e( 'Display Settings', 'jmembers' ) ?></label></th>
					<td>
						<input type="checkbox" name="display_registration" id="display_registration" checked />
						<label for="display_registration"><?php _e( 'Register page', 'jmembers' ) ?></label>
						&nbsp;
						<input type="checkbox" name="display_upgrade" id="display_upgrade" checked />
						<label for="display_upgrade"><?php _e( 'Upgrade page', 'jmembers' ) ?></label>
						&nbsp;
						<input type="checkbox" name="display_extend" id="display_extend" checked />
						<label for="display_extend"><?php _e( 'Extend page', 'jmembers' ) ?></label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label><?php _e( 'Payment Settings', 'jmembers' ) ?></label></th>
					<td>
						<input type="checkbox" name="payment_pppro" id="payment_pppro" />
						<label for="payment_pppro"><?php _e( 'PayPal Pro', 'jmembers' ) ?></label>
						&nbsp;
						<input type="checkbox" name="payment_ppstandard" id="payment_ppstandard" />
						<label for="payment_ppstandard"><?php _e( 'PayPal Standard', 'jmembers' ) ?></label>
						&nbsp;
						<input type="checkbox" name="payment_1sc" id="payment_1sc" />
						<label for="payment_1sc"><?php _e( '1Shopping Cart', 'jmembers' ) ?></label>
						&nbsp;
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="menu_order"><?php _e( 'Menu Order', 'jmembers' ) ?>:</label></th>
					<td>
						<input type="text" name="menu_order" id="menu_order" value="0" />
					</td>
				</tr>
			</tbody>
			</table>
			<p class="form-submit">
				<input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e( 'Add New', 'jmembers' ) ?>" />
				<img class="waiting" src="<?php echo admin_url( ) ?>images/wpspin_light.gif" height="16" width="16" style="display:none" />
			</p>
		</form>

		<form id="membership-delete-<?php echo $membership->ID ?>" action="" method="post">
			<?php wp_nonce_field( 'membership_delete', 'jmember_nonce' ) ?>
			<input type="hidden" name="membership_id" id="membership_id" value="<?php echo $membership->ID ?>" />
			<p class="form-submit">
				<input type="submit" name="submit" id="submit" class="button-secondary" value="<?php _e( 'Delete Membership', 'jmembers' ) ?>" />
				<img class="waiting" src="<?php echo admin_url( ) ?>images/wpspin_light.gif" height="16" width="16" style="display:none" />
			</p>
		</form>
	</div>
<?php
		endforeach;
	endif;
?>
	</div>

	<h3><?php _e( 'Add New Membership', 'jmembers' ) ?></h3>
	<form id="membership-add" action="" method="post">
		<?php wp_nonce_field( 'membership_add', 'jmember_nonce' ) ?>
		<table class="form-table">
		<tbody>
			<tr valign="top">
				<td><label for="name"><?php _e( 'Name', 'jmembers' ) ?>:</label></td>
				<td><input type="text" name="name" id="name" required="required" /></td>
			</tr>
		</tbody>
		</table>
		<p class="form-submit">
			<input class="button-primary" type="submit" name="submit" id="submit" value="<?php _e( 'Add New' ) ?>" />
			<img class="waiting" src="<?php echo admin_url( ) ?>images/wpspin_light.gif" height="16" width="16" style="display:none" />
		</p>
	</form>
</div>
<?php
	}

	public function ajax_membership_add(){
		if( empty( $_POST ) || !wp_verify_nonce( $_POST['nonce'], 'membership_add' )  ):
			echo __( 'Error passing security check.', 'jmembers' );
			die();
		endif;

		$membership       = new JMembers_Membership();
		$membership->name = (String) $_POST['name'];

		if( !$membership->add() ):
			echo __( 'Error adding membership.', 'jmembers' );
		endif;

		echo 1;

		die();
	}

	public function ajax_membership_delete(){
		if( empty( $_POST ) || !wp_verify_nonce( $_POST['nonce'], 'membership_delete' )  ):
			echo __( 'Error passing security check.', 'jmembers' );
			die();
		endif;

		$result = JMembers_Membership::delete( $_POST['membership_id'] );

		if( $result == TRUE ):
			echo 1;
		else:
			echo __( 'Error deleting membership.', 'jmembers' );
		endif;

		die();
	}
}
new JMembers_Page_Memberships();