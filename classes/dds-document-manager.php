<?php

class dds_document_manager {

   public $updated_document_title;
   public $error_message;
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

         // change the date to the local time zone
         $base_document->date_updated = date (
            'Y-m-d H:i',
            strtotime ($base_document->date_updated) + 60 * 60 * get_option ('gmt_offset')
         );

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

      // change the date to the local time zone
      $document_data->date_updated = date (
         'Y-m-d H:i',
         strtotime ($document_data->date_updated) + 60 * 60 * get_option ('gmt_offset')
      );

      return $document_data;

   }

   public function add_document ($title, $description, $type, $author, $file_reference) {
      // file reference in format of: Array ( [name] => Capture.PNG [type] => image/png [tmp_name] => /tmp/phpeWcPTP [error] => 0 [size] => 18462 )

      // validate required fields
      if (!$title || !$description || !$type || !$author || !$file_reference['size']) {
         $this->error_message = 'Missing required fields. Please try again.';
         return false;
      }

      // validate that a file with the same name does not already exist and rename if it does
      $target_path = DDS_UPLOAD_BASE_PATH . '/' . $type;
      $target_name = $file_reference['name'];
      $i = 1;
      while (file_exists ($target_path . '/' . $target_name)) {
         if (strpos ($target_name, '(' . ($i - 1) . ')') !== false) $target_name = str_replace ('(' . ($i - 1) . ')', '(' . $i . ')', $target_name);
         else $target_name = str_replace ('.', '(' . $i . ').', $target_name);
         if ($i > 100) { // break out if excessive looping
            $this->error_message = 'The file name is already in use. Please change the file name and try again.';
            return false;
         }
         $i++;
      }

      // create the target folder if doesn't already exist
      if (!file_exists ($target_path)) {
         if (!mkdir($target_path)) return false;
      }

      // move the file to the uploads folder
      if (!move_uploaded_file ($file_reference['tmp_name'], $target_path . '/' . $target_name)) return false;

      // insert the new record
      if (! $this->db->insert (DDS_TABLE_DOCUMENTS, array (
         'type' => $type,
         'author' => $author,
         'file_name' => $target_name,
         'title' => $title,
         'description' => $description,
         'date_updated' => current_time ('mysql', 1)
      ))) {
         unlink ($target_path . '/' . $target_name);
         return false;
      }

      // return
      $this->updated_document_title = $title;
      return true;

   }

   public function update_document ($document_id, $title, $description, $type, $author, $file_reference) {
      // file reference in format of: Array ( [name] => Capture.PNG [type] => image/png [tmp_name] => /tmp/phpeWcPTP [error] => 0 [size] => 18462 )

      // validate required fields
      if (!$document_id || !$title || !$description || !$type || !$author) {
         $this->error_message = 'Missing required fields. Please try again.';
         return false;
      }

      // load the current document fields
      $document_data = $this->get_document ($document_id);
      if (!$document_data) return false;

      // if file provided, validate that it is not associated to a different document, and rename it if it does
      $target_path = $target_name = '';
      if ($file_reference && $file_reference['size']) {
         $target_path = DDS_UPLOAD_BASE_PATH . '/' . $type;
         $target_name = $file_reference['name'];
         $i = 1;
         while (file_exists ($target_path . '/' . $target_name)) {
            if ($target_name == $document_data->file_name) break;
            if (strpos ($target_name, '(' . ($i - 1) . ')') !== false) $target_name = str_replace ('(' . ($i - 1) . ')', '(' . $i . ')', $target_name);
            else $target_name = str_replace ('.', '(' . $i . ').', $target_name);
            if ($i > 100) { // break out if excessive looping
               $this->error_message = 'The file name is already in use. Please change the file name and try again.';
               return false;
            }
            $i++;
         }
      }

      // move the file to the uploads folder
      if ($target_name) {
         if (!move_uploaded_file($file_reference['tmp_name'], $target_path . '/' . $target_name)) return false;
      }

      // update the record
      if (! $this->db->update (DDS_TABLE_DOCUMENTS,
         array (
            'type' => $type,
            'author' => $author,
            'file_name' => $target_name,
            'title' => $title,
            'description' => $description,
            'date_updated' => current_time ('mysql', 1)),
         array (
            'id' => $document_id)
      )) {
         if ($target_name != $document_data->file_name) // remove file only if new and not overwrite
            unlink ($target_path . '/' . $target_name);
         return false;
      }

      // delete the original file the file name is different from provided
      if ($target_name && $document_data->file_name != $target_name) {
         unlink ($target_path . '/' . $document_data->file_name);
      }

      // return
      $this->updated_document_title = $document_data->title; // set the old title for the status message because that's what was being updated
      return true;

   }

   public function delete_document ($document_id) {

      // load the document
      $document_data = $this->get_document ($document_id);
      if (!$document_data) return false;
      $this->updated_document_title = $document_data->title; // set title so is accessible for status messages

      // delete the file from the file system
      if (! unlink ($document_data->local_path)) return false;

      // delete the record from the database
      if (! $this->db->delete (DDS_TABLE_DOCUMENTS, array ('id' => $document_id), array ('%d'))) return false;

      return true;

   }

}