<?php

class dds_shortcode_manager {

   private $db;

   public function __construct () {
      global $wpdb;
      $this->db = &$wpdb;

      wp_enqueue_script ('dds_main', DDS_BASE_WEB_PATH . '/content/js/main.js', null, '20150817', true);

   }

   public function build_file_store_page ($attributes) {
      $type = $attributes["type"];

      $dm = new dds_document_manager ();
      $documents = $dm->get_documents ($type);

      // return message if there are currently no document
      if (! $documents) {
         $html = '<p>There are currently no documents saved.</p>';
         return $html;
      }

      // build the document table
      $html = '';

      // add the add document button
      $html .= '
<div class="dds-add-document-wrapper">
    <button class="dds-add-document">Add Document</button>
</div>';

      $html .= '
<table id="dds-table" class="display" cellspacing="0" width="100%">
<thead>
   <tr>
      <th></th>
      <th>Title</th>
      <th>Description</th>
      <th>Updated</th>
      <th></th>
   </tr>
</thead>
<tbody>';

      foreach ($documents as $document) {
         $html .= '
   <tr download="' . $document->file_path . '" document="' . $document->id . '">
       <td><button class="dds-download-document">Download</button></a></td>
       <td>' . $document->title . '</td>
       <td>' . $document->description . '</td>
       <td>' . $document->date_updated . '</td>
       <td class="edit-controls"><button class="dds-edit-document">Edit</button><button class="dds-delete-document">Delete</button></td>
   </tr>';
      }

      $html .= '
</tbody>
</table>';

      return $html;

   }

}