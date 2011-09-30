<?php if (!defined('APPLICATION')) exit(); // Make sure this file can't get accessed directly
/**
 * A special function that is automatically run upon enabling your application.
 *
 * Remember to rename this to FooHooks, where 'Foo' is you app's short name.
 */
class ProjectsHooks implements Gdn_IPlugin {

   /**
    * Example hook. You should delete this.
    *
    * @param object $Sender The object that fired the event. All hooks must accept this single parameter.
    */
   public function ControllerName_EventName_Handler($Sender) {
      // You can find existing hooks by searching for 'FireEvent'
      // Request new hooks on the VanillaForums.org community forum!
   }

    public function Base_Render_Before(&$Sender) {

		if ($Sender->Menu)
			$Sender->Menu->AddLink('Design', T('Design'), '/designer', FALSE, array('class' => 'Design', 'Standard' => TRUE));

		if ($Sender->Head) {
			$Sender->AddJsFile('/applications/projects/js/designer.js');
			$Sender->AddCssFile('/applications/projects/design/designer.css');
			$Sender->AddJsFile('/applications/projects/js/projectbox.js');
			$Sender->AddCssFile('/applications/projects/design/projectbox.css');
			$Sender->AddJsFile('/applications/galleries/js/jquery-ui-1.8.15.custom.min.js');
			$Sender->AddJsFile('jquery.jrac.js');
			$Sender->AddCssFile('style.jrac.css');
		}
        $Controller = $Sender->ControllerName;
        $Session = Gdn::Session();
        //$Hide=Gdn::Config('GalleryModule.Hide', TRUE);

		//if($Hide && !$Session->IsValid())	return;
        if(in_array($Sender->ControllerName, array(
			'itemcontroller', 'projectcontroller', 'designercontroller',
			'gallerycontroller','profilecontroller', 'categoriescontroller',
			'discussionscontroller', 'infocontroller'))) {
            include_once(PATH_APPLICATIONS.DS.'projects'.DS.'modules'.DS.'class.projectboxmodule.php');
			$ProjectBoxModule = new ProjectBoxModule($Sender);
			$Sender->AddModule($ProjectBoxModule);
		}

	}

	public function Base_GetAppSettingsMenuItems_Handler(&$Sender) {
		$Menu = &$Sender->EventArguments['SideMenu'];
		$Menu->AddItem('Projects', T('Projects'));
		$Menu->AddLink('Projects', T('Open Projects'), 'projects/settings/projects', 'Garden.Settings.Manage');
		$Menu->AddLink('Projects', T('Ordered Projects'), 'projects/settings/floodcontrol', 'Garden.Settings.Manage');
		$Menu->AddLink('Projects', T('Completed Projects'), 'projects/settings/advanced', 'Garden.Settings.Manage');
   }

	public function ProfileController_AddProfileTabs_handler(&$Sender) {

	$Sender->AddProfileTab('projects', "/profile/projects/".$Sender->User->UserID."/".Gdn_Format::Url($Sender->User->Name), 'Projects', 'Projects');
}

	public function ProfileController_Projects_Create(&$Sender, $params) {
		$Sender->AddCssFile('/applications/galleries/design/gallery.css');
		$Sender->AddCssFile('/applications/projects/design/profile.css');

		$Sender->UserID = ArrayValue(0, $Sender->RequestArgs, '');
		$Sender->UserName = ArrayValue(1, $Sender->RequestArgs, '');
		$ProjectModel = new ProjectModel();
		//if (Gdn::Session()->UserID == $Sender->UserID)
		$Sender->Projects = $ProjectModel->GetAllUser($Sender->UserID);
		$Sender->GetUserInfo($Sender->UserID, $Sender->UserName);
		$Sender->SetTabView('projects', PATH_APPLICATIONS.DS.'projects/views/profile'.DS.'projects.php/', 'Profile', 'Dashboard');

		$Sender->HandlerType = HANDLER_TYPE_NORMAL;
		$Sender->Render();
		}

   /**
    * Special function automatically run upon clicking 'Enable' on your application.
    * Change the word 'skeleton' anywhere you see it.
    */
   public function Setup() {
      // You need to manually include structure.php here for it to get run at install.
      include(PATH_APPLICATIONS . DS . 'projects' . DS . 'settings' . DS . 'structure.php');

      // This just gets the version number and stores it in the config file. Good practice but optional.
      $ApplicationInfo = array();
      include(CombinePaths(array(PATH_APPLICATIONS . DS . 'projects' . DS . 'settings' . DS . 'about.php')));
      $Version = ArrayValue('Version', ArrayValue('Projects', $ApplicationInfo, array()), 'Undefined');
      SaveToConfig('Projects.Version', $Version);
	  SaveToConfig('Projects.Projects.Manage');
   }

   /**
    * Special function automatically run upon clicking 'Disable' on your application.
    */
   public function OnDisable() {
      // Optional. Delete this if you don't need it.
   }

   /**
    * Special function automatically run upon clicking 'Remove' on your application.
    */
   public function CleanUp() {
      // Optional. Delete this if you don't need it.
   }
}