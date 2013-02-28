<?php
Class JMembers_Page_Settings{
	public function __construct(){
		add_action( 'admin_menu', array( &$this, 'init' ) );
	}

	public function init(){
		add_submenu_page( 'jmembers', __( 'Settings', 'jmembers' ), __( 'Settings', 'jmembers' ), 'manage_options', 'jmembers_settings', array( &$this, 'page' ) );
	}

	public function scripts(){

	}

	public function stylesheets(){

	}

	public function page(){
?>
<div class="wrap">
	<h2><?php _e( 'Settings', 'jmembers' ) ?></h2>
</div>
<?php
	}
}
new JMembers_Page_Settings();