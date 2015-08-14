<?php

class dds_shortcode_manager {

   private $db;

   public function __construct () {
      global $wpdb;

      $this->db = &$wpdb;

   }

   public function build_file_store_page ($attributes) {

      $type = $attributes["type"];

      $test = $this->db->get_var ("SHOW TABLES LIKE '" . DDS_TABLE_DOCUMENTS . "'");

      return "test: " . $test;

   }

}