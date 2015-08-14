<?php

class dds_document_manager {

   private $db;

   public function __construct () {
      global $wpdb;
      $this->db = &$wpdb;
   }

   public function get_documents ($type, $sort_field = 'title', $sort_direction = 'asc') {

      // load the data from the database
      // TODO: Pull author name
      $sql = $this->db->prepare ("
         SELECT   id
         ,        type
         ,        author
         ,        file_name
         ,        title
         ,        description
         ,        date_updated
         FROM     " . DDS_TABLE_DOCUMENTS . "
         WHERE    type = %s
         ORDER BY " . $sort_field . " " . $sort_direction,
         $type);

      $document_data = $this->db->get_results ($sql);

      // set document path and validate document storage
      // TODO: Add functionality

      return $document_data;

   }

}