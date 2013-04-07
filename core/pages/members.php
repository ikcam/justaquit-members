<?php
Class JMembers_Page_Members{
	public function __construct(){
		add_action( 'admin_menu', array( &$this, 'init' ) );
	}

	public function init(){
		add_submenu_page( 'jmembers', __( 'Manage Members', 'jmembers' ), __( 'Manage Members', 'jmembers' ), 'manage_options', 'jmembers_members', array( &$this, 'page' ) );
	}

	public function stylesheets(){

	}

	public function scripts(){

	}

	public function page(){
?>
<div class="wrap">
	<h2><?php _e( 'Manage Members', 'jmembers' ) ?></h2>

	
</div>
<?php
	}
}
new JMembers_Page_Members();