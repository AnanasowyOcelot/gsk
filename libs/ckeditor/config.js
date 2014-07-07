/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	
	config.toolbar = 'smallCMS';
	
	config.toolbar_smallCMS =
	[
		
		{ name: 'clipboard', items : [ 'Source','PasteText','PasteFromWord','-','Undo','Redo' ] },
		{ name: 'editing', items : [ 'Find','Replace','-','SelectAll' ] },		       	
		{ name: 'basicstyles', items : [ 'Image','Table','-','Bold','Italic','-','RemoveFormat' ] },		
		{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent' ] },		
		{ name: 'links', items : [ 'Link','Unlink'] }		
	];
};
