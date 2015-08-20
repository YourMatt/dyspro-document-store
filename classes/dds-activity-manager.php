<?php

class dds_activity_manager {

   public $error_message;
   public $success_message;

   public function __construct () {}

   public function process_management_forms () {

      switch ($_POST['action']) {
         case 'add':
            $this->process_add ();
            break;
         case 'edit':
            $this->process_edit ();
            break;
         case 'delete':
            $this->process_delete ();
            break;
      }

   }

   private function process_add () {

      if (! wp_verify_nonce ($_POST[DDS_MANAGEMENT_NONCE], 'dds_add_edit')) {
         $this->error_message = 'Could not verify the form submission. Please try again.';
         return;
      }

      $type = $_POST['type'];
      $author = get_current_user_id ();
      $title = $_POST['title'];
      $description = $_POST['description'];
      $file_data = $_FILES['upload_file'];

      $document_manager = new dds_document_manager ();

      if ($document_manager->add_document ($title, $description, $type, $author, $file_data)) {
         $this->success_message = 'Successfully added ' . $document_manager->updated_document_title . '.';
      }
      else {
         if ($document_manager->error_message) $this->error_message = $document_manager->error_message;
         else $this->error_message = 'There was an error adding your document. Please contact your administrator.';
      }

   }

   private function process_edit () {

      if (! wp_verify_nonce ($_POST[DDS_MANAGEMENT_NONCE], 'dds_add_edit')) {
         $this->error_message = 'Could not verify the form submission. Please try again.';
         return;
      }

      $document_id = $_POST['document_id'];
      $type = $_POST['type'];
      $author = get_current_user_id ();
      $title = $_POST['title'];
      $description = $_POST['description'];
      $file_data = $_FILES['upload_file'];

      $document_manager = new dds_document_manager ();

      if ($document_manager->update_document ($document_id, $title, $description, $type, $author, $file_data)) {
         $this->success_message = 'Successfully updated ' . $document_manager->updated_document_title . '.';
      }
      else {
         if ($document_manager->error_message) $this->error_message = $document_manager->error_message;
         else $this->error_message = 'There was an error updating your document. Please contact your administrator.';
      }

   }

   private function process_delete () {

      if (! wp_verify_nonce ($_POST[DDS_MANAGEMENT_NONCE], 'dds_delete')) {
         $this->error_message = 'Could not verify the form submission. Please try again.';
         return;
      }

      $document_id = $_POST['document_id'];
      $document_manager = new dds_document_manager ();

      if ($document_manager->delete_document($document_id)) {
         $this->success_message = 'Successfully deleted ' . $document_manager->updated_document_title . '.';
      }
      else {
         if ($document_manager->error_message) $this->error_message = $document_manager->error_message;
         else $this->error_message = 'There was an error deleting the document. Please contact your administrator.';
      }

   }

}