<?php
Class JMembers_Page_Packages{
	public function __construct(){
		add_action( 'admin_enqueue_scripts', array( &$this, 'scripts' ) );
		add_action( 'wp_ajax_jmembers_package_add', array( &$this, 'ajax_package_add' ) );
		add_action( 'wp_ajax_jmembers_package_update', array( &$this, 'ajax_package_update' ) );
		add_action( 'wp_ajax_jmembers_package_delete', array( &$this, 'ajax_package_delete' ) );
	}

	public function scripts(){
		wp_register_script( 'jmembers_page_packages', JMEMBERS_URL . 'core/javascript/page-packages.js', array( 'jquery' ) );
		wp_enqueue_script( 'jmembers_page_packages' );
	}


	public function ajax_package_add(){
		if( empty( $_POST ) || !wp_verify_nonce( $_POST['nonce'], 'package_add' )  ):
			echo __( 'Error passing security check.', 'jmembers' );
			die();
		endif;

		$membership_id   = $_POST['membership_id'];
		$duration        = $_POST['duration'];
		$duration_type   = $_POST['duration_type'];
		$price           = $_POST['price'];
		$billing         = $_POST['billing'];
		$description     = $_POST['description'];
		$expired_package = $_POST['expired_package'];
		$display         = array(
			'display_registration' => $_POST['display_registration'],
			'display_upgrade'      => $_POST['display_upgrade'],
			'display_extend'       => $_POST['display_extend']
		);
		$menu_order      = $_POST['menu_order'];

		$package = new JMembers_Package( $membership_id, $duration, $duration_type, $price, $billing, $description, $expired_package, $display, $menu_order );

		if( !$package->add() ):
			echo __( 'Error adding package.', 'jmembers' );
			die();
		endif;

		echo  1;

		die();
	}

	public function ajax_package_update(){
		if( empty( $_POST ) || !wp_verify_nonce( $_POST['nonce'], 'package_update' )  ):
			echo __( 'Error passing security check.', 'jmembers' );
			die();
		endif;

		$package_id      = $_POST['package_id'];
		$membership_id   = $_POST['membership_id'];
		$duration        = $_POST['duration'];
		$duration_type   = $_POST['duration_type'];
		$price           = $_POST['price'];
		$billing         = $_POST['billing'];
		$description     = $_POST['description'];
		$expired_package = $_POST['expired_package'];
		$display         = array(
			'display_registration' => $_POST['display_registration'],
			'display_upgrade'      => $_POST['display_upgrade'],
			'display_extend'       => $_POST['display_extend']
		);
		$menu_order      = $_POST['menu_order'];

		$package = new JMembers_Package( $membership_id, $duration, $duration_type, $price, $billing, $description, $expired_package, $display, $menu_order );

		if( !$package->update( $package_id ) ):
			echo __( 'Error updating package.', 'jmembers' );
			die();
		endif;

		echo  1;

		die();
	}

	public function ajax_package_delete(){
		if( empty( $_POST ) || !wp_verify_nonce( $_POST['nonce'], 'package_delete' )  ):
			echo __( 'Error passing security check.', 'jmembers' );
			die();
		endif;

		$result = JMembers_Package::delete($_POST['package_id']);

		if( $result == FALSE ):
			echo __( 'Error deleting package.', 'jmembers' );
			die();
		endif;

		echo 1;

		die();
	}
}
new JMembers_Page_Packages();