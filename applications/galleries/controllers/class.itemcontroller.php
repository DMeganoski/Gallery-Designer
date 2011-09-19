<?php if (!defined('APPLICATION'))
	exit();

class ItemController extends GalleriesController {

	public $Uses = array('Form', 'GalleryItemModel', 'SessionValidationModel',
		'GalleryUploadModel', 'GalleryCategoryModel', 'GalleryClassModel');

	public $GalleryItemModel;

	public static $SelectedClass;

	public static $SelectedCategory;

	public static $SelectedSlug;

	public function Initialize() {
		parent::Initialize();

		if ($this->Head) {
			$this->AddJsFile('jquery.js');
			$this->AddJsFile('css_browser_selector.js');
			$this->AddJsFile('jquery.livequery.js');
			$this->AddJsFile('jquery.form.js');
			$this->AddJsFile('jquery.popup.js');
			$this->AddJsFile('jquery.gardenhandleajaxform.js');
			$this->AddJsFile('global.js');

			$this->AddJsFile('jquery.ui.packed.js');

			$this->AddJsFile('/applications/projects/js/projectbox.js');
			$this->AddCssFile('/applications/projects/design/projectbox.css');

			$this->AddJsFile('/applications/projects/js/designer.js');
			$this->AddCssFile('/applications/projects/design/designer.css');
		}
		//$this->AddDefinition('apcavailable',self::ApcAvailable());
		//$this->AddDefinition('uploaderuniq',uniqid(''));
		$this->MasterView = 'default';

	}
	/*
	 * Adds css and js files common for all views
	 */
	public function PrepareController() {
		$this->AddJsFile('jquery.qtip.js');
		$this->AddCssFile('jquery.qtip.css');

		$this->AddJsFile('jquery-ui-1.8.15.custom.min.js');
		$this->AddCssFile('jquery-ui-1.8.15.custom.css');



		$this->AddJsFile('gallery.js');
		$this->AddJsFile('loader.js');
		$this->AddCssFile('gallery.css');
		$this->AddCssFile('styles.css');
		$this->AddJsFile('jquery.event.drag.js');

        //$GalleryHeadModule->GetData();

		if (C('Galleries.ShowFireEvents'))
			$this->DisplayFireEvent('AfterItemPrepare');

		$this->FireEvent('AfterItemPrepare');
	}

/*------------------------------------------- Render Functions ----------------------*/
	/*
	 * Main render, shows individual items.
	 */
	public function Index() {
		$this->PrepareController();
		$this->AddModule('GalleryHeadModule');
		$this->AddModule('GallerySideModule');

		$this->Slug = ArrayValue('0', $this->RequestArgs, 'notfound');
		$Action = ArrayValue('1', $this->RequestArgs, 'home');

		$this->UserID = Gdn::Session()->UserID;

		if ($this->Slug != 'notfound') {
			self::$SelectedSlug = $Slug;
			$this->FileData = $this->GalleryItemModel->GetSlug($this->Slug);
			self::$SelectedClass = $this->FileData->ClassLabel;
			$this->Type = self::$SelectedClass;
			$this->ProjectID = '1';
			$CategoryLabel = $this->GetCategoryLabel(utf8_encode($this->FileData->CategoryKey));
			self::$SelectedCategory = $CategoryLabel;

		}

		$Path = PATH_APPLICATIONS . DS . 'galleries/views/item/';

		$this->Title(T($this->FileData->Name.' - '.$this->FileData->Artist));
		$this->Head->Title($this->Head->Title());
		if (C('Galleries.ShowFireEvents'))
			$this->DisplayFireEvent('BeforeItemRender');

		$this->FireEvent('BeforeItemRender');
		$this->Render();
   }

   /*
    *  Render for page containing a form for editing an individual item
    */
	public function Edit($ItemSlug = '') {
		$this->PrepareController();
		$this->AddModule('GalleryHeadModule');
		$this->AddModule('GallerySideModule');
		$this->Permission('Gallery.Items.Upload');
        if ($Item->InsertUserID != $Session->UserID) {
            $this->Permission('Gallery.Manage');
		}
		$Item = $this->GalleryItemModel->GetSlug($ItemSlug);
        $this->AddJsFile('/js/library/jquery.autogrow.js');
        $this->AddJsFile('forms.js');

        $Session = Gdn::Session();
        $Item = $this->GalleryItemModel->GetSlug($ItemSlug);
        if (!$Item) {
            throw NotFoundException('Item');
        }


        $this->Form->SetModel($this->GalleryItemModel);
				$this->Form->AddHidden('Slug', $ItemSlug);
		        $this->Form->AddHidden('Large', $Item->Large, TRUE);
				$this->Form->AddHidden('Medium', $Item->Medium, TRUE);
				$this->Form->AddHidden('Frame', $Item->Frame, TRUE);
				$this->Form->AddHidden('Special', $Item->Special, TRUE);
				$this->Form->AddHidden('ClassLabel', $Item->ClassLabel, TRUE);
        $GalleryItemModel = $this->GalleryItemModel;
        //$this->ClassData = $ItemClassModel->GetWhere(array('Visible' => '1'));
         $this->Form->SetData($Item);

        if ($this->Form->AuthenticatedPostBack() === FALSE) {
        } else {
            if ($this->Form->Save() !== FALSE) {

                $this->StatusMessage = T("Your changes have been saved successfully.");
                $this->RedirectUrl = Url('/item/'.$Item->Slug);
            }
        }

		$this->Title(T($this->Name));
		$this->Head->Title($this->Head->Title());
        $this->Render();
    }

	/*
	 * Render function for upload page
	 */
	public function Upload($Args) {

		$this->Permission('Gallery.Items.Upload');

		$this->AddModule('GalleryHeadModule');
		$this->AddModule('GallerySideModule');
		$this->AddCssFile('uploadify.css');
		$this->AddJsFile('jquery.uploadify.js');
		$this->AddJsFile('flash_detect.1.0.4.min.js');
		$this->AddJsFile('/js/library/jquery.autogrow.js');
        $this->AddJsFile('forms.js');
		$this->PrepareController();
		$Session = Gdn::Session();
		if ($Session->IsValid()) {
			$this->UserID = $Session->UserID;
			$this->TransientKey = $Session->TransientKey();
			$this->Render();

		}
      }

	/*
	 * Render Function
	 * This is the page for scanning images already on the server
	 * Loads images first and saves them on submit
	 */
	public function Scan(){
	$this->PrepareController();
	$this->AddModule('GalleryHeadModule');
	$this->AddModule('GallerySideModule');
	GalleryController::$Class = 'item';
	GalleryController::$Category = 'home';
	$this->Permission('Gallery.Manage');

	$this->Form = new Gdn_Form();
	$Validation = new Gdn_Validation();
	$GalleryItemModel = $this->GalleryItemModel;
    $this->Form->SetModel($GalleryItemModel);

    if ($this->Form->AuthenticatedPostBack()) {

		$Path = PATH_ROOT.'/uploads/item/';
        $Classes = $this->GalleryClassModel->GetClasses();
        foreach($Classes as $Class) {
			$Label = $Class->ClassLabel;
            $ReturnedFiles = $this->GalleryItemModel->GetFilesInfo($Label);
            foreach($ReturnedFiles as $FileInfo) {
				$FileInfo['ClassLabel'] = $Label;

				$Large = self::ImageResize($Path.$Label.DS.$FileInfo['FileName'], $Path.$Label.DS.$FileInfo['Slug'].'L.jpg', 300, 300, 0);
				$Medium = self::ImageResize($Path.$Label.DS.$FileInfo['FileName'], $Path.$Label.DS.$FileInfo['Slug'].'M.jpg', 150, 150, 0);
				$Small = self::ImageResize($Path.$Label.DS.$FileInfo['FileName'], $Path.$Label.DS.$FileInfo['Slug'].'S.jpg', 100, 100, 0);
				$this->PutFile($FileInfo);
				}
	    }
	} else {
		if ($this->Form->ErrorCount() == 0) {
			$Saved = $this->GalleryItemModel->Save($ReturnedFiles);
        }
        if ($Saved) {
			$this->StatusMessage = T("Your changes have been saved successfully.");
		//$this->RedirectUrl = Url('/item/'.$Item->Slug);

		}
	}

	 $this->Render(PATH_APPLICATIONS . DS . 'galleries' . DS . 'views'.DS.'item'.DS.'scan.php');

      }

/*----------------------------------------- Upload and Scan Helper Functions ------------------*/

	/*
	 * Script for the Uploader to run after sucessful upload
	 * Saves file to server and creates thumbnail
	 */
	public function Uploadify() {

		$targetFolder = '/uploads'; // Relative to the root

		if (!empty($_FILES)) {
			$tempFile = $_FILES['Filedata']['tmp_name'];

			$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder . '/';
			$returnFile = $_FILES['Filedata']['name'];

			$file = $_FILES['Filedata']['name'];
			$file = utf8_decode($file);
			$file = ereg_replace("[^a-zA-Z0-9_.-\[\]()]", "", strtr($file, "()����������������������������������������������% ", "[]aaaaaeeeeiiiiooooouuuucAAAAAEEEEIIIIOOOOOUUUUC__"));
			$file = strtolower($file);

			$aux_targetFile = str_replace('//','/',$targetPath);
			$targetFile = str_replace('//','/',$targetPath) . $file;
			$GoodFile = $targetFile;
			if(file_exists($targetFile)) {
				// while file exists
				while ($ok != true) {
					if(file_exists($targetFile)) {
						$ok = false;
						$rand = rand(1000, 9999);
						$targetFile = $aux_targetFile . $rand . '_' . $file;
					} else {
						$ok = true;
						$file = $rand . '_' . $file;
					}
				}
			}
			$fileParts = pathinfo($targetFile);
			$Resized = self::ImageResize($GoodFile, $targetPath.$fileParts['filename'].'-Thumb.jpg', 100, 100, 0);
			$Thumb = $Resized;
			$Count = $this->GalleryItemModel->GetCount(array('FileName'=> $fileParts['basename']));
			// Now the same call produces a zero instead...
			if ($Count > 0) {
				$this->GalleryItemModel->Update('GalleryUpload', array(
					'FileName' => $fileParts['basename'],
					'Thumbnail' => $Thumb
				), array('FileName' => $fileParts['basename']));
			} else {
				$this->GalleryItemModel->Insert('GalleryUpload', array(
					'FileName' => $fileParts['basename'],
					'Thumbnail' => $Thumb
				));
			}
	move_uploaded_file($tempFile,$targetFile);

	echo "$file";
	}
}

	/*
	 * Function called on submit of the upload form
	 * @ Params $_REQUEST
	 */
	public function UploadifySavePost() {

		$Request = Gdn::Request();
		$Description = $Request->Post('Description');
		$UserID = $Request->Post('UserID');
		$TransientKey = $Request->Post('TransientKey');
		$numImgs = $Request->Post('numImgs');
		$UserModel = new UserModel();
		$User = $UserModel->GetSession($UserID);
		if ($User->Attributes['TransientKey'] == $TransientKey) {

			$ActivityModel = new ActivityModel();
			$ActivityModel->ClearNotificationQueue();

			for ($i = 0; $i <= $numImgs; $i++) {
				$img[$i] = $Request->Post('img'.$i);
			}
			foreach ($img as $ImageName) {
				$this->GalleryUploadModel->Update('GalleryUpload', array(
					'FileName' => $ImageName,
					'InsertUserID' => $UserID,
					'Description' => $Description,
				), array('FileName' => $ImageName));
			}
		}

	Redirect('/item/upload');
	}

	/*
	 * Function to determine whether the upload exists already or not.
	 */
	public function UploadifyCheckExists() {
		if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $_POST['filename'])) {
			echo 1;
		} else {
			echo 0;
		}
	}



	/*
	 * Function for storing individual files found on the server into the database
	 */
	public function PutFile($FileInfo) {
		$ExistingItem = $this->GalleryItemModel->GetCount(array('Slug' => $FileInfo['Slug']));
		if ($ExistingItem < 1) {
			$this->GalleryItemModel->Insert('GalleryItem', array(
				'Name' => $FileInfo['Name'],
				'Large' => $FileInfo['Large'],
				'FileName' => $FileInfo['FileName'],
				'ClassLabel' => $FileInfo['ClassLabel'],
				'ItemID' => $FileInfo['ItemID'],
				'CategoryCAPS' => $FileInfo['CategoryCAPS'],
				'CategoryKey' => $FileInfo['CategoryKey'],
				'Slug' => $FileInfo['Slug'],
				'Visible' => $FileInfo['Visible']
	    ));
	 } else {

	    if ($FileInfo['Visible'] == 0) {
	       $this->Visible = 0;
	    } else {
	       $this->Visible = 1;
	    }
	    $this->GalleryItemModel->Update('GalleryItem', array(
	       'Name' => $FileInfo['Name'],
	       'Large' => $FileInfo['Large'],
			'FileName' => $FileInfo['FileName'],
	       'ClassLabel' => $FileInfo['ClassLabel'],
	       'ItemID' => $FileInfo['ItemID'],
	       'CategoryCAPS' => $FileInfo['CategoryCAPS'],
	       'CategoryKey' => $FileInfo['CategoryKey'],
			'Visible' => $FileInfo['Visible']
	       ), array('Slug' => $FileInfo['Slug']));
		}
	}


	public function UploadDelete() {
		$Request = Gdn::Request();
		$UserID = $Request->Post('UserID');
		$UploadID = $Request->Post('ItemID');
		$TransientKey = $Request->Post('TransientKey');
		$UserModel = new UserModel();
		$User = $UserModel->GetSession($UserID);
		if ($User->Attributes['TransientKey'] == $TransientKey) {
			$Upload = $this->GalleryUploadModel->GetUploadID($ItemID);
			$Path = PATH_UPLOADS.DS;
			$UploadPath = $Path.$Upload->FileName;
			if (file_exists($UploadPath)) {
				echo "Original File Removed<br/>";
				fclose($UploadPath);
				chmod($UploadPath ,0777);
				unlink($UploadPath);
				$FileParts = pathinfo($UploadPath);
				if (file_exists($Path.$FileParts['filename'].'-Thumb.jpg')) {
					fclose($Path.$FileParts['filename'].'-Thumb.jpg');
					chmod($Path.$FileParts['filename'].'-Thumb.jpg', 0777);
					unlink($Path.$FileParts['filename'].'-Thumb.jpg');
					echo "Thumbnai Removed Too";

				}
			} else {
					echo "File already Deleted";
			}
			$this->GalleryUploadModel->Remove($UploadID);
		}
	}

/*-------------------------------------------------- Ajax functions ------------------------*/

	/*
	 * Function for retrieving a html list via ajax
	 */
	public function GetUploads() {
	$Request = Gdn::Request();
	$this->AddCssFile('/applications/galleries/design/gallery.css');
	$UserID = $Request->Post('UserID');

	$this->Uploads = $this->GalleryUploadModel->GetUploads('0', '', array('InsertUserID' => $UserID));

	echo '<div id="UploadItems">';
		echo '<div class="Heading">';
			echo '<h2>My Personal Uploads</h2>';
		echo '</div>';
	echo '<div class="Uploads">';
		$Uploads = $this->Uploads;
		echo '<ul id="Uploads">';
		$HTML = '';
		foreach ($Uploads as $File) {
			$FileName = $File->FileName;
			if (strlen($FileName)< 28) {
				$FileLabel = $FileName;
			} else {
				$FileLabel = substr($FileName, 0, 25);
				$FileLabel .= '...';
			}
			$FileParts = pathinfo($FileName);
			$BaseName = $FileParts['filename'];
			$HTML .= '<li class="UploadData" uploadid="'.$File->UploadKey.'">';
			$HTML .= '<img src="/uploads/'.$BaseName.'-Thumb.jpg" class="Thumb"></img>';
			$HTML .= '<br/>';
			$HTML .= '<strong>'.$FileLabel.'</strong>';
			$HTML .= '</br>';
			$HTML .= $File->Description;
			$HTML .= '<br/>';
			$HTML .= '<button type="button" class="UploadDelete Button" id="Delete" uploadid="'.$File->UploadKey.'">Delete Image</button>';
			$HTML .= '<button type="button" class="UploadSubmit Button" id="Upload" uploadid="'.$File->UploadKey.'">Add to Project</button>';
			$HTML .= '<div class="ClearFix"></div>';
			$HTML .= '</li>';

		}
		echo $HTML;
		echo '</ul></div></div><div class="ClearFix"></div>';
	}

	public function DisplayFireEvent($EventName) {
	   $Controller = $this->ControllerName;
	   echo '<img src="/applications/galleries/design/images/burn.png" class="FireEvent" content="'.$EventName.'"></img>';
	   echo '<div class="FireEvent">FireEvent: '.$Controller.':'.$EventName.'</div>';
   }

	public static function ImageResize($src, $dst, $width, $height, $crop=0){

		set_time_limit(60);

	if(!file_exists($src)) $Return = 0;
	if(!list($w, $h) = getimagesize($src)) $Return = 2;

	$fileParts = pathinfo($src);
	$type = strtolower($fileParts['extension']);
	if($type == 'jpeg') $type = 'jpg';
	switch($type){
		case 'bmp': $img = imagecreatefromwbmp($src);
			$Return = 1;
			break;
		case 'gif': $img = imagecreatefromgif($src);
			$Return = 1;
			break;
		case 'jpg': $img = imagecreatefromjpeg($src);
			$Return = 1;
			break;
		case 'png': $img = imagecreatefrompng($src);
			$Return = 1;
			break;
		default : return $type;
	}

	// resize
	if($crop){
		if($w < $width or $h < $height) return "Picture is too small!";
		$ratio = max($width/$w, $height/$h);
		$h = $height / $ratio;
		$x = ($w - $width / $ratio) / 2;
		$w = $width / $ratio;
	}
	else{
		if($w < $width and $h < $height) return "Picture is too small!";
		$ratio = min($width/$w, $height/$h);
		$width = $w * $ratio;
		$height = $h * $ratio;
		$x = 0;
	}

	$new = imagecreatetruecolor($width, $height);

	// preserve transparency
	if($type == "gif" or $type == "png"){
		imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
		imagealphablending($new, false);
		imagesavealpha($new, true);
	}

	imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);

	switch($type){
		case 'bmp': imagewbmp($new, $dst); break;
		case 'gif': imagegif($new, $dst); break;
		case 'jpg': imagejpeg($new, $dst); break;
		case 'png': imagepng($new, $dst); break;
	}
	return $Return;
	}

	/*
	 * The fucntions included in the model. Not sure why they are getting called from the controller...
	 */

	public function GetFilesInfo($Class, $Category = 'home') {
	 $Model = $this->GalleryItemModel;
	 $Files = $Model->GetFilesInfo($Class, $Category);
	 return $Files;
      }
    public function GetDirectory($GetClass) {
	 $Model = $this->GalleryItemModel;
	 $Files = $Model->GetDirectory($GetClass);
	 return $Files;
      }
    public function GetClasses() {
	 $Model = $this->GalleryClassModel;
	 $Result = $Model->GetClasses();
	 return $Result;
      }
    public function GetCategories($GetClass) {
	 $Model = $this->GalleryCategoryModel;
	 $Result = $Model->GetCategories(self::$Class);
	 return $Result;
      }
	public function GetCategoryLabel($CategoryKey) {

		  $Model = $this->GalleryCategoryModel;
		  $Model->GetCategoryLabel($CategoryKey);
	  }
    public function GetClassKey($GetClass) {
	 $Model = $this->GallerClassModel;
	 $Key = $Model->GetClassKey($GetClass);
	 return $Key;
      }
    public function GetCategoryKey($GetCategoryCaps) {
	 $Model = $this->GalleryCategoryModel;
	 $Key = $Model->GetCategoryKey($GetCategoryCaps);
	 return $Key;
      }
    public function VerifyCategory($VerifyCategory, $Class) {
	  $Model = $this->GalleryCategoryModel;
	  $Return = $Model->VerifyCategory($VerifyCategory, $Class);
      }
	public function SplitFileName($FileName) {
		  $this->GalleryItemModel->SplitFileName($FileName);
	  }

}
