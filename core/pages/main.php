<?php
Class JMembers_Page_Main {
	public function __construct(){
		add_action( 'admin_menu', array( &$this, 'init' ) );
	}

	public function init(){
		add_menu_page( __( 'Members Control', 'jmembers' ), __( 'Members Control', 'jmembers' ), 'manage_options', 'jmembers', array( &$this, 'page' ), '', 99);
		add_action( 'admin_enqueue_scripts', array( &$this, 'scripts' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'stylesheets' ) );
	}

	public function stylesheets(){

	}

	public function scripts(){

	}

	public function page(){
?>
<div class="wrap">
	<h2><?php _e( 'Members Control', 'jmember' ) ?></h2>
</div>
<?php
	}

}
new JMembers_Page_Main();