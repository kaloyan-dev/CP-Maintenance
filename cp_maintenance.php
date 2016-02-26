<?php
/**
 * Plugin Name: CP Maintenance
 * Description: A maintenance mode plugin which allows you to setup a maintenance page, redirect to a specific URL or use custom HTML.
 * Version: 1.0.2
 * Author: Kaloyan Ivanov
 * Author URI: http://codepeace.net/
 * License: GPL2
 */

# Define constants
defined( 'CPM_DIR' ) ? NULL : define( 'CPM_DIR', dirname( __FILE__ ) . DIRECTORY_SEPARATOR );

# Include the main class
include_once( CPM_DIR . 'class/CP_Maintenance.php' );

# Install
register_activation_hook( __FILE__, array( 'CP_Maintenance', 'install' ) );

# Uninstall
register_uninstall_hook( __FILE__, array('CP_Maintenance', 'uninstall' ) );

# Include admin scripts
include_once( CPM_DIR . 'admin/cp_admin.php' );

# Include front-end scripts
include_once( CPM_DIR . 'front/cp_front.php' );
