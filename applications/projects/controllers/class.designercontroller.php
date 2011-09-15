<?php if (!defined('APPLICATION'))
	exit();

/*
 * Designer Controller class, manages and organizes pages that are used to customize
 * the current project and prepare it for print.
 */
class DesignerController extends ProjectsController {

	public $Uses = array('Form', 'GalleryItemModel', 'ProjectModel', 'GalleryUploadModel');

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

	/*----------------------------------------Start of basic view functions --------------*/
	/*
	 * Index, default function. Displays all selected items in a drag and drop
	 * design environment.
	 */
	public function Index() {
		GalleryController::$Class = 'designer';
		GalleryController::$Category = 'home';
		$this->PrepareController();
		$Session = Gdn::Session();
		if ($Session->IsValid()) {
			$Admin = $Session->CheckPermission('Projects.Projects.Manage');
			if ($Admin) {
				$UserID = GetValue(0, $this->RequestArgs, '');
				if (empty($UserID)) {
					$UserID = $Session->UserID;
				}
			} else {
				$UserID = $Session->UserID;
			}
			$this->CurrentProject = $this->ProjectModel->GetCurrent($UserID);
			$CurrentProject = $this->CurrentProject;
			// Background / cover data
			$Selection = $this->MyExplode($CurrentProject->Selected);
			$Background = $Selection['covers'];
			$this->BackgroundFile = $this->GalleryItemModel->GetWhere(array('Slug' => $Background))->FirstRow();
			$Tin = $this->MyExplode($CurrentProject->tins);
			$this->TinFile = $this->GalleryItemModel->GetWhere(array('Slug' => $Tins[0]))->FirstRow();

			$Frames = $this->MyExplode($CurrentProject->frame);
			$this->Frame = $Frames[0];

			$this->ProjectStage = $CurrentProject->ProjectStage;
			$IncludedUploads = $this->MyExplode($CurrentProject->Included);
			echo '<div class="Hidden">';
			$Upload = print_r($IncludedUploads);
			echo '</div>';
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
		}

		$this->Render();
	}

	/*
	 * Function for displaying a page where an item can be resized and cropped.
	 * This is most useful for custom uploads
	 */
	public function Resize() {
		$this->PrepareController();
		$ItemSlug = GetValue(0, $this->RequestArgs, '');
		if ($ItemSlug != '') {
			$this->AddModule('GalleryHeadModule');
			$Session = Gdn::Session();
			$UserID = $Session->UserID;
			$this->CurrentItem = $this->GalleryItemModel->GetWhere(array('Slug' => $ItemSlug))->FirstRow();
			$CurrentItem = $this->CurrentItem;
			echo $CurrentItem->FileName;

			$this->CurrentUpload = $this->GalleryUploadModel->GetUploads(0,0, array('UploadKey' => $UploadID))->FirstRow();

		}
		$this->Render();
	}

	public function Text() {
		$this->PrepareController();
		//$this->Form = new Gdn_Form('Project');
		$this->Form->SetModel($this->ProjectModel);
		$UserID = Gdn::Session()->UserID;
		$ProjectData = $this->ProjectModel->GetCurrent($UserID);
		$this->Form->SetData($ProjectData);
		//$this->Form->AddHidden('ProjectKey', $ProjectData->ProjectKey);
		if ($this->Form->AuthenticatedPostBack()) {
			if ($this->Form->Save()) {
                $this->StatusMessage = T("Your changes have been saved successfully.");
                //$this->RedirectUrl = Url('/item/'.$Item->Slug);
			} else {
				$FormValues = $this->Form->FormValues();
				$this->StatusMessage = T("Your changes have been saved successfully.");
				$this->ProjectModel->Update('Project', array(
					'Message' => $FormValues['Message']
				), array('ProjectKey' => $ProjectData->ProjectKey));
			}
		} else {

		}
		$this->Render();
	}

	public function TestSerial() {
		$Array = array('this' => 'that', 'these' => 'those', 'time' => 'then');
		print_r($Array);
		$Mine = $this->MyImplode($Array);
		echo '<br/>';
		echo $Mine;
		$Exploded = explode('-', $Mine);
		echo '<br/>';
		print_r($Exploded);
		$Mine2 = $this->MyExplode($Mine);
		echo '<br/>';
		print_r($Mine2);
	}

	/* -------------------------------------- Start of Ajax functions -------------------*/
	public function Placement() {
		$Request = Gdn::Request();
		$Top = $Request->Post('top');
		$Left = $Request->Post('left');
		$Type = $Request->Post('imgID');
		$ProjectID = $Request->Post('ProjectID');

		$Return = $this->_UpdateProjectOrder($ProjectID, $Type);

		$this->_SaveItemPosition($ProjectID, $Type, $Top, $Left);
		echo '<br/>';
		print_r($Return);
	}
	private function _UpdateProjectOrder($ProjectID, $Type) {
		$CurrentProject = $this->ProjectModel->GetSingle($ProjectID);
		$Order = explode('-', $CurrentProject->Order);
		if (count($Order) > 1) {
			$Found = array_search($Type, $Order);
			$Empty = array_search('', $Order);
			if ($Found) {
				unset($Order[$Found]);
			}
			if ($Empty) {
				unset($Order[$Empty]);
			}

			array_push($Order, $Type);
			$Return = implode('-', $Order);
		} else {
			$Order = $Type;
			$Return = $Order;
		}
			$this->ProjectModel->Update('Project', array(
				'Order' => $Type
			), array('ProjectKey' => $ProjectID));
		return $Order;

	}

	private function _SaveItemPosition($ProjectID = '', $Type = '', $Top= '', $Left = '') {
		if ($ProjectID != '') {
			$CurrentProject = $this->ProjectModel->GetSingle($ProjectID);
			$CurrentTopPositions = $this->MyExplode($CurrentProject->TopPositions);
			$CurrentTopPositions[$Type] = $Top;
			$CurrentLeftPositions = $this->MyExplode($CurrentProject->LeftPositions);
			$CurrentLeftPositions[$Type] = $Left;
			$NewTopPositions = $this->MyImplode($CurrentTopPositions);
			$NewLeftPositions = $this->MyImplode($CurrentLeftPositions);
			$this->ProjectModel->Update('Project', array(
				'TopPositions' => $NewTopPositions,
				'LeftPositions' => $NewLeftPositions
			), array('ProjectKey' => $ProjectID));
				echo $Top.'<br/>'.$Left.'<br/>'.$Type;
		}
	}

}