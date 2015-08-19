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
      if (!$documents) {
         $html = '<p>There are currently no documents saved.</p>';
         return $html;
      }

      // build the document table
      $html = '';

      // add the add document button
      $html .= '
<div class="dds-table-wrapper">
<button class="dds-add-document">Add Document</button>';

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
      <td class="download-control"><button class="dds-download-document">Download</button></a></td>
      <td class="title">' . $document->title . '</td>
      <td class="description">' . $document->description . '</td>
      <td class="date">' . $document->date_updated . '</td>
      <td class="edit-controls"><button class="dds-edit-document">Edit</button><button class="dds-delete-document">Delete</button></td>
   </tr>';
      }

      $html .= '
</tbody>
</table>';

      // add the add/edit form
      $html .= '
<div id="dds-form-add-edit" style="display: none;">
   <div class="dds-form">
      <p>This is the add/edit form.</p>
   </div>
</div>';

      // add the delete form
      $html .= '
<div id="dds-form-delete" style="display: none;">
   <form class="dds-form" method="post">
      <input type="hidden" name="action" value="delete"/>
      <input type="hidden" name="document_id" value=""/>
      <p>Are you sure you want to delete [document_name]?  This will permanently delete the document from the system.</p>
      <p class="center">
         <button class="form-submit">Delete</button>
         <button class="form-cancel">Cancel</button>
      </p>
   </form>
</div>';

      $html .= '
</div>';

      return $html;

   }

}