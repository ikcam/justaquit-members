<?php
Class JMembers_Filters{
	public function __construct(){
		add_filter( 'the_content', array( &$this, 'hide_content' ) );
	}

	public function hide_content($content){
		global $post;
		global $jmembers_settings;
		$settings = get_post_meta( $post->ID, '_jmembers_settings', true );

		if( is_array($settings) && array_key_exists( 0, $settings['available']) )
			return $content;

		if( $jmembers_settings['hide_content'] == 1 ):
			if( is_administrator() ):
				return $content;
			endif;

			$user_id = get_current_user_id();

			if( $user_id == 0 ):
				return $jmembers_settings['message_hide_content'];
			endif;

			if( has_bought_post( $post->ID, $user_id ) ):
				return $content;
			endif;

			$user_package = get_user_meta( $user_id, '_jmembers_package' );
			$post_package = get_post_meta( $post->ID, '_jmembers_settings', true );

			if( is_array( $post_package ) && array_key_exists( $user_package, $post_package['available']) ):
				return $content;
			endif;
			
			return $jmembers_settings['message_hide_content'];
		endif;
	
		return $content;		
	}
}
new JMembers_Filters();