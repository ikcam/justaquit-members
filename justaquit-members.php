<?php
/*
Plugin Name: JustAquit Members
Plugin URI: http://justaquit.com
Description: This plugins to manage members.
Version: 1.0
Author: Irving Kcam
Author URI: http://ikcam.com
License: GPL2
*/

Class JA_Members {
	public function __construct(){
		add_action( 'wp_enqueue_scripts', array( &$this, 'register_scripts' ) );
	}

	public function register_scripts(){
		wp_enqueue_script( 'jquery' );
	}

	public function register_stylesheets(){

	}
}

new JA_Members();
?>
