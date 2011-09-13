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
		//$this->AddJsFile('/applications/projects/js/projectbox.js');
		//$this->AddCssFile('/applications/projects/design/projectbox.css');
      $this->MasterView = 'default';
	  parent::Initialize();
   }

	public function PrepareController() {
		$this->AddJsFile('jquery.qtip.js');
		$this->AddCssFile('jquery.qtip.css');

		$this->AddJsFile('jquery-ui-1.8.15.custom.min.js');
		$this->AddCssFile('jquery-ui-1.8.15.custom.css');

		$this->AddJsFile('gallery.js');
		$this->AddJsFile('loader.js');
		$this->AddCssFile('gallery.css');

        //$GalleryHeadModule->GetData();

		if (C('Galleries.ShowFireEvents'))
			$this->DisplayFireEvent('AfterItemPrepare');

		$this->FireEvent('AfterItemPrepare');
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
		$UserID = $Request->Post('UserID');
		$TransientKey = $Request->Post('TransientKey');
		$this->CurrentProject = $this->ProjectModel->GetCurrent($UserID);
		$CurrentProject = $this->CurrentProject;

		$UserModel = new UserModel();
		$User = $UserModel->GetSession($UserID);
		if ($User->Attributes['TransientKey'] == $TransientKey) {
			if ($this->CurrentProject === FALSE) {
				echo 'No Project Currently Selected. Would you like to start a new one or select an existing one?';
			}
			echo '<div id="ProjectItems">';
			echo '<div class="Heading">';
				echo '<table align="Center">';
				echo '<tr class="Heading">';
				echo '<th colspan="2"><h2>My Current Project:  '.$this->CurrentProject->ProjectName.'</a><h2></th>';
				echo '</tr>';
				$Selection = $this->MyExplode($this->CurrentProject->Selected);
				if (!empty($Selection)) {
					$Tin = $this->GalleryItemModel->GetWhere(array('Slug' => $Selection['tins']))->FirstRow();
						if (!empty($Tin)) {
							echo '<tr>';
							if (!empty($Tin->Name))
								echo '<th colspan="2">Selected Background:  <a href="/item/'.$Tin->Slug.'">'.$Tin->Name.'</a></th>';
							else
								echo '<th colspan="2">Selected Background :  <a href="/item/'.$Tin->Slug.'">'.$Tin->Slug.'</a></th>';
						echo '</tr><tr>';
							echo '<td align="Center" class="Background">';
							echo '<img src="/uploads/item/tins/'.$Tin->Slug.'M.jpg"></img>';
							echo '</td>';
							echo '<td>';
							echo '<button type="button" id="TinRemove" class="Button TinRemove" itemtype="tins" itemslug="'.$Tin->Slug.'">Remove Tin</button>';
							echo '</td>';
						}

						$Background = $this->GalleryItemModel->GetWhere(array('Slug' => $Selection['covers']))->FirstRow();
						if (!empty($Background)) {
						echo '<tr>';
							if (!empty($Background->Name))
								echo '<th>Selected Background:  <a href="/item/'.$Background->Slug.'">'.$Background->Name.'</a></th>';
							else
								echo '<th>Selected Background :  <a href="/item/'.$Background->Slug.'">'.$Background->Slug.'</a></th>';
							$Frame = $Selection['frame'];
							if (!empty($Frame))
							echo '<th>Selected Frame: '.$Frame.'</th>';
						echo '</tr><tr>';
							echo '<td align="Center" class="Background">';
							echo '<div class=ProjectWrapper>';
							if (!empty($Frame)) {
								echo '<img src="/uploads/item/frames/'.strtolower($Frame).'.png" class="FrameSmall">';
							}
							echo '<img src="/uploads/item/covers/'.$Background->Slug.'S.jpg"></img>';
							echo '</td>';
							echo '<td>';
							echo '<button type="button" id="FrameRemove" class="Button FrameRemove" itemtype="frame" itemslug="'.$Frame.'">Remove Frame</button>';
							echo '<button type="button" id="CoverRemove" class="Button CoverRemove" itemtype="covers" itemslug="'.$Background->Slug.'">Remove Background</button>';
							echo '</td>';
							echo '</div>';
						}
			}
			echo '<div class="ClearFix"></div>';
			echo '</tr>';
			$IncludedUploads = $this->MyExplode($CurrentProject->Included);
			if (is_array($IncludedUploads)) {
				echo '<tr>';
				echo '<th colspan="2">Included Uploaded Images</th>';
				echo '</tr><tr><td colspan="2">';

				foreach ($IncludedUploads as $Upload) {
					$UploadData = $this->GalleryUploadModel->GetWhere(array('UploadKey' => $Upload))->FirstRow();
					if (!empty($UploadData)) {
						$TopPositions = $this->MyExplode($CurrentProject->TopPositions);
						$TopPosition = $TopPositions[$UploadData->FileName];
						$LeftPositions = $this->MyExplode($CurrentProject->LeftPositions);
						$LeftPosition = $LeftPositions[$UploadData->FileName];
						$this->UploadList[$UploadData->FileName] = array('top' => $TopPosition, 'left' => $LeftPosition);
				}
				}
				foreach ($this->UploadList as $Upload => $Positions) {
					if (!empty($UploadData)) {
					$FileParts = pathinfo($Upload);
					$BaseName = $FileParts['filename'];?>
							<img src="/uploads/<? echo $BaseName ?>-Thumb.jpg" class="Upload Draggable Individual" id="<? echo $Upload ?>"
								 projectid="<? echo $this->CurrentProject->ProjectKey ?>" style="<? echo 'top: '.$Positions['top'].'; left: '.$Positions['left'] ?>"></img>
						<? }}
				echo '</td></tr></table>';
				echo '</div>';
				}
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
		echo $Type;
		echo '<br/>';
		echo $Identifier;
		echo '<br/>';
		echo $UserID;
		echo '<br/>';
		echo $ProjectID;
		echo '<br/>';
		echo $Method;
		echo '<br/>';
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
		print_r($Array);

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
					if (!empty($CurrentSlug)) {
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
	public function Delete($ProjectID) {
		$ProjectID = GetValue(0, $this->RequestArgs, '');
		if (Gdn::Session()->IsValid()){
			$this->ProjectModel->DeleteProject($ProjectID);
		}
		Redirect('/project');
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

		}
	}

/*------------------- Functions for retrieving previous poisition of elements -----------*/

	/*
	 *
	 */
	public function GetPlacement() {
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