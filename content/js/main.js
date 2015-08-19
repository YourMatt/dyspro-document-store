if (!$) var $ = jQuery;

$(document).ready (function () {
   ddsMain.createAdminButtonHandlers();
});

var ddsMain = {

   createAdminButtonHandlers: function() {

      $('.dds-add-document').click (function () {
         console.log ("clicked add");

         $.fancybox(
            '<h2>Add Document</h2>' + $('#dds-form-add-edit').html()
         );

      });

      $('.dds-edit-document').click (function () {
         var documentId = $(this).parent().parent().attr("document");
         console.log ("clicked edit for " + documentId);
      });

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

         $('.dds-form input[name=document_id]').val(documentId);
         $('.form-cancel').click (ddsMain.closeModalHandler);

      });

      $('.dds-download-document').click (function () {
         var downloadLocation = $(this).parent().parent().attr("download");
         window.open (downloadLocation, '_new');
      });

   },

   closeModalHandler: function() {
      $.fancybox.close();
      return false;
   }

}