<?php
global $wpdb;

// define paths
define ('DDS_BASE_PATH', dirname (__FILE__));
define ('DDS_BASE_WEB_PATH', plugin_dir_url (__FILE__));

// database tables - will use meta system so all of these can be removed later
define ('DDS_TABLE_DOCUMENTS', $wpdb->prefix . 'dds_documents');

// load support files
require_once (DDS_BASE_PATH . '/classes/dds-plugin-manager.php');
require_once (DDS_BASE_PATH . '/classes/dds-shortcode-manager.php');