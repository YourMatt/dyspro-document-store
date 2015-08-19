<?php

class dds_activity_manager {

   public $error_message;
   public $success_message;

   public function __construct () {}

   public function process_management_forms () {

      if ($_POST['action'] == 'delete') {
         $this->process_delete ();
      }

   }

   private function process_delete () {

      if (! wp_verify_nonce ($_POST[DDS_MANAGEMENT_NONCE], 'dds_delete')) {
         $this->error_message = 'Could not verify the form submission. Please try again.';
         return;
      }

      $document_id = $_POST["document_id"];
      $document_manager = new dds_document_manager ();

      if ($document_manager->delete_document($document_id)) {
         $this->success_message = 'Successfully deleted ' . $document_manager->updated_document_title . '.';
      }
      else {
         $this->error_message = 'There was an error deleting the document. Please contact your administrator.';
      }

   }

}