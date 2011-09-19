<?php if (!defined('APPLICATION'))
	exit();


class ProjectController extends ProjectsController {

	public $Uses = array('GalleryItemModel', 'ProjectModel', 'GalleryUploadModel');

	public function Initialize() {
      parent::Initialize();

      $Controller = $this->ControllerName;
      //$Sender->Form = new Gdn_Form();

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
	  parent::Initialize();
   }

    public function PrepareController() {

		$this->AddModule('GalleryHeadModule');
		$this->AddModule('ProjectBoxModule');
		$this->AddModule('GallerySideModule');

		$this->AddJsFile('jquery.event.drag.js');
		$this->AddJsFile('/applications/galleries/js/gallery.js');
		$this->AddCssFile('/applications/galleries/design/gallery.css');

	}

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

	/*
	 * Ajax function for retrieving project data
	 */
	public function GetProject() {
		$this->PrepareController();
		$Request = Gdn::Request();
		$this->UserID = $Request->Post('UserID');
		$this->TransientKey = $Request->Post('TransientKey');
		$this->CurrentProject = $this->ProjectModel->GetCurrent($this->UserID);
		$UserModel = new UserModel();
		$this->User = $UserModel->GetSession($this->UserID);
		if ($this->User->Attributes['TransientKey'] == $this->TransientKey) {
			include_once(PATH_APPLICATIONS.DS.'/projects/views/project/box.php');

		} else {
				echo "Must Be Signed in to start a project";
		}
	}

/*------------------------------------- Main Selections -------------------------------*/
	/*
	 *
	 */
	public function ProjectSelect() {
		$Request = Gdn::Request();
		$Type = $Request->Post('Type');
		$Identifier = $Request->Post('Slug');
		$UserID = $Request->Post('UserID');
		$ProjectID = $Request->Post('ProjectID');
		$Method = $Request->Post('Action');
		/*echo $Type;
		echo '<br/>';
		echo $Identifier;
		echo '<br/>';
		echo $UserID;
		echo '<br/>';
		echo $ProjectID;
		echo '<br/>';
		echo $Method;
		echo '<br/>';*/
		if ($Type != 'uploads') {
			if ($Method == 'add')
				$Array = $this->_AddToSelection($ProjectID, $Type, $Identifier);
			elseif ($Method == 'remove')
				$Array = $this->_RemoveFromSelection($ProjectID, $Type, $Identifier);
		} else {
			if ($Method == 'add')
				$Array = $this->_AddToIncluded($ProjectID, $Type, $Identifier);
			elseif ($Method == 'remove')
				$Array = $this->_RemoveFromIncluded($ProjectID, $Type, $Identifier);
		}
		$CurrentProject = $this->ProjectModel->GetSingle($ProjectID);
		$CurrentSelection = $this->MyExplode($CurrentProject->Selected);
		if ($CurrentSelection[$Type] == $Identifier) {
			echo "Updated Successfully";
		} else {
			echo "Something went wrong";
		}

	}

	/*
	 *
	 */
	public function FrameSelect() {
		$Request = Gdn::Request();
		$UserID = $Request->Post('UserID');
		$Session = Gdn::Session();
		$ProjectID = $Request->Post('ProjectID');
		$FrameChoice = $Request->Post('Frame');
		if ($FrameChoice != 'none')
			$this->_AddToSelection($ProjectID, 'frame', $FrameChoice);
		else
			$this->_RemoveFromSelection($ProjectID, 'frame');
	}
/*------------------------------- Private selection functions --------------------------*/

	/*
	 *
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
			$Return = $this->_SaveSelection($ProjectID, $NewArray);
			return $Return;
	}

	/*
	 *
	 */
	private function _AddToIncluded($ProjectID, $Type, $UploadID) {

		$this->_SaveIncluded($ProjectID, $UploadID);
	}

	/*
	 *
	 */
	private function _SaveIncluded($ProjectID, $UploadID) {
		$CurrentProject = $this->ProjectModel->GetSingle($ProjectID);
		$CurrentSelection = $this->MyExplode($CurrentProject->Included);
		$NewArray = array();
			if (!empty($CurrentSelection)) {
				foreach ($CurrentSelection as $CurrentSlug) {
					if (!empty($CurrentSlug)) {
						$NewArray[] = $CurrentSlug;
					}
				}
			}
		$NewArray[] = $UploadID;
		$Serialized = $this->MyImplode($NewArray);
			$this->ProjectModel->Update('Project', array(
			'Included' => $Serialized
			), array('ProjectKey' => $ProjectID));

		//Redirect('/project');

	}

	/*
	 *
	 */
	private function _SaveSelection($ProjectID, $Selection) {


		$Serialized = $this->MyImplode($Selection);
			$this->ProjectModel->Update('Project', array(
			'Selected' => $Serialized
			), array('ProjectKey' => $ProjectID));

		//Redirect('/project');
			return $Serialized;

	}

	/*
	 *
	 */
	private function _RemoveFromSelection($ProjectID, $Type, $ItemSlug = '') {

		$Project = $this->ProjectModel->GetSingle($ProjectID);
		$Selection = $this->MyExplode($Project->Selected);
		$Found = array_search($ItemSlug, $Selection);
		unset($Selection[$Found]);
		$Serialized = $this->MyImplode($Selection);
		$this->ProjectModel->Update('Project', array(
			'Selected' => $Serialized
		), array('ProjectKey' => $ProjectID));

	}

	/*
	 *
	 */
	private function _RemoveFromIncluded($ProjectID, $Upload) {
		$Project = $this->ProjectModel->GetSingle($ProjectID);
		$Selection = $this->MyExplode($CurrentProject->Included);
		$Found = array_search($Upload, $Selection);
		unset($Selection[$Found]);
		$this->ProjectModel->Update('Project', array(
			'Included' => $Selection
		), array('ProjectKey' => $ProjectID));
	}

/*------------------------------- Function for administering projects ------------------*/

	/*
	 *
	 */
	public function SetCurrent() {
		$Request = Gdn::Request();
		$ProjectID = $Request->Post('ProjectID');
		$UserID = $Request->Post('UserID');
		$this->ProjectModel->SetCurrent($UserID, $ProjectID);
		echo 'UserID: '.$UserID;
		echo 'ProjectID: '.$ProjectID;

	}

	/*
	 *
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

	/*
	 * Function for setting stage of completion of projects.
	 * Allows for users to set the project to be completed by tins direct
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

	/*
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