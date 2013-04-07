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

		$display = array(
			'display_registration' => $_POST['display_registration'],
			'display_upgrade'      => $_POST['display_upgrade'],
			'display_extend'       => $_POST['display_extend']
		);

		$package                  = new JMembers_Package();
		$package->membership_id   = (int) $_POST['membership_id'];
		$package->duration        = (int) $_POST['duration'];
		$package->duration_type   = (int) $_POST['duration_type'];
		$package->price           = (float) $_POST['price'];
		$package->billing         = (int) $_POST['billing'];
		$package->description     = (String) $_POST['description'];
		$package->expired_package = (int) $_POST['expired_package'];
		$package->display         = serialize($display);
		$package->menu_order      = (int) $_POST['menu_order'];

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

		$display = array(
			'display_registration' => $_POST['display_registration'],
			'display_upgrade'      => $_POST['display_upgrade'],
			'display_extend'       => $_POST['display_extend']
		);

		$package                  = new JMembers_Package();
		$package->membership_id   = (int) $_POST['membership_id'];
		$package->duration        = (int) $_POST['duration'];
		$package->duration_type   = (int) $_POST['duration_type'];
		$package->price           = (float) $_POST['price'];
		$package->billing         = (int) $_POST['billing'];
		$package->description     = (String) $_POST['description'];
		$package->expired_package = (int) $_POST['expired_package'];
		$package->display         = serialize($display);
		$package->menu_order      = (int) $_POST['menu_order'];

		if( !$package->update( $_POST['package_id'] ) ):
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