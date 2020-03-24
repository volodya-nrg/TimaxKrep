/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.language = 'ru';
	config.filebrowserUploadUrl = '/upload_image';
	
	config.toolbar = [
						['Source', 'Save', 'NewPage'],
						['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', 'Undo', 'Redo'],
						['Link', 'Unlink', 'Anchor', 'Image', 'Flash', 'Table', 'HorizontalRule', 'CreateDiv'],
						['Format'],
						['Font'],
						['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', 'SpecialChar', 'RemoveFormat'],
						['NumberedList', 'BulletedList', 'Outdent', 'Indent', 'Blockquote', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '', ''],
						['FontSize'],
						['TextColor', 'BGColor', 'ShowBlocks'],
						['Find'],
						['Preview', 'Maximize']
					  ];
	//Ширина
	  //config.resize_minWidth = 450;
	  //config.resize_maxWidth = 600; 
	//Высота
	config.height = 400; 
	//config.resize_maxHeight = 500; 
	
	//config.removePlugins = 'elementspath';
	//config.entities = false;	
	config.allowedContent = true;			  
};

