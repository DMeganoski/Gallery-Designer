<?php if (!defined('APPLICATION'))
	exit();

class ProjectBoxModule extends Gdn_Module {

	public function __construct(&$Sender = '') {
      parent::__construct($Sender);
	  $Sender->AddJsFile('/applications/projects/js/designer.js');
	  $Sender->AddCssFile('/applications/projects/design/designer.css');
	  $Sender->AddJsFile('/applications/projects/js/projectbox.js');
	  $Sender->AddCssFile('/applications/projects/design/projectbox.css');
	  $Sender->AddJsFile('/applications/projects/js/jquery.event.drag.js');
   }

	public function AssetTarget() {
		return 'Panel';
	}

	public function GetProject() {
		$this->Session = Gdn::Session();
		$this->ProjectModel = new ProjectModel();
		$this->UserID = $this->Session->UserID;
		$this->CurrentProject = $this->ProjectModel->GetCurrent($this->UserID);
	}

	public function ToString() {
		$this->GetProject();
		$String = '';
		$Session = Gdn::Session();
		$permissions=$Session->User->Permissions;
		$admin=preg_match('/Garden.Settings.Manage/',$permissions);

		ob_start();
		//$Limit = Gdn::Config('UserList.Limit');
		//$Photo = Gdn::Config('UserList.Photo');
		//$Title = Gdn::Config('UserList.Title');
		//$ShowNumUsers = Gdn::Config('UserList.ShowNumUsers');

		//if(empty($Title)) {
      	//$Title="Members";
		//}

		//if($Photo) {

		include_once(PATH_APPLICATIONS.DS.'projects/views/modules/projectbox.php');

		$String = ob_get_contents();
		@ob_end_clean();
		return $String;
	}

}