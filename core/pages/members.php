<?php
Class JMembers_Page_Members{
	public function __construct(){
		add_action( 'admin_menu', array( &$this, 'init' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'scripts' ) );
	}

	public function init(){
		add_submenu_page( 'jmembers', __( 'Manage Members', 'jmembers' ), __( 'Manage Members', 'jmembers' ), 'manage_options', 'jmembers_members', array( &$this, 'page' ) );
	}

	public function scripts(){
		wp_register_script( 'jmembers-members', JMEMBERS_URL. 'core/javascript/page-members.js', array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker') );
		wp_enqueue_script( 'jmembers-members' );
	}

	public function page(){
?>
<div class="wrap">
	<h2><?php _e( 'Manage Members', 'jmembers' ) ?></h2>

	<form action="" method="get">
	<input type="hidden" id="page" name="page" value="<?php echo $_GET['page'] ?>" />
		<div id="accordion-search">
			<h3><?php _e( 'Search User', 'jmembers' ) ?></h3>
			<div>
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><label for="s"><?php _e( 'Username or email', 'jmembers' ) ?></label></th>
						<td>
							<input type="search" id="s" name="s" value="<?php if( isset( $_GET['s'] ) ) echo $_GET['s'] ?>" />
						</td>
					</tr>
				</table>
				<p class="form-submit">
					<input class="button-primary" type="submit" value="<?php _e( 'Search', 'jmembers' ) ?>" />
				</p>
			</div>
		</div>
	</form>
	<h3>User List</h3>
<?php
if( isset($_GET['s']) ):
	$args = array(
		'search' => $_GET['s']
	);

	$users = get_users( $args );
else:
	$users = get_users();
endif;

$packages = get_packages();

foreach($users as $user):
	$user_package = get_user_meta( $user->ID, '_package_id', true );
	$user_status  = get_user_meta( $user->ID, '_status', true );
	$user_join    = get_user_meta( $user->ID, '_datetime_packjoin', true );
	$user_expire  = get_user_meta( $user->ID, '_datetime_expire', true );
	$user_payment = unserialize( get_user_meta( $user->ID, '_payment', true ) );
?>
	<div id="accordion-user-<?php echo $user->ID ?>">
		<h3><strong>User:</strong> <?php echo $user->user_login ?> | <strong>Email:</strong> <?php echo $user->user_email ?></h3>
		<div>
			<form action="" id="member-update-<?php echo $user->ID ?>" method="post">
				<?php wp_nonce_field( 'member_update', 'jmembers_nonce' ) ?>
				<input type="hidden" name="user" id="user-<?php echo $user->ID ?>" value="<?php echo $user->ID ?>" />
				<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><label for="status-<?php echo $user->ID ?>"><?php _e( 'Status', 'jmembers' ) ?></label></th>
						<td>
							<select name="status" id="status-<?php echo $user->ID ?>">
								<option value="Active" <?php if( $user_status == 'Active' ) echo 'selected'; ?>><?php _e( 'Active', 'jmembers' ) ?></option>
								<option value="Inactive" <?php if( $user_status == 'Inactive' ) echo 'selected'; ?>><?php _e( 'Inactive', 'jmembers' ) ?></option>
								<option value="Expired" <?php if( $user_status == 'Expired' ) echo 'selected'; ?>><?php _e( 'Expired', 'jmembers' ) ?></option>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="package-<?php echo $user->ID ?>"><?php _e('Package', 'jmembers') ?></label></th>
						<td>
							<select name="_package_id" id="package-<?php echo $user->ID ?>">
								<option value="0"><?php echo 'None' ?></option>
<?php foreach( $packages as $package ): ?>
								<option value="<?php echo $package->ID ?>" <?php if( $package->ID == $user_package ) echo 'selected'; ?>>
									<?php echo get_package_name($package->ID) ?>
								</option>
<?php endforeach ?>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="date-join-<?php echo $user->ID ?>"><?php _e( 'Join Date', 'jmembers' ) ?></label></th>
						<td>
							<input type="text" name="date-join" id="date-join-<?php echo $user->ID ?>" value="<?php echo date( 'd/m/Y', $user_join ) ?>" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="date-expire-<?php echo $user->ID ?>"><?php _e( 'Expire Date', 'jmembers' ) ?></label></th>
						<td>
							<input type="text" name="date-expire" id="date-expire-<?php echo $user->ID ?>" value="<?php echo date( 'd/m/Y', $user_expire ) ?>" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="payment-<?php echo $user->ID ?>"><?php _e( 'Payment Information', 'jmembers' ) ?></label></th>
						<td>
							<table class="form-table">
								<tr valign="top">
									<th scope="row">
										<label for="payment-processor-<?php echo $user->ID ?>"><?php _e( 'Payment Processor', 'jmembers' ) ?></label>
									</th>
									<td>
										<select name="payment-processor" id="payment-processor-<?php echo $user->ID ?>">
											<option value="0" <?php if( 0 == $user_payment['processor'] ) echo 'selected'; ?>>
												<?php _e( 'None', 'jmembers' ) ?>
											</option>
											<option value="pppro" <?php if( 'pppro' == $user_payment['processor'] ) echo 'selected'; ?>>
												<?php _e( 'PayPal Pro', 'jmembers' ) ?>
											</option>
											<option value="ppstandard" <?php if( 'ppstandard' == $user_payment['processor'] ) echo 'selected'; ?>>
												<?php _e( 'PayPal Standard', 'jmembers' ) ?>
											</option>
											<option value="1sc" <?php if( '1sc' == $user_payment['processor'] ) echo 'selected'; ?>>
												<?php _e( '1Shopping Cart', 'jmembers' ) ?>
											</option>
										</select>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row">
										<label for="payment-profile-<?php echo $user->ID ?>"><?php _e( 'Profile ID', 'jmembers' ) ?></label>
									</th>
									<td>
										<input type="text" name="payment-profile" id="payment-profile-<?php echo $user->ID ?>" value="<?php echo $user_payment['profile_id'] ?>" />
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="payment-status-<?php echo $user->ID ?>"><?php _e( 'Profile Status', 'jmembers' ) ?></label>
									</th>
									<td>
										<select name="payment-status" id="payment-status-<?php echo $user->ID ?>">
											<option value="ActiveProfile" <?php if( 'ActiveProfile' != $user_payment['profile_status'] ) echo 'disabled'; ?>>
												<?php _e( 'Active Profile', 'jmembers' ) ?>
											</option>
											<option value="CancelledProfile" <?php if( 'CancelledProfile' != $user_payment['profile_status'] ) echo 'disabled'; ?>>
												<?php _e( 'Cancelled Profile', 'jmembers' ) ?>
											</option>
											<option value="SuspendedProfile" <?php if( 'SuspendedProfile' != $user_payment['profile_status'] ) echo 'disabled'; ?>>
												<?php _e( 'Suspended Profile', 'jmembers' ) ?>
											</option>
											<option value="ExpiredProfile" <?php if( 'ExpiredProfile' != $user_payment['profile_status'] ) echo 'disabled'; ?>>
												<?php _e( 'Expired Profile', 'jmembers' ) ?>
											</option>
										</select>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</tbody>
				</table>
				<p class="form-submit">
					<input class="button-primary" type="submit" name="submit" id="submit" value="<?php _e('Update User', 'jmembers') ?>" />
					<img class="waiting" src="<?php echo admin_url( ) ?>images/wpspin_light.gif" height="16" width="16" style="display:none" />
				</p>
			</form>
		</div>
	</div>
<?php endforeach; ?>
</div>
<?php
	}
}
new JMembers_Page_Members();
