<?php
Class JMembers_Page_Reports{
	public function __construct(){
		add_action( 'admin_menu', array( &$this, 'init' ) );
	}

	public function init(){
		add_submenu_page( 'jmembers', __( 'Reports', 'jmembers' ), __( 'Reports', 'jmembers' ), 'manage_options', 'jmembers_reports', array( &$this, 'page' ) );
	}

	public function page(){
?>
<div class="wrap">
	<h2><?php _e( 'Reports', 'jmembers' ) ?></h2>

	<form method="get">
		<input type="hidden" name="page" id="page" value="<?php echo $_GET['page'] ?>" />
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="package"><?php _e('Package', 'jmembers') ?></label></th>
				<td>
					<select name="package" id="package">
						<option value="0"><?php _e( 'All', 'jmembers' ) ?></option>
<?php
	$packages = get_packages();
	foreach( $packages as $package ):
?>
						<option value="<?php echo $package->ID ?>"><?php echo get_package_name( $package->ID ) ?></option>
<?php
	endforeach;
?>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="status"><?php _e( 'Status', 'jmembers' ) ?></label></th>
				<td>
					<select name="status" id="status">
						<option value="all"><?php _e( 'All', 'jmembers' ) ?></option>
						<option value="Active"><?php _e( 'Active', 'jmembers' ) ?></option>
						<option value="Inactive"><?php _e( 'Inactive', 'jmembers' ) ?></option>
						<option value="Expired"><?php _e( 'Expired', 'jmembers' ) ?></option>
					</select>
				</td>
			</tr>
		</table>
		<p class="form-submit"><input class="button-primary" type="submit" value="<?php _e( 'Filter Results', 'jmembers' ) ?>" /></p>
	</form>

<?php
	if( !empty( $_GET ) ):
		$users = $this->get_members($_GET);
	else:
		$users = $this->get_members();
	endif;
?>
	<h3>The Report</h3>
	<table class="wp-list-table widefat fixed users" cellspacing="0">
		<thead>
			<tr>
				<th><?php _e( 'ID', 'jmembers' ) ?></th>
				<th><?php _e( 'User Login', 'jmembers' ) ?></th>
				<th><?php _e( 'Name', 'jmembers' ) ?></th>
				<th><?php _e( 'Email', 'jmembers' ) ?></th>
				<th><?php _e( 'Package', 'jmembers' ) ?></th>
				<th><?php _e( 'Join Date', 'jmembers' ) ?></th>
				<th><?php _e( 'Expiration Date', 'jmembers' ) ?></th>
				<th><?php _e( 'Last Payment', 'jmembers' ) ?></th>
				<th><?php _e( 'Processor', 'jmembers' ) ?></th>
				<th><?php _e( 'Profile ID', 'jmembers' ) ?></th>
			</tr>
		</thead>
		<tbody>
<?php
	foreach( $users as $user ):
		$payment = unserialize( get_user_meta( $user->ID, '_payment', true ) );
?>
			<tr>
				<td><?php echo $user->ID ?></td>
				<td><?php echo $user->user_login ?></td>
				<td><?php echo $user->display_name ?></td>
				<td><?php echo $user->user_email ?></td>
				<td><?php echo get_package_name( get_user_meta( $user->ID, '_package_id', TRUE ) ) ?></td>
				<td><?php echo date( 'd/m/Y', get_user_meta( $user->ID, '_datetime_packjoin', true ) ) ?></td>
				<td><?php echo date( 'd/m/Y', get_user_meta( $user->ID, '_datetime_expire', true ) ) ?></td>
				<td></td>
				<td><?php echo $payment['processor'] ?></td>
				<td><?php echo $payment['profile_id'] ?></td>
			</tr>
<?php
	endforeach;
?>
		</tbody>
	</table>
</div>
<?php
	}

	private function get_members( $args = null ){
		global $wpdb;

		$defaults = array(
			'membership' => 0,
			'package'    => 0,
			'status'     => 'all'
		);

		$args = wp_parse_args( $args, $defaults );


		extract( $args, EXTR_SKIP );
		
		if( $package != 0 ):
			if( $status != 'all' ):
				$args = array(
					'meta_query' => array(
						array(
							'key'   => '_package',
							'value' => $package
						),
						array(
							'key'   => '_status',
							'value' => $status
						)
					)
				);
				$users = get_users( $args );
			else:
				$args = array(
					'meta_key'   => '_package',
					'meta_value' => $package
				);
				$users = get_users( $args );
			endif;
		else:
			if( $membership != 0 ):
				$membership = get_membership( $membership );
				$packages_ids = array();	
				
				if( $membership != null ):
					$packages = get_packages( 'membership='.$membership->ID );
				else:
					$packages = get_packages();
				endif;

				foreach( $packages as $package ):
					array_push($packages_ids, intval($package->ID));
				endforeach;

				if( $status != 'all' ):
					$args = array(
						'meta_query' => array(
							array(
								'key'   => '_package',
								'value' => $packages_ids
							),
							array(
								'key'   => '_status',
								'value' => $status
							)
						)
					);
					$users = get_users( $args );
				else:
					$args = array(
						'meta_query' => array(
							array(
								'key'     => '_package',
								'value'   => $packages_ids
							)
						)
					);
					$users = get_users( $args );
				endif;
			else:
				if( $status != 'all' ):
					$args = array(
						'meta_key'   => '_status',
						'meta_value' => $status
					);
					$users = get_users( $args );
				else:
					$users = get_users();
				endif;
			endif;
		endif;

		return $users;
	}
}
new JMembers_Page_Reports();