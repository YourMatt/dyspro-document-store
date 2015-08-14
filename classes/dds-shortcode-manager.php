<?php

class dds_shortcode_manager {

   private $db;

   public function __construct () {
      global $wpdb;
      $this->db = &$wpdb;
   }

   public function build_file_store_page ($attributes) {
      $type = $attributes["type"];

      $dm = new dds_document_manager ();
      $documents = $dm->get_documents ($type);

      return print_r ($documents, true);

   }

}