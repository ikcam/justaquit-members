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

define( 'JMEMBERS_PATH' , plugin_dir_path( __FILE__ ));
define( 'JMEMBERS_URL', plugin_dir_url( __FILE__ ) );
require( JMEMBERS_PATH . 'core/classes/Membership.php' );
require( JMEMBERS_PATH . 'core/classes/Package.php' );
require( JMEMBERS_PATH . 'core/pages/main.php' );
require( JMEMBERS_PATH . 'core/pages/memberships.php' );
require( JMEMBERS_PATH . 'core/pages/packages.php' );
require( JMEMBERS_PATH . 'core/pages/settings.php' );
require( JMEMBERS_PATH . 'core/functions/get.php' );
require( JMEMBERS_PATH . 'core/metabox.php' );

Class JA_Members {
	public function __construct(){
		register_activation_hook( __FILE__, array( &$this, 'install' ) );
	}

	public function install(){
		$db_version = get_option( 'ja_members_db_version' );

		if( $db_version == 1 ):
			return;
		else:
			add_option( 'ja_members_db_version', 1 );

			global $wpdb;
			
			$table = $wpdb->prefix.'jm_memberships';
			$sql = "CREATE TABLE $table (
				ID mediumint(9) NOT NULL AUTO_INCREMENT,
				name varchar(100) NOT NULL,
				UNIQUE KEY ID (ID)
			);";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);

			$table = $wpdb->prefix.'jm_packages';
			$sql = "CREATE TABLE $table (
				ID mediumint(9) NOT NULL AUTO_INCREMENT,
				membership_id mediumint(9) NOT NULL,
				duration mediumint(9) DEFAULT 0 NOT NULL,
				duration_type mediumint(9) DEFAULT 0 NOT NULL,
				price decimal(5,2) DEFAULT 0 NOT NULL,
				billing mediumint(9) DEFAULT 0 NOT NULL,
				description longtext NULL,
				expired_package mediumint(9) DEFAULT 0 NOT NULL,
				display longtext NULL,
				menu_order mediumint(9) DEFAULT 0 NOT NULL,
				payment longtext NULL,
				UNIQUE KEY ID (ID)
			);";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);

			$table = $wpdb->prefix.'jm_puchase_posts';
			$sql = "CREATE TABLE $table (
				ID mediumint(9) NOT NULL AUTO_INCREMENT,
				post_id mediumint(9) NOT NULL,
				user_id mediumint(9) NOT NULL,
				expire_date bigint(12) NULL
			);";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);

		endif;
	}
}

new JA_Members();
?>
