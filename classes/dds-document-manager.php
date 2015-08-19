<?php

class dds_document_manager {

   public $updated_document_title;
   private $db;

   public function __construct () {
      global $wpdb;
      $this->db = &$wpdb;
   }

   public function get_documents ($type, $sort_field = 'title', $sort_direction = 'asc') {

      // load the data from the database
      // TODO: Pull author name
      $sql = $this->db->prepare ('
         SELECT   id
         ,        type
         ,        author
         ,        file_name
         ,        title
         ,        description
         ,        date_updated
         FROM     ' . DDS_TABLE_DOCUMENTS . '
         WHERE    type = %s
         ORDER BY ' . $sort_field . ' ' . $sort_direction,
         $type);

      $base_document_data = $this->db->get_results ($sql);

      // set document path and validate document storage
      $document_data = [];
      $base_path = DDS_UPLOAD_BASE_PATH . '/' . $type . '/';
      $base_web_path = DDS_UPLOAD_WEB_PATH . '/' . $type . '/';
      foreach ($base_document_data as $base_document) {

         // skip files where the file isn't found
         if (! file_exists ($base_path . $base_document->file_name)) continue;

         // set the paths to the file
         $base_document->local_path = $base_path . $base_document->file_name;
         $base_document->file_path = $base_web_path . $base_document->file_name;

         // add the document to the list
         $document_data[] = $base_document;

      }

      return $document_data;

   }

   public function get_document ($document_id) {

      // load the document data from the database
      $sql = $this->db->prepare ('
         SELECT   id
         ,        type
         ,        author
         ,        file_name
         ,        title
         ,        description
         ,        date_updated
         FROM     ' . DDS_TABLE_DOCUMENTS . '
         WHERE    id = %d',
         $document_id);

      $document_data = $this->db->get_results ($sql);
      if (! $document_data) return [];
      $document_data = $document_data[0];

      // verify the file and set the web path
      if (! file_exists (DDS_UPLOAD_BASE_PATH . '/' . $document_data->type . '/' . $document_data->file_name)) return [];
      $document_data->local_path = DDS_UPLOAD_BASE_PATH . '/' . $document_data->type . '/' . $document_data->file_name;
      $document_data->file_path = DDS_UPLOAD_WEB_PATH . '/' . $document_data->type . '/' . $document_data->file_name;

      return $document_data;

   }

   public function delete_document ($document_id) {

      // load the document
      $document_data = $this->get_document ($document_id);
      if (! $document_data) return false;
      $this->updated_document_title = $document_data->title; // set title so is accessible for status messages

      // delete the file from the file system
      if (! unlink ($document_data->local_path)) return false;

      // delete the record from the database
      if (! $this->db->delete (DDS_TABLE_DOCUMENTS, array ('id' => $document_id), array ('%d'))) return false;

      /*
      $sql = $this->db->prepare ('
         DELETE FROM ' . DDS_TABLE_DOCUMENTS . '
         WHERE       id = %d',
         $document_id);
      */

      return true;

   }

}