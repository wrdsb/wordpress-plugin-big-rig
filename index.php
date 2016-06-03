<?php
/*
* Plugin Name: WRDSB BIG RIG
* Plugin URI: https://github.com/wrdsb/wordpress-plugin-rig
* Description: Business Intelligence Gathering/Runtime Intelligence Gathering for WordPress
* Author: WRDSB
* Author URI: https://github.com/wrdsb
* Version: 0.0.3
* License: GNU AGPLv3
* GitHub Plugin URI: wrdsb/wordpress-plugin-big-rig
* GitHub Branch: master
*/
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'BIG_RIG_PATH' ) ) {
	define( 'BIG_RIG_PATH', plugin_dir_path( __FILE__ ) );
}

/** Load required files */
require_once(__DIR__ . "/inc/BigRig.php");

/** Boot up */
BigRig::get_instance();

