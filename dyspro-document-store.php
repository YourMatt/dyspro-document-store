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

// add installation script
register_activation_hook (__FILE__, array ($dds_plugin_manager, 'activate'));
register_uninstall_hook (__FILE__, array ($dds_plugin_manager, 'uninstall'));

// set up shortcodes
add_shortcode ('dds_file_store', 'dds_build_file_store_page');

function dds_build_file_store_page ($attributes) {
    $type = $attributes["type"];

    global $wpdb;
    $test = $wpdb->get_var ("SHOW TABLES LIKE '" . DDS_TABLE_DOCUMENTS . "'");

    return "test: " . $test;

}
