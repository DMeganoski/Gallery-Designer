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
			$this->AddJsFile('jquery.jrac.js');
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
				$Tin = $Selection['tins'];
				$this->TinFile = $this->GalleryItemModel->GetWhere(array('Slug' => $Tin))->FirstRow();

				$Frames = $this->MyExplode($CurrentProject->frame);
				$this->Frame = $Frames[0];

				$this->ProjectStage = $CurrentProject->ProjectStage;
				$IncludedUploads = $this->MyExplode($CurrentProject->Included);
				echo '<div class="Hidden">';
				$Upload = print_r($IncludedUploads);
				echo '</div>';
				$TopPositions = $this->MyExplode($CurrentProject->TopPositions);
				$LeftPositions = $this->MyExplode($CurrentProject->LeftPositions);
				// loop for multiple uploaded images
				foreach ($IncludedUploads as $Upload) {
					$UploadData = $this->GalleryUploadModel->GetWhere(array('UploadKey' => $Upload))->FirstRow();
					if (!empty($UploadData)) {
						$TopPosition = $TopPositions[$UploadData->FileName];
						$LeftPosition = $LeftPositions[$UploadData->FileName];
						$this->UploadList[$UploadData->FileName] = array('top' => $TopPosition, 'left' => $LeftPosition);
					}
				}
				// Define Message and set last position
				$Message = $CurrentProject->Message;
				$this->MessagePosition = array('top' => $TopPositions['Text'], 'left' => $LeftPositions['Text']);
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

		/*
		 * Function for diplaying the page where text is added or updated
		 */
	public function Text() {
			// css and modules
			$this->PrepareController();
			//$this->Form = new Gdn_Form('Project');
			$this->Form->SetModel($this->ProjectModel);
			$UserID = Gdn::Session()->UserID;
			$ProjectData = $this->ProjectModel->GetCurrent($UserID);
			$MessageStyles = $this->MyExplode($ProjectData->MessageStyle);
			// Add objects that don't exist
			$ProjectData->FontColor = $MessageStyles['FontColor'];
			$ProjectData->FontSize = $MessageStyles['FontSize'];
			$ProjectData->FontName = $MessageStyles['FontName'];
			$ProjectData->LineLength = $MessageStyles['LineLength'];
			// Set the form data
			$this->Form->SetData($ProjectData);
			//$this->Form->AddHidden('ProjectKey', $ProjectData->ProjectKey);
			if ($this->Form->AuthenticatedPostBack()) {
				if ($this->Form->Save()) {
					$this->StatusMessage = T("Your changes have been saved successfully.");
					//$this->RedirectUrl = Url('/item/'.$Item->Slug);
				} else {
					$this->StatusMessage = T("Your changes have been saved successfully.");
					$FormValues = $this->Form->FormValues();
					$FontColor = $FormValues['FontColor'];
					$Style['FontColor'] = $FontColor;
					$FontSize = $FormValues['FontSize'];
					$Style['FontSize'] = $FontSize;
					$FontName = $FormValues['FontName'];
					$Style['FontName'] = $FontName;
					$LineLength = $FormValues['LineLength'];
					$Style['LineLength'] = $LineLength;
					$SerializedStyle = $this->MyImplode($Style);
					$this->ProjectModel->Update('Project', array(
						'Message' => $FormValues['Message'],
						'MessageStyle' => $SerializedStyle
					), array('ProjectKey' => $ProjectData->ProjectKey));
					$Angle = 0;
					$x = 50;
					$y = 20;
					if (!empty($ProjectData->Message)) {
						$this->TextImage = $this->_GenerateText($ProjectData->ProjectKey, $FontSize, $FontColor, $LineLength, $Angle, $x, $y, $FontName, $ProjectData->Message );
					}
				}
			} else {
				$this->TextImage = "/uploads/project/text/$ProjectData->ProjectKey.png";
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

			//$Return = $this->_UpdateProjectOrder($ProjectID, $Type);

			$this->_SaveItemPosition($ProjectID, $Type, $Top, $Left);
			//print_r($Return);
		}

		/*
		 *
		 */
	private function _UpdateProjectOrder($ProjectID, $Type) {
			$CurrentProject = $this->ProjectModel->GetSingle($ProjectID);
			$Order = $this->MyExplode('-', $CurrentProject->Order);
			$Count = count($Order);
			$Found = array_search($Type, $Order);
				$Empty = array_search('', $Order);
				if ($Found) {
					unset($Order[$Found]);
				}
				if ($Empty) {
					unset($Order[$Empty]);
				}

			if ($Count > 1) {
				for($i=0; $i < $Count; $i++) {
					 $Return[$i] = $Order[$i];
				}


				$Order[] = $Type;
				$Return = $this->MyImplode('-', $Order);
			} else {
				$Order = $Type;
				$Return = $Order;
			}
				$this->ProjectModel->Update('Project', array(
					'Order' => $Return
				), array('ProjectKey' => $ProjectID));
			return $Order;

		}

		/*
		 *
		 */
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

		/*
		 * Function for generating an image of text
		 */
	private function _GenerateText($ProjectID, $FontSize, $Color, $LineLength, $Angle, $x, $y, $FontName, $Message) {

		  // Determine the size of the image to generate
		  // Get the length of the string to insert
			$NewText = wordwrap($Message, $LineLength, "\n");
			$Lines = explode("\n", $NewText);
			$LineCount = count($Lines);

		  if ($LineCount < 2) {
			  $MessageLength = strlen($Message);
			  $Width = ($MessageLength * $FontSize * 0.7);
		  } else {
			  $Width = ($FontSize * $LineLength);
		  }
		  $Height = ($LineCount * ($FontSize * 1.5));
			// Create the image
			// Create the image
		$im = imagecreatetruecolor($Width, $Height);

		// Create some colors
		$white = imagecolorallocate($im, 255, 255, 255);
		$grey = imagecolorallocate($im, 128, 128, 128);
		$black = imagecolorallocate($im, 0, 0, 0);
		$red = imagecolorallocate($im, 255, 0, 0);
		switch ($Color) {
			case 'white':
				$FontColor = $white;
				$Shadow = $black;
				$clear = $grey;
				break;
			case 'black':
				$FontColor = $black;
				$Shadow = $grey;
				$clear = $white;
				break;
			case 'red':
				$FontColor = $red;
				$Shadow = $grey;
				$clear = $white;
				break;
			default:
				$FontColor = $white;
				$Shadow = $black;
				$clear = $grey;
				break;
		}
		imagefilledrectangle($im, 0, 0, $Width, $Height, $clear);
		imagecolortransparent($im, $clear);

		// The text to draw
		$text = $NewText;

		// Replace path by your own font path
		$FontFile = PATH_APPLICATIONS.'/projects/design/fonts/'.$FontName.'.TTF';

		// Add the text
		$y = $FontSize * 1.2;
		$total_width=0;
		$counter=0;
		/*
		for($i=0; $i<strlen($text); $i++)
		{
			//$text_to_write=urldecode(substr($text,$i,1)."%0D_");
			$dimensions = imagettfbbox($FontSize, $Angle, $FontFile, substr($text,$i,1));
			$total_width += ($dimensions[2]);

		}

		$cx = 200;
		$cy = 100;
		$cr = 80;
		$degDelta = 360 / $LineLength;

		for ($x = 0; $x < $LineLength; $x++) {
			// Circular Text
			$AX = $cx - cos(deg2rad($degDelta * $x)) * $cr;
			$AY = $cy - sin(deg2rad($degDelta * $x)) * $cr;

			imagettftext($im, 20, -($degDelta * $x + $degDelta / 2)+90 , $AX, $AY, $color, 'arial.ttf', $text[$x]);

		}
		*/
		$dimensions = imagettfbbox($FontSize, $Angle, $FontFile, $text);


		$difference = $dimensions[2] - $total_width;

		imagettftext($im, $FontSize, $Angle, $x+1, $y+1, $Shadow, $FontFile, $text);
		imagettftext($im, $FontSize, $Angle, $x, $y, $FontColor, $FontFile, $text);
		$this->ImageTrim($im, $clear);
		// Using imagepng() results in clearer text compared with imagejpeg()
		$NewImage = imagepng($im, PATH_UPLOADS.DS."project/text/$ProjectID.png");
		imagedestroy($im);
		return "/uploads/project/text/$ProjectID.png";
	  }

	  public function ImageTrim(&$im, $bg, $pad=null){

		// Calculate padding for each side.
		if (isset($pad)){
			$pp = explode(' ', $pad);
			if (isset($pp[3])){
				$p = array((int) $pp[0], (int) $pp[1], (int) $pp[2], (int) $pp[3]);
			}else if (isset($pp[2])){
				$p = array((int) $pp[0], (int) $pp[1], (int) $pp[2], (int) $pp[1]);
			}else if (isset($pp[1])){
				$p = array((int) $pp[0], (int) $pp[1], (int) $pp[0], (int) $pp[1]);
			}else{
				$p = array_fill(0, 4, (int) $pp[0]);
			}
		}else{
			$p = array_fill(0, 4, 0);
		}

		// Get the image width and height.
		$imw = imagesx($im);
		$imh = imagesy($im);

		// Set the X variables.
		$xmin = $imw;
		$xmax = 0;

		// Start scanning for the edges.
		for ($iy=0; $iy<$imh; $iy++){
			$first = true;
			for ($ix=0; $ix<$imw; $ix++){
				$ndx = imagecolorat($im, $ix, $iy);
				if ($ndx != $bg){
					if ($xmin > $ix){ $xmin = $ix; }
					if ($xmax < $ix){ $xmax = $ix; }
					if (!isset($ymin)){ $ymin = $iy; }
					$ymax = $iy;
					if ($first){ $ix = $xmax; $first = false; }
				}
			}
		}

		// The new width and height of the image. (not including padding)
		$imw = 1+$xmax-$xmin; // Image width in pixels
		$imh = 1+$ymax-$ymin; // Image height in pixels

		// Make another image to place the trimmed version in.
		$im2 = imagecreatetruecolor($imw+$p[1]+$p[3], $imh+$p[0]+$p[2]);

		// Make the background of the new image the same as the background of the old one.
		$bg2 = imagecolorallocate($im2, ($bg >> 16) & 0xFF, ($bg >> 8) & 0xFF, $bg & 0xFF);
		imagefill($im2, 0, 0, $bg2);
		imagecolortransparent($im2, $bg2);

		// Copy it over to the new image.
		imagecopy($im2, $im, $p[3], $p[0], $xmin, $ymin, $imw, $imh);

		// To finish up, we replace the old image which is referenced.
		$im = $im2;
	}
}