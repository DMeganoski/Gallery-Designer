<?php if (!defined('APPLICATION'))
	exit();

$PluginInfo['Gallery Designer'] = array(
   'Description' => 'This plugin allows items to be selected from the gallery and constructed to create a final product',
   'Version' => '0.1',
   'Author' => "Darryl Meganoski",
   'AuthorEmail' => 'zodiacdm@gmail.com',
   'AuthorUrl' => 'www.facebook.com/zodiacdm',
   'HasLocale' => TRUE,

);

class GalleryDesigner extends Gdn_Plugin {

	// An array of the user's selected items for this project
	private $_Selection;

	public function Base_GetAppSettingsMenuItems_Handler(&$Sender) {
      $Menu = &$Sender->EventArguments['SideMenu'];
      $Menu->AddItem('Galleries', T('Galleries'));
      $Menu->AddLink('Galleries', T('Designer'), 'settings/voting', 'Garden.Settings.Manage');
   }

	public function SettingsController_Designer_Create(&$Sender) {
		$Sender->Permission('Garden.Settings.Manage');
		$Sender->Title('Designer');
		$Sender->AddSideMenu('settings/designer');
		$Sender->Render('plugins/GalleryDesigner/views/settings.php');
	}
	public function ItemController_AfterItemPrepare_Handler(&$Sender) {
		$Sender->AddCssFile('/plugins/GalleryDesigner/design/designer.css');
		$Sender->AddCssFile('/plugins/GalleryDesigner/design/projectbox.css');
		$Sender->AddJsFile('/plugins/GalleryDesigner/js/designer.js');
	}
	public function GalleryController_WhileHeadInit_Handler($Sender) {
		//$Sender->AddCssFile('/plugins/GalleryDesigner/design/projectbox.css');
		// Can't be plugged into for some reason...
		// $this->AddJsFile('/plugins/GalleryDesigner/js/projectbox.js');
	}
	public function ItemController_AfterItemDetails_Handler() {
		$GalleryItemModel = new GalleryItemModel();
		$this->FileData = $GalleryItemModel->GetSlug(ItemController::$SelectedSlug);
		include(PATH_PLUGINS.DS.'GalleryDesigner/views/frameoptions.php');
	}
	public function GalleryController_BeforeCategoriesView_Handler() {
		include(PATH_PLUGINS.DS.'GalleryDesigner/views/categoryheader.php');
	}
	public function GalleryController_AfterBrowsePager_Handler() {
		include(PATH_PLUGINS.DS.GalleryDesigner.DS.'views/affiliatelinks.php');
	}
	public function Base_Render_Before(&$Sender) {
		$Sender->AddJsFile('/plugins/GalleryDesigner/js/projectbox.js');
      $Controller = $Sender->ControllerName;
      $Session = Gdn::Session();
      //$Hide = Gdn::Config('GalleryDesigner.Hide', TRUE);

	  //if($Hide && !$Session->IsValid())	return;
      if(!in_array($Sender->ControllerName, array('gallerycontroller','itemcontroller', 'categoriescontroller', 'discussioncontroller', 'discussionscontroller'))) return;

      if ($Controller !== "plugin") {
         include_once(PATH_PLUGINS.DS.'GalleryDesigner'.DS.'class.designermodule.php');
         $DesignerModule = new DesignerModule($Sender);
         //$UserListModule->GetData();
         $Sender->AddModule($DesignerModule);
         $Session = Gdn::Session();
         //$Limit = Gdn::Config('UserList.Limit', 6);
         //if (!is_numeric($Limit))
            //$Limit = 6;

         //$Sender->AddDefinition('UserListLimit', $Limit);
      }
   }
	/*
	 * Functions for selecting Items from the gallery
	 */
   public function PluginController_Select_Create(&$Sender) {
		$Type = ArrayValue(0, $Sender->RequestArgs, '');
		$Slug = ArrayValue(0, $Sender->RequestArgs, '');
		$Replace = ArrayValue(0, $Sender->RequestArgs, '');
		if ($Slug != '') {
			$this->AddToSelection($Type, $Slug, $Replace);
		}
	}
	public function SaveSelection($Array) {
		$GalleriesModel = new GalleriesModel();
	   $Serialized = serialize($Array);
	   $Session = Gdn::Session();
	   $CurrentProject = $Session->GetPreference('Project');
	   $GalleriesModel->Insert('DesignerProjects', array('Selection' => $Serialized), array('ProjectKey' => $CurrentProject));
	}

	public function AddToSelection($Type, $ItemSlug, $Replace = FALSE) {
		if ($Replace) {
			$this->_Selection[$Type] = $ItemSlug;
		} else {
			if (!array_key_exists($Type, $this->_Selection)) {
				$this->_Selection[$Type] = $ItemSlug;
			} else {
				$OldValue = $this->_Selection[$Type];
				if (!is_array($this->_Selection)) {
					$this->_Selection[$Type][] = $OldValue;
					$this->_Selection[$Type][] = $ItemSlug;
				} else {
					array_push($this->_Selection[$Type], $ItemSlug);
				}
			}
		}
		$this->SaveSelection($this->_Selection);
	}

	public function Setup() {
		$Structure = Gdn::Structure();
		$Structure->Table('GalleryProject')
		->PrimaryKey('ProjectKey')
        ->Column('UserID', 'int', FALSE, 'primary')
        ->Column('ProjectName', 'varchar(64)', FALSE)
		->Column('Selection', 'varchar(255)', TRUE)
		->Column('CurrentProject', 'tinyint(1)', TRUE)
        ->Set(FALSE, FALSE);
	}
}

