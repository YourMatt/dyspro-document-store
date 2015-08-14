<?php
global $wpdb;

// define paths
define ('DDS_BASE_PATH', dirname (__FILE__));
define ('DDS_BASE_WEB_PATH', plugin_dir_url (__FILE__));

// database tables - will use meta system so all of these can be removed later
define ('DDS_TABLE_DOCUMENTS', $wpdb->prefix . 'dds_documents');

// default settings
/*define ('DBD_DEFAULT_USER_PASSWORD', 'business'); // when adding a new company, this will be the initial password for the user account // TODO: Move to settings
define ('DBD_GOOGLE_MAPS_DEFAULT_ZOOM', 2);
define ('DBD_GOOGLE_MAPS_ADDRESSED_ZOOM', 16);
define ('DBD_GOOGLE_MAPS_DEFAULT_CENTER_LOCATION', 'United States');*/

// load support files
require_once (DDS_BASE_PATH . '/classes/dds-plugin-manager.php');