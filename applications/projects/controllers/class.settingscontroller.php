<?php if (!defined('APPLICATION'))
	exit();
/*
 * Controls views in the dashboard related to projects and orders
 */
class SettingsController extends Gdn_Controller {
   /**
    * Models to include.
    *
    * @since 2.0.0
    * @access public
    * @var array
    */
   public $Uses = array('Database', 'Form', 'GalleryItemModel', 'ProjectModel', 'GalleryUploadModel');

   public function Initialize() {
      // Set up head
      $this->Head = new HeadModule($this);
      $this->AddJsFile('jquery.js');
      $this->AddJsFile('jquery.livequery.js');
      $this->AddJsFile('jquery.form.js');
      $this->AddJsFile('jquery.popup.js');
      $this->AddJsFile('jquery.gardenhandleajaxform.js');
      $this->AddJsFile('global.js');

      if (in_array($this->ControllerName, array('profilecontroller', 'activitycontroller'))) {
         $this->AddCssFile('style.css');
      } else {
         $this->AddCssFile('admin.css');
      }

      // Change master template
      $this->MasterView = 'admin';
      parent::Initialize();
   }

   public function PrepareController() {
	   $this->AddCssFile('gallery.css');
	   $this->AddJsFile('gallery.js');
	   $this->AddCssFile('settings.css');
   }

	/**
    * All Projects on the site
    *
    * Allows setting configuration values via form elements.
    *
    * @since 2.0.0
    * @access public
    */
	public function Projects() {
		$this->PrepareController();
		$this->AddSideMenu('projects/settings/projects');
		// Check permission
		//$this->Permission('Projects.Projects.Manage');
		$UserModel = new UserModel();
		$this->Projects = $this->ProjectModel->Get();
		foreach ($this->Projects as $Project) {
			$UserID = $Project->UserID;
			$Project->User = $UserModel->Get($UserID);
		}

		$this->Render();
	}

	/*
	 * Function for displaying orders that need to be verified and completed
	 */
	public function OpenProjects() {
		$this->Function = 'OpenProjects';
		$this->PrepareController();
		$this->AddSideMenu('/projects/settings/openprojects');
		$this->Projects = $this->ProjectModel->GetOpen();

		$this->View('projects');
		$this->Render();
	}

	public function AddSideMenu($CurrentUrl) {
      // Only add to the assets if this is not a view-only request
      if ($this->_DeliveryType == DELIVERY_TYPE_ALL) {
         $SideMenu = new SideMenuModule($this);
         $SideMenu->HtmlId = '';
         $SideMenu->HighlightRoute($CurrentUrl);
			$SideMenu->Sort = C('Garden.DashboardMenu.Sort');
         $this->EventArguments['SideMenu'] = &$SideMenu;
         $this->FireEvent('GetAppSettingsMenuItems');
         $this->AddModule($SideMenu, 'Panel');
      }
   }

	/*-------------- Functions for imploding and exploding associative arrays -------------*/
	/*
	 * Takes an array implodes it with '-' and ':', and returns the new string
	 */
	function MyImplode($Array) {

		$Result = '';
		foreach ($Array as $Key => $Value) {
			//if (strlen($Result) > 1)
				$Result .= '-';
				$Result .= $Key.':'.$Value;
		}
		return $Result;
   }

   /*
    * Takes an imploded string containing an associative array and explodes it into an array again
    */
	public function MyExplode($Array) {
		$WierdArray = explode('-', $Array);
		foreach ($WierdArray as $Weird) {
			list ($Before, $After) = explode(':', $Weird);
			$Return[$Before] = $After;
		}
		return $Return;
	}

}