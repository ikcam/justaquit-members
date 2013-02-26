<?php
Class JMembers_Page_Memberships{
	public function __construct(){
		add_action( 'admin_menu', array( &$this, 'init' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'scripts' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'stylesheets' ) );
		add_action( 'wp_ajax_jmembers_membership_add', array( &$this, 'ajax_add_membership' ) );
		add_action( 'wp_ajax_jmembers_membership_delete', array( &$this, 'ajax_delete_membership' ) );
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
	<h3 id="package-<?php echo $package->ID ?>"></h3>
	<div id="package-<?php echo $package->ID ?>">
	</div>
<?php
	endforeach;
?>
		</div>
		<h4><?php _e( 'Add New Package', 'jmembers' ) ?></h4>
		<form id="package-add" action="" method="post">
			<?php wp_nonce_field( 'package_add', 'jmember_nonce' ) ?>
			<input type="hidden" name="membership_id" id="membership_id" value="<?php echo $membership->ID ?>" />
			<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="">Algo</label></th>
					<td><input type="text" /></td>
				</tr>
			</tbody>
			</table>
			<p class="form-submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e( 'Add New', 'jmembers' ) ?>" />
		</form>
		<a href="" onclick="" id="membership-delete" rel="<?php echo $membership->ID ?>"><?php _e( 'Delete membership', 'jmembers' ) ?></a>
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
		</p>
	</form>
</div>
<?php
	}

	public function ajax_add_membership(){
		if( empty( $_POST ) || !wp_verify_nonce( $_POST['nonce'], 'membership_add' )  ):
			echo __( 'Error passing security check.', 'jmembers' );
		else:
			$membership = new JMembers_Membership( $_POST['name'] );

			if( $membership->add() == TRUE ):
				echo 1;
			else:
				echo __( 'Error adding membership.', 'jmembers' );
			endif;
		endif;

		die();
	}

	public function ajax_delete_membership(){
		$membership_id = $_POST['membership_id'];

		$result = JMembers_Membership::delete($membership_id);

		if( $result == TRUE )
			echo 1;
		else
			echo __( 'Error deleting membership.', 'jmembers' );

		die();
	}
}
new JMembers_Page_Memberships();