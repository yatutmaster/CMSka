/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
   config.filebrowserBrowseUrl = '/js/admin/ckeditor/kcfinder/browse.php?type=files';
   config.filebrowserImageBrowseUrl = '/js/admin/ckeditor/kcfinder/browse.php?type=images';
   config.filebrowserFlashBrowseUrl = '/js/admin/ckeditor/kcfinder/browse.php?type=flash';
   config.filebrowserUploadUrl = '/js/admin/ckeditor/kcfinder/upload.php?type=files';
   config.filebrowserImageUploadUrl = '/js/admin/ckeditor/kcfinder/upload.php?type=images';
   config.filebrowserFlashUploadUrl = '/js/admin/ckeditor/kcfinder/upload.php?type=flash';
     
   config.allowedContent = true;
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
  config.toolbar_Basic =
  [
  	['Source','Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink','-','About','skin : kama']
  ];	
};
