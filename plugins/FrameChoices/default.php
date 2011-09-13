<?php if (!defined('APPLICATION'))
	exit();

$PluginInfo['Frame Choices'] = array(
   'Description' => 'This plugin allows items to have custom framing options',
   'Version' => '0.1',
   'Author' => "Darryl Meganoski",
   'AuthorEmail' => 'zodiacdm@gmail.com',
   'AuthorUrl' => 'www.facebook.com/zodiacdm',
   'HasLocale' => TRUE,

);

class FrameChoices extends Gdn_Plugin {

	// An array of the user's selected items for this project
	private $_Selection;

	public function ItemController_AfterItemPrepare_Handler($Sender) {
		$Sender->AddCssFile('/plugins/GalleryDesigner/design/designer.css');
		$Sender->AddJsFile('/plugins/GalleryDesigner/js/designer.js');
	}
	public function ItemController_AfterItemDetails_Handler() {
		$GalleryItemModel = new GalleryItemModel();
		$this->FileData = $GalleryItemModel->GetSlug(ItemController::$SelectedSlug);
		include(PATH_PLUGINS.DS.'GalleryDesigner/views/frameoptions.php');
	}
	public function GalleryController_BeforeCategoriesView_Handler() {
		include(PATH_PLUGINS.DS.'GalleryDesigner/views/categoryheader.php');
	}
	/*
	 * Functions for selecting Items from the gallery
	 */

	public function Setup() {

	}
}

