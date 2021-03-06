<?php
global $wpdb;

// define paths
define ('DDS_BASE_PATH', dirname (__FILE__));
define ('DDS_BASE_WEB_PATH', plugin_dir_url (__FILE__));
define ('DDS_UPLOAD_BASE_PATH', wp_upload_dir()["basedir"] . '/dds-documents');
define ('DDS_UPLOAD_WEB_PATH', wp_upload_dir()["baseurl"] . '/dds-documents');

// database tables - will use meta system so all of these can be removed later
define ('DDS_TABLE_DOCUMENTS', $wpdb->prefix . 'dds_documents');

// additional application constants
define ('DDS_MANAGEMENT_NONCE', 'dds_manage_nonce');

// load support files
require_once (DDS_BASE_PATH . '/classes/dds-plugin-manager.php');
require_once (DDS_BASE_PATH . '/classes/dds-shortcode-manager.php');
require_once (DDS_BASE_PATH . '/classes/dds-document-manager.php');
require_once (DDS_BASE_PATH . '/classes/dds-activity-manager.php');