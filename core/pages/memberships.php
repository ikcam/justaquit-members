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
		wp_register_script( 'jmembers_page_memberships', plugins_url( 'javascript/page-memberships.js' , __FILE__ ) );
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
?>
	<div id="accordion">
<?php
	foreach( $memberships as $membership ):
?>
	<h3 id="membership-<?php echo $membership->ID ?>"><?php echo $membership->name ?></h3>
	<div id="membership-<?php echo $membership->ID ?>">
		<div id="accordion">
<?php
	$packages = get_packages( array( 'membership' => $membership->ID ) );

	foreach( $packages as $package ):
?>
			<h3 id="package-<?php echo $package->ID ?>"><?php echo get_package_name( $package->ID ) ?></h3>
			<div id="package-<?php echo $package->ID ?>">
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
					<th scope="row"><label for="expire_package"><?php _e( 'Package after expiration', 'jmembers' ) ?>:</label></th>
					<td>
						<select name="expire_package" id="expire_package">
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

		$membership = new JMembers_Membership( $_POST['name'] );

		if( $membership->add() == TRUE ):
			echo 1;
		else:
			echo __( 'Error adding membership.', 'jmembers' );
		endif;

		die();
	}

	public function ajax_membership_delete(){
		if( empty( $_POST ) || !wp_verify_nonce( $_POST['nonce'], 'membership_delete' )  ):
			echo __( 'Error passing security check.', 'jmembers' );
			die();
		endif;

		$membership_id = $_POST['membership_id'];

		$result = JMembers_Membership::delete($membership_id);

		if( $result == TRUE ):
			echo 1;
		else:
			echo __( 'Error deleting membership.', 'jmembers' );
		endif;

		die();
	}
}
new JMembers_Page_Memberships();