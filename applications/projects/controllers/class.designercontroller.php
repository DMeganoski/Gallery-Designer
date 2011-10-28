<?php if (!defined('APPLICATION'))
	exit();

/**
 * Designer Controller class, manages and organizes pages that are used to customize
 * the current project's design and prepare it for print.
 */
class DesignerController extends ProjectsController {

	/**
	 * Array of classes (models) to include.
	 *
	 * @var type
	 */
	public $Uses = array('Form', 'GalleryItemModel', 'ProjectModel', 'GalleryUploadModel');

	public $SignedIn;

	/**
	 * Standard initialize, includes js files from the library
	 */
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
		 $this->AddCssFile('/themes/TinsDirect/design/custom.css');
		 if (C('Galleries.ShowFireEvents'))
			$this->DisplayFireEvent('WhileHeadInit');

		$this->FireEvent('WhileHeadInit');

      }
      $this->MasterView = 'default';
	  parent::Initialize();
	   }

	public function CheckSession() {
		$this->SignedIn = Gdn::Session()->IsValid();
	}

	/**
	 * Function for other functions to use, includes css and js files, as well as modules
	 */
	public function PrepareController() {

			$this->AddModule('GalleryHeadModule');
			$this->AddModule('ProjectBoxModule');
			$this->AddModule('GallerySideModule');

			$this->AddJsFile('jquery.event.drag.js');
			$this->AddJsFile('jquery.jrac.js');
			//$this->AddJsFile('/applications/galleries/js/gallery.js');
			//$this->AddCssFile('/applications/galleries/design/gallery.css');
			//$this->AddCssFile('/applications/galleries/design/gallerycustom.css');
			$this->CheckSession();

		}

	/*----------------------------------------Start of basic view functions --------------*/
	/**
	 * Index, default function. Displays all selected items in a drag and drop
	 * design environment.
	 */
	public function Index() {
			//GalleryController::$Class = 'designer';
			//GalleryController::$Category = 'home';
			$this->PrepareController();
			$Session = Gdn::Session();
			// make sure session is valid before allowing designing
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
				$Background = $Selection['backgrounds'];
				$this->BackgroundFile = $this->GalleryItemModel->GetWhere(array('Slug' => $Background))->FirstRow();
				$Base = $Selection['bases'];
				$this->BaseFile = $this->GalleryItemModel->GetWhere(array('Slug' => $Base))->FirstRow();

				$Frame = $Selection['frame'];
				$this->Frame = $Frame;

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

	/**
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

	/**
	 * Render function for creating a message in text for the project
	 */
	public function Text() {
			// css and modules
			$this->PrepareController();
			//$this->Form = new Gdn_Form('Project');
			$this->Form->SetModel($this->ProjectModel);
			$UserID = Gdn::Session()->UserID;
			$ProjectData = $this->ProjectModel->GetCurrent($UserID);
			$MessageStyles = $this->MyExplode($ProjectData->MessageStyle);
			$this->Form->AddHidden('FontStyle');
			// Add objects that don't exist
			$ProjectData->FontStyle = $MessageStyles['FontStyle'];
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
					$FontStyle = $FormValues['FontStyle'];
					$Style['FontStyle'] = $FontStyle;
					$SerializedStyle = $this->MyImplode($Style);
					$this->ProjectModel->Update('Project', array(
						'Message' => $FormValues['Message'],
						'MessageStyle' => $SerializedStyle
					), array('ProjectKey' => $ProjectData->ProjectKey));
					$Angle = 0;
					$x = 50;
					$y = 20;
					if (!empty($ProjectData->Message)) {
						//$this->DrawTextArc($Message, 135, 300, 200, TRUE);
						$this->TextImage = $this->_GenerateText($ProjectData->ProjectKey, $FontSize, $FontColor, $LineLength, $Angle, $x, $y, $FontName, $ProjectData->Message );
					}
				}
			} else {
				$this->TextImage = "/uploads/project/text/$ProjectData->ProjectKey.png";
			}

			$this->Render();
		}

	/* -------------------------------------- Start of Ajax functions -------------------*/

	/**
	 * Ajax function for saving the position of the items in the project
	 */
	public function Placement() {

			$Request = Gdn::Request();
			$Top = $Request->Post('top');
			$Left = $Request->Post('left');
			$Type = $Request->Post('imgID');
			$ProjectID = $Request->Post('ProjectID');

			//$Return = $this->_UpdateProjectOrder($ProjectID, $Type);

			$this->_SaveItemPosition($ProjectID, $Type, $Top, $Left);
			echo '<br>';
			//print_r($Return);
		}

	/**
	 * Private function for updating the 'z-index' of the items in the project
	 * @todo Not working at the moment
	 *
	 * @param type $ProjectID
	 * @param type $Type
	 * @return type
	 */
	private function _UpdateProjectOrder($ProjectID, $Type) {
			$CurrentProject = $this->ProjectModel->GetSingle($ProjectID);
			$Order = $this->MyExplode('-', $CurrentProject->Order);
			$Count = count($Order);
			$Found = array_search($Type, $Order);
				$Empty = array_search('', $Order);
				if ($Found) {
					unset($Order[$Found]);
					$Order[] = $Type;
				}
				if ($Empty) {
					unset($Order[$Empty]);
				}

			if ($Count > 1) {
				for($i=0; $i < $Count; $i++) {
					 $Return[$i] = $Order[$i];
				}
				$Serialized = $this->MyImplode('-', $Return);
			} else {
				$Order[] = $Type;
				$Serialized = $this->MyImplode('-', $Order);
			}
				$this->ProjectModel->Update('Project', array(
					'Order' => $Serialized
				), array('ProjectKey' => $ProjectID));
			return $Order;

		}

	/**
	 *
	 * @param type $ProjectID :
	 * @param type $Type :
	 * @param type $Top :
	 * @param type $Left :
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

	/**
	 * Private function for generating text into an image with the given parameters
	 *
	 * @param type $ProjectID : Number: the current project image is being generated for (used for name)
	 * @param type $FontSize : Number: the size of the generated text
	 * @param type $Color : String: the color of the generated text
	 * @param type $LineLength : Number: the limit on the number of characters allowed in one line
	 * @param type $Angle : Number: the angle at which to generate the text
	 * @param type $x : Number: the x-coordinate for the text to start
	 * @param type $y : Number: the y-coordinate for the text to start
	 * @param type $FontName : String: the name of the font to be generated
	 * @param type $Message : String: the message to generate in the image
	 *
	 * @return type String: the location of the newly generated image
	 */
	private function _GenerateText($ProjectID, $FontSize, $Color, $LineLength, $Angle, $x, $y, $FontName, $Message) {

		  // Determine the size of the image to generate
		  // Get the length of the string to insert
			$NewText = wordwrap($Message, $LineLength, "\n");
			$Lines = explode("\n", $NewText);
			$LineCount = count($Lines);

		  if ($LineCount < 2) {
			  $MessageLength = strlen($Message);
			  $Width = ($MessageLength * $FontSize * 4.7);
		  } else {
			  $Width = ($FontSize * $LineLength * 4);
		  }
		  $Height = ($LineCount * ($FontSize * 5));
			// Create the image
			// Create the image
		$im = imagecreatetruecolor($Width, $Height);

		// Create some colors
		$white = imagecolorallocate($im, 255, 255, 255);
		$grey = imagecolorallocate($im, 128, 128, 128);
		$black = imagecolorallocate($im, 0, 0, 0);
		$red = imagecolorallocate($im, 255, 0, 0);
		$green = imagecolorallocate($im, 0, 255, 0);
		$blue = imagecolorallocate($im, 0, 0, 255);
		// determine which colors to use
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
			case 'blue':
				$FontColor = $blue;
				$Shadow = $grey;
				$clear = $white;
				break;
			case 'green':
				$FontColor = $green;
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
		$y = $FontSize * 2.2;
		$total_width=0;
		$counter=0;

		/*for($i=0; $i<strlen($text); $i++) {

			//$text_to_write=urldecode(substr($text,$i,1)."%0D_");
			$dimensions = imagettfbbox($FontSize, $Angle, $FontFile, substr($text,$i,1));
			$total_width += ($dimensions[2]);

		}
		$dimensions = imagettfbbox($FontSize, $Angle, $FontFile, $text);


		$difference = $dimensions[2] - $total_width;


		// define starting locations
		$StringLength = strlen($text);
		$cx = $FontSize * $StringLength;
		$cy = $FontSize * 3;
		$cr = $FontSize;
		$degDelta = 90 / $StringLength;

		for ($x = 0; $x < $StringLength; $x++) {
			// Circular Text
			//$AX = ($cx - cos(deg2rad($degDelta * $x)) * $cr) + ($FontSize * $x);
			$AX = $FontSize * $x;
			$AY = ($cy - sin(deg2rad($degDelta * $x)) * $cr);
			$Middle = $StringLength / 2;
			if ($x < ($Middle)) {
				$y = ($Middle - $x);
				$AY = $AY - (($FontSize / 4) * -$y);
			} else {
				$y = ($x - $Middle);
				$AY = $AY - (($FontSize / 4) * -$y);
			}

			imagettftext($im, $FontSize, -($degDelta * $x + $degDelta / 2)+45 , $AX, $AY, $FontColor, $FontFile , substr($text, $x, 1));

		}
		*/


		imagettftext($im, $FontSize, $Angle, $x+1, $y+1, $Shadow, $FontFile, $text);
		imagettftext($im, $FontSize, $Angle, $x, $y, $FontColor, $FontFile, $text);
		$this->ImageTrim($im, $clear);
		//imageantialias($im, true);
		// Using imagepng() results in clearer text compared with imagejpeg()
		$NewImage = imagepng($im, PATH_UPLOADS.DS."project/text/$ProjectID.png");
		imagedestroy($im);
		return "/uploads/project/text/$ProjectID.png";
	  }



	public function GenerateCurve($ProjectID, $FontSize, $Color, $LineLength, $Angle, $x, $y, $FontName, $Message) {
		// image dimensions
		$imageWidth = 150;
		$imageHeight = 100;

		// font file
		$FontName = "Saved By Zero";
		$FontFile = PATH_APPLICATIONS.'/projects/design/fonts/'.$FontName.'.TTF';
		$fontSize = 10;

		// maximum number
		$max = 20;
		// current number
		$current = 10;
		// how many numbers to be displayed
		$divisions  = 6;

		// speedometer semicircle center
		$arcCenterX = 70;
		$arcCenterY = 90;

		// create image
		$image = imagecreate($imageWidth, $imageHeight);
		// make line appear smoother
		//imageantialias($image, true);
		// allocate colors
		$black = imagecolorallocate($image, 0, 0, 0);
		$white = imagecolorallocate($image, 255, 255, 255);
		imagefill($image, 0, 0, $white);

		imagearc($image, $arcCenterX, $arcCenterY, 100, 100, 180, 360, $black);

		$oneDivision = pi() / $max;

		$firstNum = round($max / $divisions);

		for($i = 1; $i < $divisions; $i++) {
		$num = $firstNum * $i;
		if ($num != $max) {

			$numAngle = $num * $oneDivision;

			$x = $arcCenterX - cos($numAngle) * 50;
			$y = $arcCenterY - sin($numAngle) * 50;

			$tangent = (- 2 * $x + 140) / (2 * $y - 180);
			$angle = 0 - rad2deg(atan($tangent));

			imagettftext($image, $fontSize, $angle, $x, $y, $black, $FontFile, $num);

			}
		}
		//$this->ImageTrim($image, $clear);
		//imageantialias($image, true);
		// Using imagepng() results in clearer text compared with imagejpeg()
		$NewImage = imagepng($image, PATH_UPLOADS.DS."project/text/curvetest.png");
		imagedestroy($image);
		echo "<img src='/uploads/project/text/curvetest.png'/>";
	}

	function DrawTextArc($str, $aStart, $aEnd, $iRadius, $bCCW) {
		$nFont = 5;

		// create image to store each character
		$xFont = imagefontwidth($nFont);
		$yFont = imagefontheight($nFont);
		$imgChar = imagecreatetruecolor($xFont, $yFont);
		// create overall image
		$iCentre = $iRadius + max($xFont, $yFont);
		$img = imagecreatetruecolor(2 * $iCentre, 2 * $iCentre);
		// sort out colours
		$colBG = imagecolorallocate($img, 255, 255, 255);
		$colBGchar = imagecolorallocate($imgChar, 255, 255, 255);
		$colFGchar = imagecolorallocate($imgChar, 0, 0, 0);
		imagefilledrectangle($img, 0, 0, 2 * $iCentre, 2 * $iCentre, $colBG);

		// arrange angles depending on direction of rotation
		if ($bCCW)
		{
		while ($aEnd < $aStart)
		{
		$aEnd += 360;
		}
		}
		else
		{
		while ($aEnd > $aStart)
		{
		$aEnd -= 360;
		}
		}

		$len = strlen($str);

		// draw each character individually
		for ($i = 0; $i < $len; $i++)
		{
		// calculate angle along arc
		$a = ($aStart * ($len - 1 - $i) + $aEnd * $i) / ($len - 1);

		// draw individual character
		imagefilledrectangle($imgChar, 0, 0, $xFont, $yFont, $colBGchar);
		imagestring($imgChar, $nFont, 0, 0, $str[$i], $colFGchar);

		// rotate character
		$imgTemp = imagerotate($imgChar, (int)$a + 90 * ($bCCW ? 1 : -1), $colBGchar);
		$xTemp = imagesx($imgTemp);
		$yTemp = imagesy($imgTemp);

		// copy to main image
		imagecopy($img, $imgTemp,
		$iCentre + $iRadius * cos(deg2rad($a)) - ($xTemp / 2),
		$iCentre - $iRadius * sin(deg2rad($a)) - ($yTemp / 2),
		0, 0, $xTemp, $yTemp);
		}
		imagejpeg($img, PATH_UPLOADS.DS."project/text/curvetest.png");
		echo '<img src="/uploads/project/text/curvetest.png"/>';
		}

	/**
	 * Function for trimming the edges off of generated text images.
	 *
	 * @param type $im : the generated image
	 * @param type $bg : the color to trim
	 * @param type $pad : the amount of padding
	 */
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