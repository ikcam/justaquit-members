<?php
Class JMembers_Page_Settings{
	public function __construct(){
		add_action( 'admin_menu', array( &$this, 'init' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'scripts' ) );
		add_action( 'admin_enqueue_styles', array( &$this, 'stylesheets' ) );
		add_action( 'admin_init', array( &$this, 'settings' ) );
	}

	public function init(){
		add_submenu_page( 'jmembers', __( 'Settings', 'jmembers' ), __( 'Settings', 'jmembers' ), 'manage_options', 'jmembers_settings', array( &$this, 'page' ) );
	}

	public function settings(){
		register_setting( 'jmembers', 'jmembers_settings', array( &$this, 'save' ) );
	}

	public function scripts(){
		wp_register_script( 'jmembers_page_settings', JMEMBERS_URL . 'core/javascript/page-settings.js', array( 'jquery-ui-tabs', 'jquery-ui-core', 'jquery' ) );
		wp_enqueue_script( 'jmembers_page_settings' );
		wp_enqueue_script( 'editor' );
    wp_enqueue_script( 'thickbox' );
		wp_enqueue_script( 'media-upload' );
	}

	public function stylesheets(){
    wp_enqueue_style( 'thickbox' );
	}

	public function page(){
		// Hide all content by default

?>
<div class="wrap">
	<h2><?php _e( 'Settings', 'jmembers' ) ?></h2>

	<form method="post" action="options.php">
		<?php settings_fields('jmembers'); ?>
<?php
	$settings = get_option( 'jmembers_settings' );
?>
		<div id="tabs-1">
			<ul>
				<li><a href="#tab-general"><?php _e( 'General', 'jmembers' ) ?></a></li>
				<li><a href="#tab-messages"><?php _e( 'Messages', 'jmembers' ) ?></a></li>
				<li><a href="#tab-email"><?php _e( 'E-mail', 'jmembers' ) ?></a></li>
			</ul>
			<div id="tab-general">
				<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><label for="hide_content"><?php _e( 'Hide content?', 'jmembers' ) ?></label></th>
						<td>
							<input type="radio" name="jmembers_settings[hide_content]" id="hide_content_1" value="1" <?php if( $settings['hide_content'] == 1 ) echo 'checked'; ?> /> <label for="hide_content_1"><?php _e( 'Yes', 'jmembers' ) ?></label>
							&nbsp;
							<input type="radio" name="jmembers_settings[hide_content]" id="hide_content_0" value="0" <?php if( $settings['hide_content'] == 0 ) echo 'checked'; ?> /> <label for="hide_content_0"><?php _e( 'No', 'jmembers' ) ?></label>
						</td>
					</tr>
				</tbody>
				</table>
			</div>
			<div id="tab-messages">
				<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><label for="message_hide_content"><?php _e( 'Hide content message:', 'jmembers' ) ?></label></th>
						<td>
							<?php wp_editor($settings['message_hide_content'], 'message_hide_content', array( 'textarea_name' => 'jmembers_settings[message_hide_content]', 'teeny' => true ) 	); ?>
						</td>
					</tr>
				</tbody>
				</table>
			</div>
			<div id="tab-email">
				Email
			</div>
		</div>
		<p class="form-submit">
			<input type="submit" class="button-primary" name="submit" id="submit" value="<?php _e( 'Save Changes', 'jmembers' ) ?>" />
		</p>
	</form>
</div>
<?php
	}

	public function save($input){
		$input['message_hide_content'] = $input['message_hide_content'];

		return $input;
	}

}
new JMembers_Page_Settings();