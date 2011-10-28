<?php if (!defined('APPLICATION'))
	exit();

/**
 * Controller for handling anything to do with projects outside of the design part.
 * This includes adding and removing objects from around the site to the project,
 * including uploads, though they are otherwise handled in the item controller.
 */
class ProjectController extends ProjectsController {

	/**
	 * Array of classes (models) to include.
	 *
	 * @var type
	 */
	public $Uses = array('GalleryItemModel', 'ProjectModel', 'GalleryUploadModel');

	public function Initialize() {
		parent::Initialize();

		$Controller = $this->ControllerName;

		if ($this->Head) {
			$this->AddJsFile('jquery.js');
			$this->AddJsFile('css_browser_selector.js');
			$this->AddJsFile('jquery.livequery.js');
			$this->AddJsFile('jquery.form.js');
			$this->AddJsFile('jquery.popup.js');
			$this->AddJsFile('jquery.gardenhandleajaxform.js');
			$this->AddJsFile('global.js');
			if (C('Galleries.ShowFireEvents'))
				$this->DisplayFireEvent('WhileHeadInit');

			$this->FireEvent('WhileHeadInit');

		}
		$this->MasterView = 'default';
	}

   /**
    * Function for other functions to include css and js files, as well as modules.
    */
    public function PrepareController() {

		$this->AddModule('GalleryHeadModule');
		$this->AddModule('ProjectBoxModule');
		$this->AddModule('GallerySideModule');

		$this->AddJsFile('jquery.event.drag.js');
		$this->AddJsFile('/applications/galleries/js/gallery.js');
		$this->AddCssFile('/applications/galleries/design/gallery.css');

	}

	/**
	 * Function for rendering all projects belonging to the person viewing the page.
	 */
	public function Index() {
		$this->PrepareController();
		$this->AddModule('GallerySideModule');
		$this->AddModule('GalleryHeadModule');
		$this->AddModule('ProjectBoxModule');
		$this->AddJsFile('/js/library/jquery.autogrow.js');
        $this->AddJsFile('forms.js');
		$Session = Gdn::Session();
		$UserID = $Session->UserID;
		$this->Form = new Gdn_Form('Project');
		$this->Form->SetModel($this->ProjectModel);
		$this->Form->AddHidden('UserID', $UserID);
		if ($this->Form->AuthenticatedPostBack() === FALSE) {

		} else {
				if ($this->Form->Save() !== FALSE) {

					$this->ProjectModel->Insert('Project', array(
					'UserID' => $UserID,
					'ProjectName' => $this->Form->GetFormValue('ProjectName'),
					'CurrentProject' => 1
				));
				$CurrentProject = $this->ProjectModel->GetNew($UserID);
				$this->ProjectModel->SetCurrent($UserID,$CurrentProject->ProjectKey);
				//$this->ProjectModel->NewProject($UserID);
                $this->StatusMessage = T("Your changes have been saved successfully.");
                //$this->RedirectUrl = Url('/item/'.$Item->Slug);
				$this->Form->SetFormValue('ProjectName', '');
				}
		}
		$this->Projects = $this->ProjectModel->GetAllUser($Session->UserID);
		$this->Render();
	}

	/**
	 * Ajax function for retrieving project data.
	 * loads pages located in /views/project/
	 */
	public function GetProject() {
		$this->PrepareController();
		$Request = Gdn::Request();
		$this->UserID = $Request->Post('UserID');
		$this->TransientKey = $Request->Post('TransientKey');
		$this->CurrentProject = $this->ProjectModel->GetCurrent($this->UserID);
		$Type = $Request->Post('Type');
		$UserModel = new UserModel();
		$this->User = $UserModel->GetSession($this->UserID);
		if ($this->User->Attributes['TransientKey'] == $this->TransientKey) {
			$CurrentProject = $this->CurrentProject;
			if ($this->CurrentProject === FALSE) {
				echo 'No Project Currently Selected. Would you like to start a new one or select an existing one?';
			} else {
				$Path = PATH_APPLICATIONS.DS.'projects/views/project/';
				switch ($Type) {
					case 'None':
						break;
					case 'Tin':
						include_once($Path.'base.php');
						break;
					case 'bases':
						include_once($Path.'base.php');
						break;
					case 'Background':
						include_once($Path.'background.php');
						break;
					case 'backgrounds':
						include_once($Path.'background.php');
						break;
					case 'Uploads':
						include_once($Path.'uploads.php');
						break;
					case 'uploads':
						include_once($Path.'uploads.php');
						break;
					case 'Text':
						include_once($Path.'text.php');
						break;
					case 'message':
						include_once($Path.'text.php');
						break;
					default:
						break;
				}
			}
		} else {
				echo "Must Be Signed in to start a project";
		}
	}

/*------------------------------------- Main Selections -------------------------------*/
	/**
	 * Ajax function for adding items around the site, as well as uploads,
	 * to the current project.
	 */
	public function ProjectSelect() {
		$Request = Gdn::Request();
		$Type = $Request->Post('Type');
		$Identifier = $Request->Post('Slug');
		$UserID = $Request->Post('UserID');
		$ProjectID = $Request->Post('ProjectID');
		$Method = $Request->Post('Action');

		$CurrentProject = $this->ProjectModel->GetSingle($ProjectID);
		/*--------------------- Regular Provided Items -----------------------*/
		if ($Type != 'uploads') { // if its one of the standard items...
			// reset variables
			$CurrentSelection = array();
			$Success = false;
			// loop to verify success
			while ($Success === false) {
				// add or remove according to method parameter
				if ($Method == 'add')
					$Return = $this->_AddToSelection($ProjectID, $Type, $Identifier);
				elseif ($Method == 'remove')
					$Return = $this->_RemoveFromSelection($ProjectID, $Type, $Identifier);

				// Get data after save
				$CurrentIncluded = $this->MyExplode($CurrentProject->Included);
				// if the data is correct, exit loop
				if ($CurrentIncluded[$Type] == $Identifier) {
					$Success === TRUE;
				} else {
					$Success === FALSE;
				}
			} // end while success loop
		/*--------------------------- Uploaded Items -----------------------------*/
		} else { // else its one of the uploads...
			// reset variables
			$CurrentIncluded = array();
			$Success = false;
			// loop to verify success
			while ($Success === false) {
				// check method
				if ($Method == 'add')
					$Return = $this->_AddToIncluded($ProjectID, $Type, $Identifier);
				elseif ($Method == 'remove')
					$Return = $this->_RemoveFromIncluded($ProjectID, $Identifier);
				// get data after save
				$CurrentIncluded = $this->MyExplode($CurrentProject->Selected);
				// if the data is correct, exit loop
				if ($CurrentIncluded[$Type] == $Included[$Type]) {
					$Success === TRUE;
				} else {
					$Success === FALSE;
				}
			} // end while success loop
		}
	}

	/**
	 * Ajax function for selecting a frame for the background.
	 */
	public function FrameSelect() {
		$Request = Gdn::Request();
		$UserID = $Request->Post('UserID');
		$Session = Gdn::Session();
		$ProjectID = $Request->Post('ProjectID');
		$FrameChoice = $Request->Post('Frame');



		$CurrentSelection = array();
			$Success = false;
			// loop to verify success
			while ($Success === false) {

				if ($FrameChoice != 'None')
					$this->_AddToSelection($ProjectID, 'frame', $FrameChoice);
				else
					$this->_RemoveFromSelection($ProjectID, 'frame', $FrameChoice);

				// Get data after save
				$CurrentSelection = $this->MyExplode($CurrentProject->Selection);
				// if the data is correct, exit loop
				if ($CurrentSelection['frame'] == $FrameChoice) {
					$Success === TRUE;
				} else {
					$Success === FALSE;
				}
			} // end while success loop





	}
/*------------------------------- Private selection functions --------------------------*/

	/**
	 * Private function for adding an item to the project.
	 * Used by ProjectSelect()
	 */
	private function _AddToSelection($ProjectID, $Type, $ItemSlug) {

		$CurrentProject = $this->ProjectModel->GetSingle($ProjectID);
		$CurrentSelection = $this->MyExplode($CurrentProject->Selected);
		$NewArray = array();
			if (!empty($CurrentSelection)) {
				foreach ($CurrentSelection as $CurrentType => $CurrentSlug) {
					if (!empty($CurrentSlug) && $CurrentType != $Type) {
						$NewArray[$CurrentType] = $CurrentSlug;
					}
				}
			}
			$NewArray[$Type] = $ItemSlug;
			$Return = $this->_SaveSelection($ProjectID, $Type, $NewArray);
			return $Return;
	}

	/**
	 * Private function for adding an upload to the project.
	 * Used by ProjectSelect()
	 */
	private function _AddToIncluded($ProjectID, $Type, $UploadID) {

		$Return = $this->_SaveIncluded($ProjectID, $Type, $UploadID);
		return $Return;

	}

	/**
	 * Private function for saving an item to the project database.
	 * Used by _AddToIncluded()
	 */
	private function _SaveIncluded($ProjectID, $Type, $UploadID) {
		$CurrentProject = $this->ProjectModel->GetSingle($ProjectID);
		$CurrentSelection = $this->MyExplode($CurrentProject->Included);
		$NewArray = array();
			if (is_array($CurrentSelection)) {
				foreach ($CurrentSelection as $CurrentSlug) {
					if (!empty($CurrentSlug)) {
						$NewArray[] = $CurrentSlug;
					}
				}
				$NewArray[] = $UploadID;
			} else {
				$NewArray[] = $UploadID;
			}
			$Serialized = $this->MyImplode($NewArray);
			$this->ProjectModel->Update('Project', array(
			'Included' => $Serialized
			), array('ProjectKey' => $ProjectID));

		//Redirect('/project');

	}

	/**
	 * Private function for saving an upload to the project database.
	 * Used by _AddtoSelection()
	 */
	private function _SaveSelection($ProjectID, $Type, $Selection) {


		$Serialized = $this->MyImplode($Selection);
			$this->ProjectModel->Update('Project', array(
			'Selected' => $Serialized
			), array('ProjectKey' => $ProjectID));

	}

	/**
	 * Private function for removing an item from the project database.
	 * Used by ProjectSelect()
	 */
	private function _RemoveFromSelection($ProjectID, $Type, $ItemSlug = '') {

		$Project = $this->ProjectModel->GetSingle($ProjectID);
		$Selection = $this->MyExplode($Project->Selected);
		unset($Selection[$Type]);
		$Serialized = $this->MyImplode($Selection);
		$this->ProjectModel->Update('Project', array(
			'Selected' => $Serialized
		), array('ProjectKey' => $ProjectID));

	}

	/**
	 * Private function for removing an upload from the project database.
	 * Used by ProjectSelect()
	 */
	private function _RemoveFromIncluded($ProjectID, $Upload) {
		$Project = $this->ProjectModel->GetSingle($ProjectID);
		$Included = $this->MyExplode($Project->Included);
		$UploadData = $this->GalleryUploadModel->GetWhere(array('FileName' => $Upload))->FirstRow();
		$Found = array_search($UploadData->UploadKey, $Included);
		unset($Included[$Found]);
		$Serialized = $this->MyImplode($Included);
		$this->ProjectModel->Update('Project', array(
			'Included' => $Serialized
		), array('ProjectKey' => $ProjectID));
	}

/*------------------------------- Function for administering projects ------------------*/

	/**
	 * Ajax function for changing the current project for a user.
	 */
	public function SetCurrent() {
		$Request = Gdn::Request();
		$ProjectID = $Request->Post('ProjectID');
		$UserID = $Request->Post('UserID');
		$this->ProjectModel->SetCurrent($UserID, $ProjectID);
		echo 'UserID: '.$UserID;
		echo 'ProjectID: '.$ProjectID;

	}

	/**
	 * Ajax function for removing a project altoghether.
	 */
	public function Delete() {
		$Request = Gdn::Request();
		$ProjectID = $Request->Post('ProjectID');
		$UserID = $Request->Post('UserID');
		$TransientKey = $Request->Post('TransientKey');
		$UserModel = new UserModel();
		$User = $UserModel->GetSession($UserID);
		if ($User->Attributes['TransientKey'] == $TransientKey) {
			$this->ProjectModel->DeleteProject($ProjectID);
		}
	}

	/**
	 * Function for setting stage of completion of projects.
	 * Allows for users to set the project to be produced.
	 */
	public function SetStage() {
		$Request = Gdn::Request();
		$Stage = $Request->Post('Stage');
		if ($Stage <= 2) {
			$ProjectID = $Request->Post('ProjectID');
			$SelectedProject = $this->ProjectModel->GetSingle($ProjectID);
			$this->ProjectModel->Update('Project', array(
				'ProjectStage' => $Stage
			), array('ProjectKey' => $ProjectID));
			echo "Project Waiting for approval";
		} else {
			echo "Error, Project already at final stage";
		}
	}

/*------------------- Functions for retrieving previous poisition of elements -----------*/

	/**
	 *
	 */
	public function
	GetPlacement() {
		$Request = Gdn::Request();
		$CurrentProject = $Request->Post('projectID');
		$ImgID = $Request->Post('imgID');
		$ProjectData = $this->ProjectModel->GetSingle($CurrentProject);
		$TopPositions = $this->MyExplode($ProjectData->TopPositions);
		$TopPosition = $TopPositions[$ImgID];
		$LeftPositions = $this->MyExplode($ProjectData->LeftPositions);
		$LeftPosition = $LeftPositions[$ImgID];
		echo json_encode(array("top"=>$TopPosition,"left"=>$LeftPosition, "imgID"=>$ImgID,"projectID"=>$CurrentProject));
	}
}