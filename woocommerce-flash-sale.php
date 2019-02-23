<?php
/*
Plugin Name: Woocommerce Flash Sale
Plugin URI: www.example.com
description: >- a plugin to create flash sale
Version: 1.0
Author: Edo Hartono
Author URI: http://github.com/edohartono
*/

if(!defined('ABSPATH')) exit;

if(!defined("FS_PLUGIN_DIR_PATH"))
	
	define("FS_PLUGIN_DIR_PATH",plugin_dir_path(__FILE__));

if(!defined("FS_PLUGIN_URL"))
	
	define("FS_PLUGIN_URL",plugins_url().'/'.basename(dirname(__FILE__)));

require_once( FS_PLUGIN_DIR_PATH . 'functions.php');

// register_activation_hook( __FILE__, 'active_fs');

// function active_fs() {
// 	if ( is_admin() ) {
// 		$curr_user = wp_get_current_user()-> roles[0];
// 		if( $curr_user == 'administrator')
// 		{
// 			include( FS_PLUGIN_DIR_PATH . 'config.php');
// 		}
// 	}
// }