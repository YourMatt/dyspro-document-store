<?php

class dds_plugin_manager {

   // run when activating the plugin
   function activate () {
      global $wpdb;

      // create documents table if doesn't already exist
      if ($wpdb->get_var ("SHOW TABLES LIKE '" . DDS_TABLE_DOCUMENTS . "'") != DDS_TABLE_DOCUMENTS) {

         $sql = '
            CREATE TABLE    ' . DDS_TABLE_DOCUMENTS . '
            (               id              mediumint(8)    NOT NULL AUTO_INCREMENT
            ,               type            varchar(32)     NOT NULL
            ,               author          bigint(20)      NOT NULL
            ,               file_name       varchar(255)    NOT NULL
            ,               title           varchar(255)    NOT NULL
            ,               description     longtext
            ,               date_updated    datetime        NOT NULL
            ,               UNIQUE KEY      id (id))
         ';
         $wpdb->query ($sql);

         //require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
         //dbDelta ($sql);

      }

   }

   // run when uninstalling the plugin
   function uninstall () {
      global $wpdb;

      // delete all documents and storage folder
      // TODO: Fill in functionality

      // delete the documents table
      $sql = '
         DROP TABLE IF EXISTS ' . DDS_TABLE_DOCUMENTS;
      $wpdb->query ($sql);

   }

}