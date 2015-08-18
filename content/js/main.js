jQuery(document).ready (function () {

   jQuery('.dds-add-document').click (function () {
      console.log ("clicked add");
   });

   jQuery('.dds-edit-document').click (function () {
      var documentId = jQuery(this).parent().parent().attr("document");
      console.log ("clicked edit for " + documentId);
   });

   jQuery('.dds-delete-document').click (function () {
      var documentId = jQuery(this).parent().parent().attr("document");
      console.log ("clicked delete for " + documentId);
   });

   jQuery('.dds-download-document').click (function () {
      var downloadLocation = jQuery(this).parent().parent().attr("download");
      window.open (downloadLocation, '_new');
   });

});