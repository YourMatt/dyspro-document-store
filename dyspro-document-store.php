<?php
/*
Plugin Name: Dyspro Document Store
Plugin URI:
Description: Adds capability to view and manage documents of a given type.
Version: 0.9
Author: Dyspro Media
Author URI: http://dyspromedia.com
*/

// load configuration variables
require_once (dirname (__FILE__) . '/config.php');

// initialize objects
$dds_plugin_manager = new dds_plugin_manager ();
$dds_shortcode_manager = new dds_shortcode_manager ();
$dds_activity_manager = new dds_activity_manager ();

// add installation script
register_activation_hook (__FILE__, array ($dds_plugin_manager, 'activate'));
register_uninstall_hook (__FILE__, array ($dds_plugin_manager, 'uninstall'));

// set up shortcodes
add_shortcode ('dds_file_store', array ($dds_shortcode_manager, 'build_file_store_page'));

// process any user activity
if ($_POST[DDS_MANAGEMENT_NONCE]) {
   $dds_activity_manager->process_management_forms ();
   $custom_error_message = $dds_activity_manager->error_message;
   $custom_success_message = $dds_activity_manager->success_message;
}