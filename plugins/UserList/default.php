<?php if (!defined('APPLICATION')) exit();
$PluginInfo['UserList'] = array(
   'Name' => 'User List',
   'Description' => "Lists users registered to the forum in the side panel.",
   'Version' => '0.5',
   'Author' => "Ellie Roepken",
   'AuthorEmail' => 'ellie@roepken.net',
   'AuthorUrl' => 'http://ellie.roepken.net',
   'RegisterPermissions' => FALSE,
   'SettingsPermission' => FALSE
);

class UserListPlugin implements Gdn_IPlugin {
   
   public function PluginController_UserList_Create(&$Sender) {
      $Sender->AddSideMenu('plugin/userlist');
      $Sender->Form = new Gdn_Form();
      $Validation = new Gdn_Validation();
      $ConfigurationModel = new Gdn_ConfigurationModel($Validation);
      $ConfigurationModel->SetField(array('UserList.Limit', 'UserList.Hide', 'UserList.Random', 'UserList.Photo', 'UserList.NoPhoto', 'UserList.Title', 'UserList.ShowNumUsers'));
      $Sender->Form->SetModel($ConfigurationModel);
            
      if ($Sender->Form->AuthenticatedPostBack() === FALSE) {
         $Sender->Form->SetData($ConfigurationModel->Data);
      } else {
         $Data = $Sender->Form->FormValues();
         $ConfigurationModel->Validation->ApplyRule('UserList.Limit', array('Required', 'Integer'));
         if ($Sender->Form->Save() !== FALSE)
            $Sender->StatusMessage = Gdn::Translate("Your settings have been saved.");
      }
      
      $Sender->View = dirname(__FILE__).DS.'views'.DS.'userlist.php';
      $Sender->Render();

   }
   
   public function Base_Render_Before(&$Sender) {
      $Controller = $Sender->ControllerName;
      $Session = Gdn::Session();     
      $Hide=Gdn::Config('UserList.Hide', TRUE);

	  if($Hide && !$Session->IsValid())	return;
      if(!in_array($Sender->ControllerName, array('categoriescontroller', 'discussioncontroller', 'discussionscontroller'))) return;
   
      if ($Controller !== "plugin") {
         include_once(PATH_PLUGINS.DS.'UserList'.DS.'class.userlistmodule.php');
         $UserListModule = new UserListModule($Sender);
         $UserListModule->GetData();
         $Sender->AddModule($UserListModule);
         $Session = Gdn::Session();
         $Limit = Gdn::Config('UserList.Limit', 6);
         if (!is_numeric($Limit))
            $Limit = 6;
            
         $Sender->AddDefinition('UserListLimit', $Limit);
      }
   }

   public function Base_GetAppSettingsMenuItems_Handler(&$Sender) {
      $Menu = $Sender->EventArguments['SideMenu'];
      $Menu->AddLink('Add-ons', 'User List', 'plugin/userlist', 'Garden.Settings.Manage');
   }

   public function Setup() { 
      // No setup required.
   }
}
