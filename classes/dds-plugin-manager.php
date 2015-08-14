<?php

class dds_plugin_manager {

   private $db;

   public function __construct () {
      global $wpdb;

      $this->db = &$wpdb;

   }

   // run when activating the plugin
   public function activate () {

      // create documents table if doesn't already exist
      if ($this->db->get_var ("SHOW TABLES LIKE '" . DDS_TABLE_DOCUMENTS . "'") != DDS_TABLE_DOCUMENTS) {

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
         $this->db->query ($sql);

      }

   }

   // run when uninstalling the plugin
   public function uninstall () {

      // delete all documents and storage folder
      // TODO: Fill in functionality

      // delete the documents table
      $sql = '
         DROP TABLE IF EXISTS ' . DDS_TABLE_DOCUMENTS;
      $this->db->query ($sql);

   }

}