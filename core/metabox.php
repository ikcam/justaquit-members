<?php
Class JMembers_Metabox{
	public function __construct(){
		add_action( 'add_meta_boxes', array( &$this, 'init' ) );
		add_action( 'save_post', array( &$this, 'save' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'scripts' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'stylesheets' ) );
		add_action( 'wp_ajax_jmembers_more_link', array( &$this, 'ajax_more_link' ) );
	}

	public function init(){
		add_meta_box( 'jmembers_metabox', __('Membership Information', 'jmembers'), array( &$this, 'metabox' ), 'post', 'side', 'high' );
		add_meta_box( 'jmembers_metabox', __('Membership Information', 'jmembers'), array( &$this, 'metabox' ), 'page', 'side', 'high' );
	}

	public function scripts(){
		wp_register_script( 'jmembers_metabox', JMEMBERS_URL . 'core/javascript/metabox.js' );
		wp_enqueue_script( 'jmembers_metabox' );
	}

	public function stylesheets(){

	}

	public function metabox( $post ){
		$settings = get_post_meta( $post->ID, '_jmembers_settings', true );
		var_dump($settings);
?>
	<?php wp_nonce_field( 'save_metabox', 'jmember_nonce' ) ?>

	<h4><?php _e( 'Pay per post:' ) ?></h4>
	<p>
		<input type="radio" name="payperpost" id="payperpost_0" value="0" /> <label for="payperpost_0"><?php _e( 'Yes', 'jmembers' ) ?></label>
		- <label for="price"><?php _e( 'Price:', 'jmembers' ) ?></label> <input type="text" name="price" id="price" value="" /> 
		<br />
		<input type="radio" name="payperpost" id="payperpost_1" value="1" /> <label for="payperpost_1"><?php _e( 'No', 'jmembers' ) ?></label>
	</p>

	<div id="jmembers-more" style="display:none">
	<h4><?php _e( 'Available for Membership:', 'jmembers' ) ?></h4>
<?php
		$memberships = get_memberships();
		if( $memberships == NULL ):
			echo __( 'You haven\'t created any membership yet.', 'jmembers' );
		else:
			$i = 0;

			foreach( $memberships as $membership ):
?>
	<p>
		<input type="checkbox" name="membership_<?php echo $i; ?>" id="membership_<?php echo $i; ?>" value="<?php echo $membership->ID ?>" /> <label for="membership_<?php echo $i; ?>"><?php echo $membership->name ?></label>
	</p>
<?php
				$i++;
			endforeach;
?>
	<input type="hidden" name="memberships_count" id="memberships_count" value="<?php echo $i ?>" />

	<h4><?php _e( 'Dripping:', 'jmembers' ) ?></h4>
<?php
			$i = 0;

			foreach( $memberships as $membership ):
?>
	<p>
		<input type="text" name="dripping_<?php echo $i; ?>" id="dripping_<?php echo $i; ?>" value="0"> <label for="dripping_<?php echo $i; ?>"><?php echo $membership->name ?></label>
	</p>
<?php
				$i++;
			endforeach;
		endif;
?>
	</div>
	<a href="" id="jmembers-more-link"><?php _e( 'Show more options...', 'jmembers' ) ?></a>
	<img class="waiting" src="<?php echo admin_url( ) ?>images/wpspin_light.gif" height="16" width="16" style="display:none" />
<?php
	}

	public function save( $post_id ){
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return;

		if( empty( $_POST['jmember_nonce'] ) || !wp_verify_nonce( $_POST['jmember_nonce'], 'save_metabox' )  )
			return;

		if ( 'page' == $_POST['post_type'] ):
			if ( !current_user_can( 'edit_page', $post_id ) ):
				return;
			endif;
		else:
			if ( !current_user_can( 'edit_post', $post_id ) ):
				return;
			endif;
		endif;

		$post_ID = $_POST['post_ID'];
		$memberships_count = $_POST['memberships_count'];
		$available = array();

		for( $i = 0; $i < $memberships_count; $i++ ){
			if( isset($_POST['membership_'.$i]) ):
				$available[$_POST['membership_'.$i]] = $_POST['dripping_'.$i];
			endif;
		}

		$jmembers_settings = array(
			'payperpost' => $_POST['payperpost'],
			'price'      => $_POST['price'],
			'available'  => $available
		);

		add_post_meta($post_ID, '_jmembers_settings', $jmembers_settings, true) or update_post_meta($post_ID, '_jmembers_settings', $jmembers_settings);
	}

	public function ajax_more_link(){
		if( $_POST['active'] == 1 ):
			echo __( 'Show more options...', 'jmembers' );
		else:
			echo __( 'Hide', 'jmembers' );
		endif;

		die();
	}
}
new JMembers_Metabox();