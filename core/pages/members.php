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

	<form id="members-filter" action="" method="get">
		<p class="search-box">
			<label class="screen-reader-text" for="members-search-input"><? _e( 'Search Members:', 'jmembers' ) ?></label>
			<input type="search" id="member-search-input" name="s" value="<?php if( isset( $_GET['s'] ) ) echo $_GET['s']; ?>" />
			<input type="submit" name id="search-submit" class="button" value="<?php _e( 'Search Members', 'jmembers' ) ?>" />
		</p>

		<input type="hidden" name="member_status" class="member_status_page" value="all" />
		<input type="hidden" name="member_type" class="member_type_page" value="all" />
		<?php wp_nonce_field( 'members_page', 'jmembers_nonce' ) ?>

		<table class="wp-list-table widefat fixed members" cellspacing="0">
			<thead>
			<tr>
				<th scope="col" id="cb" class="manage-column column-cb check-column">
					<label class="screen-reader-text" for="cb-select-all-1">Elegir todo</label>
					<input id="cb-select-all-1" type="checkbox">
				</th>
				<th scope="col" id="username" class="manage-column column-username sortable desc">
					<a href="http://localhost/wordpress/wp-admin/users.php?orderby=login&amp;order=asc"><span>Nombre de usuario</span><span class="sorting-indicator"></span></a>
				</th>
				<th scope="col" id="name" class="manage-column column-name sortable desc">
					<a href="http://localhost/wordpress/wp-admin/users.php?orderby=name&amp;order=asc"><span>Nombre</span><span class="sorting-indicator"></span></a>
				</th>
				<th scope="col" id="email" class="manage-column column-email sortable desc">
					<a href="http://localhost/wordpress/wp-admin/users.php?orderby=email&amp;order=asc"><span>Correo electrónico</span><span class="sorting-indicator"></span></a></th><th scope="col" id="role" class="manage-column column-role" style="">Perfil</th><th scope="col" id="posts" class="manage-column column-posts num" style="">Entradas
				</th>
			</tr>
			</thead>
		</table>
	</form>
</div>
<?php
	}
}

new JMembers_Page_Members();