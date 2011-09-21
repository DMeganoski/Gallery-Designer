<?php if (!defined('APPLICATION')) exit(); // Make sure this file can't get accessed directly
/**
 * A special function that is automatically run upon enabling your application.
 *
 * Remember to rename this to FooHooks, where 'Foo' is you app's short name.
 */
class GalleriesHooks implements Gdn_IPlugin {

   /**
    * Example hook. You should delete this.
    *
    * @param object $Sender The object that fired the event. All hooks must accept this single parameter.
    */
   public function PluginController_GallerySettings_Create(&$Sender) {
      $Sender->AddSideMenu('plugin/gallerysettings');
      $Sender->Form = new Gdn_Form();
      $Validation = new Gdn_Validation();
      $ConfigurationModel = new Gdn_ConfigurationModel($Validation);
      $ConfigurationModel->SetField(array('Gallery.Items.PerPage','Gallery.Sort.Random'));
      $Sender->Form->SetModel($ConfigurationModel);

      if ($Sender->Form->AuthenticatedPostBack() === FALSE) {
         $Sender->Form->SetData($ConfigurationModel->Data);
      } else {
         $Data = $Sender->Form->FormValues();
         //$ConfigurationModel->Validation->ApplyRule('Gallery.Items.PerPage', array('Required', 'Integer'));
         if ($Sender->Form->Save() !== FALSE)
            $Sender->StatusMessage = Gdn::Translate("Your settings have been saved.");
      }

      $Sender->View = PATH_APPLICATIONS.'/galleries/views/gallery/gallerysettings.php';
      $Sender->Render();

   }
   public function Base_GetAppSettingsMenuItems_Handler(&$Sender) {
      $Menu = &$Sender->EventArguments['SideMenu'];
	  $Menu->AddItem('Galleries', T('Galleries'));
      $Menu->AddLink('Galleries', T('G Categories'), 'galleries/settings/managecategories', 'Garden.Settings.Manage');
      $Menu->AddLink('Galleries', T('G Flood Control'), 'galleries/settings/floodcontrol', 'Garden.Settings.Manage');
      $Menu->AddLink('Galleries', T('G Advanced'), 'galleries/settings/advanced', 'Garden.Settings.Manage');
   }

   public function ProfileController_AddProfileTabs_handler(&$Sender) {

	$Sender->AddProfileTab('uploads', "/profile/uploads/".$Sender->User->UserID."/".Gdn_Format::Url($Sender->User->Name), 'Uploads', 'Uploads');
}

   /*
    * Create a list of user uploaded items in the profile
    */
	public function ProfileController_Uploads_Create(&$Sender, $params) {

		$Sender->AddCssFile('/applications/galleries/design/gallery.css');
		$Sender->UserID = ArrayValue(0, $Sender->RequestArgs, '');
		$Sender->UserName = ArrayValue(1, $Sender->RequestArgs, '');
		$UploadModel = new GalleryUploadModel();
		if (Gdn::Session()->UserID == $Sender->UserID)
			$Sender->Uploads = $UploadModel->GetUploads('0', '', array('InsertUserID' => $Sender->UserID));
		$Sender->GetUserInfo($Sender->UserID, $Sender->UserName);
		$Sender->SetTabView('uploads', PATH_APPLICATIONS.DS.'galleries/views/profile'.DS.'uploads.php/', 'Profile', 'Dashboard');

		$Sender->HandlerType = HANDLER_TYPE_NORMAL;
		$Sender->Render();
	}

   /*
    * Add Link to Main Menu
    */
   private $_EnabledApplication = 'Galleries';

   public function SettingsController_DashboardData_Handler(&$Sender) {
      $GalleryItemModel = new GalleryItemModel();
      $CountDiscussions = $GalleryItemModel->GetCount();
      $Sender->AddDefinition('CountItems', $CountItems);
      $Sender->BuzzData[T('TotalItems')] = number_format($CountItems);
      //$Sender->BuzzData[T('New items in the last day')] = number_format($GalleriesModel->GetCount(array('d.DateInserted >=' => Gdn_Format::ToDateTime(strtotime('-1 day')))));
      //$Sender->BuzzData[T('New items in the last week')] = number_format($GalleriesModel->GetCount(array('d.DateInserted >=' => Gdn_Format::ToDateTime(strtotime('-1 week')))));
   }
   public function Gdn_Controller_DefineAdminPermissions_Handler(&$Sender) {
      if (isset($Sender->RequiredAdminPermissions)) {
         $Sender->RequiredAdminPermissions[] = 'Gallery.Items.Upload';
         $Sender->RequiredAdminPermissions[] = 'Gallery.Docs.Manage';
         $Sender->RequiredAdminPermissions[] = 'Gallery.Docs.Download';
		$Sender->RequiredAdminPermissions[] = 'Gallery.Items.Manage';

      }
   }
   public function Gdn_Dispatcher_AfterEnabledApplication_Handler(&$Sender) {
      $this->_EnabledApplication = ArrayValue('EnabledApplication', $Sender->EventArguments, 'Galleries'); // Defaults to "Vanilla"
   }

   private function _EnabledApplication() {
      return $this->_EnabledApplication;
   }

   public function Base_Render_Before(&$Sender) {

	if ($Sender->Menu)
		$Sender->Menu->AddLink('Gallery', T('Gallery'), '/gallery', FALSE, array('class' => 'Gallery', 'Standard' => TRUE));

	if ($Sender->Head) {
		$Sender->AddJsFile('/applications/galleries/js/gallery.js');
		$Sender->AddCssFile('/applications/galleries/design/gallery.css');
   }

        $Controller = $Sender->ControllerName;
        $Session = Gdn::Session();
        //$Hide=Gdn::Config('GalleryModule.Hide', TRUE);

	//if($Hide && !$Session->IsValid())	return;
		$GalleryHeadModule = new GalleryHeadModule($Sender);
        //$GalleryHeadModule->GetData();
		$GallerySideModule = new GallerySideModule($Sender);
        if ($Controller == "gallerycontroller") {
	    $Sender->MasterView = 'default';
	    //$Menu = $Sender->EventArguments['sidemenu'];
	    //$Menu->ClearGroups;
            include_once(PATH_APPLICATIONS.DS.'galleries'.DS.'modules'.DS.'class.galleryheadmodule.php');
            include_once(PATH_APPLICATIONS.DS.'galleries'.DS.'modules'.DS.'class.gallerysidemodule.php');

            //$this->AddModule($GalleryHeadModule);
            //$this->AddModule($GallerySideModule);
            $Session = Gdn::Session();
            //$Limit = Gdn::Config('GalleriesHeadModule.Limit', 6);
            //if (!is_numeric($Limit))
                //$Limit = 6;

            //$Sender->AddDefinition('GalleriesModuleLimit', $Limit);
        }
        if(in_array($Sender->ControllerName, array('projectcontroller', 'designercontroller', 'gallerycontroller','categoriescontroller', 'profilecontroller', 'discussioncontroller', 'discussionscontroller'))) {
            include_once(PATH_PLUGINS.DS.'Galleries'.DS.'class.galleryvideomodule.php');
            $GalleryVideoModule = new GalleryVideoModule($Sender);
            $Sender->AddModule($GalleryVideoModule);
        }
    }

   /**
    * Special function automatically run upon clicking 'Enable' on your application.
    * Change the word 'skeleton' anywhere you see it.
    */
   public function Setup() {
      $Database = Gdn::Database();
      $Config = Gdn::Factory(Gdn::AliasConfig);
      $Drop = C('Galleries.Version') === FALSE ? TRUE : FALSE;
      $Explicit = TRUE;
      // This is going to be needed by structure.php to validate permission names
      $Validation = new Gdn_Validation();
      // You need to manually include structure.php here for it to get run at install.
      include(PATH_APPLICATIONS . DS . 'galleries' . DS . 'settings' . DS . 'structure.php');

      // This just gets the version number and stores it in the config file. Good practice but optional.
      $ApplicationInfo = array();
      include(CombinePaths(array(PATH_APPLICATIONS . DS . 'galleries' . DS . 'settings' . DS . 'about.php')));
      $Version = ArrayValue('Version', ArrayValue('galleries', $ApplicationInfo, array()), 'Undefined');
      $Save = array('Galleries.Version'=> $Version, 'Garden.Upload.MaxFileSize' => '1G', 'Galleries.Items.PerPage' => '20', 'Galleries.FireEvents.Show' => TRUE, 'Galleries.LastScan.Date' => '09.09.11');
	  SaveToConfig($Save);
   }
}