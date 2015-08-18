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

      // return message if there are currently no document
      if (! $documents) {
         $html = '<p>There are currently no documents saved.</p>';
         return $html;
      }

      // build the document table
      $html = '
<table id="dds-table" class="display" cellspacing="0" width="100%">
<thead>
   <tr>
      <th></th>
      <th>Title</th>
      <th>Description</th>
      <th>Updated</th>
   </tr>
</thead>
<tbody>';

      foreach ($documents as $document) {
         $html .= '
   <tr download="' . $document->file_path . '">
       <td><a href="' . $document->file_path . '" class="download">Download</a></td>
       <td>' . $document->title . '</td>
       <td>' . $document->description . '</td>
       <td>' . $document->date_updated . '</td>
   </tr>';
      }

      $html .= '
</tbody>
</table>';

      return $html;

   }

}