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
         $html = '
<div class="dds-empty-list-wrapper">
    <p>There are currently no documents saved to this category.</p>
    <button class="dds-add-document">Add Document</button>';
         $html .= $this->get_add_edit_form ($type);
         $html .= '
</div>';
         return $html;
      }

      // build the document table
      $html = '';

      // add the add document button
      $html .= '
<div class="dds-table-wrapper">
<button class="dds-add-document">Add Document</button>';

      // add the results table
      $html .= $this->get_document_table ($documents);

      // add the add/edit form
      $html .= $this->get_add_edit_form ($type);

      // add the delete form
      $html .= $this->get_delete_form ();

      $html .= '
</div>';

      return $html;

   }

   private function get_document_table ($documents) {

      $html = '
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

      return $html;

   }

   private function get_add_edit_form ($type) {

      return '
<div id="dds-form-add-edit" style="display: none;">
   <form class="dds-form" method="post" enctype="multipart/form-data">
      <input type="hidden" name="action" value=""/>
      <input type="hidden" name="document_id" value=""/>
      <input type="hidden" name="type" value="' . $type . '"/>
      ' . wp_nonce_field('dds_add_edit', DDS_MANAGEMENT_NONCE, false, false) . '
      <div class="error-message"></div>
      <dl>
         <dt>Title</dt>
         <dd><input type="text" name="title" maxlength="255" value=""/></dd>
         <dt>Description</dt>
         <dd><textarea name="description"></textarea></dd>
         <dt>File</dt>
         <dd><input name="upload_file" type="file"/></dd>
      </dl>
      <p class="center">
         <button class="form-submit">Save</button>
         <button class="form-cancel">Cancel</button>
      </p>
   </form>
</div>';

   }

   private function get_delete_form () {

      return '
<div id="dds-form-delete" style="display: none;">
   <form class="dds-form" method="post">
      <input type="hidden" name="action" value="delete"/>
      <input type="hidden" name="document_id" value=""/>
      ' . wp_nonce_field('dds_delete', DDS_MANAGEMENT_NONCE, false, false) . '
      <p>Are you sure you want to delete [document_name]?  This will permanently delete the document from the system.</p>
      <p class="center">
         <button class="form-submit">Delete</button>
         <button class="form-cancel">Cancel</button>
      </p>
   </form>
</div>';

   }

}