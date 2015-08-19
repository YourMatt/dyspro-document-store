if (!$) var $ = jQuery;

$(document).ready (function () {
   ddsMain.createAdminButtonHandlers();
});

var ddsMain = {

   createAdminButtonHandlers: function() {

      // set add document handler
      $('.dds-add-document').click (function () {
         var row = $(this).parent().parent();

         var html = $('#dds-form-add-edit').html();

         $.fancybox(
            '<h2>Add Document</h2>' + html,
            {
               autoSize: false,
               width: 600,
               height: 'auto'
            }
         );

         var container = $('.fancybox-wrap');
         $('.form-submit', container).click(ddsMain.saveModalHandler);
         $('.form-cancel', container).click(ddsMain.closeModalHandler);
         $('input[name=action]', container).val('add');
         $('input[name=title]', container).focus();

      });

      // set edit document handler
      $('.dds-edit-document').click (function () {
         var row = $(this).parent().parent();

         var documentId = row.attr("document");
         var documentTitle = $('.title', row).text();
         var documentDescription = $('.description', row).text();

         var html = $('#dds-form-add-edit').html();

         $.fancybox(
            '<h2>Edit Document</h2>' + html,
            {
               autoSize: false,
               width: 600,
               height: 'auto'
            }
         );

         var container = $('.fancybox-wrap');
         $('.form-submit', container).click(ddsMain.saveModalHandler);
         $('.form-cancel', container).click(ddsMain.closeModalHandler);
         $('input[name=action]', container).val('edit');
         $('input[name=document_id]', container).val(documentId);
         $('input[name=title]', container).val(documentTitle);
         $('textarea[name=description]', container).val(documentDescription);
         $('input[name=title]', container).focus();

      });

      // set delete document handler
      $('.dds-delete-document').click (function () {
         var row = $(this).parent().parent();

         var documentId = row.attr("document");
         var documentName = $('.title', row).text();

         var html = $('#dds-form-delete').html();
         html = html.replace('[document_name]', documentName);

         $.fancybox(
            '<h2>Delete Document</h2>' + html,
            {
               autoSize: false,
               width: 350,
               height: 'auto'
            }
         );

         var container = $('.fancybox-wrap');
         $('input[name=document_id]', container).val(documentId);
         $('.form-cancel', container).click (ddsMain.closeModalHandler);

      });

      // set download handler
      $('.dds-download-document').click (function () {
         var downloadLocation = $(this).parent().parent().attr("download");
         window.open (downloadLocation, '_new');
      });

   },

   saveModalHandler: function() {
      var container = $('.fancybox-wrap');

      var editing = ($('input[name=document_id]', container).val() != '');

      var title = $('input[name=title]', container).val();
      var description = $('textarea[name=description]', container).val();
      var uploadFile = $('input[name=upload_file]', container).val();

      var errorMessage = '';
      if (! uploadFile && editing) errorMessage = 'Please select a file to upload.';
      if (! description) errorMessage = 'Please enter a description to continue.';
      if (! title) errorMessage = 'Please enter a title to continue.';

      if (errorMessage) {
         $('.error-message', container).html(errorMessage);
         $('.error-message', container).show();
         return false;
      }

      $('.error-message', container).hide();
      return true;

   },

   closeModalHandler: function() {
      $.fancybox.close();
      return false;
   }

}