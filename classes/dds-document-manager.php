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

      $base_document_data = $this->db->get_results ($sql);

      // set document path and validate document storage
      $document_data = [];
      $base_path = DDS_UPLOAD_BASE_PATH . '/' . $type . '/';
      $base_web_path = DDS_UPLOAD_WEB_PATH . '/' . $type . '/';
      foreach ($base_document_data as $base_document) {

         // skip files where the file isn't found
         if (! file_exists ($base_path . $base_document->file_name)) continue;

         // set the http path to the file
         $base_document->file_path = $base_web_path . $base_document->file_name;

         // add the document to the list
         $document_data[] = $base_document;

      }

      return $document_data;

   }

}