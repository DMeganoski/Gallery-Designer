<?php if (!defined('APPLICATION')) exit();

// Define the plugin:
$PluginInfo['Galleries'] = array(
   'Name' => 'Galleries',
   'Description' => 'A plugin that lets you create custom photo galleries based on file names.',
   'Version' => '0.1',
   'Author' => "Darryl Meganoski",
   'AuthorEmail' => 'darryl@tinsdirect.com',
   'AuthorUrl' => 'http://tinsdirect.com',
   'HasLocale' => TRUE,
);

include dirname(__FILE__).'/class.uploadmodel.php';

class GalleriesPlugin extends Gdn_Plugin {

    public static $SelectedFile = array();
    public static $PublicDir = '/tinsdirect/uploads/item/';
    public static $Limit = 16;
    public static $Page = 1;
    public static $Class = 'default';
    public static $Category = 'home';

    public $GalleriesModel;


    public function UploadModel() {
       static $UploadModel = NULL;

      if ($UploadModel === NULL) {
         $UploadModel = new UploadModel();
      }
      return $UploadModel;
   }
   /**
    * PrepareController function.
    *
    * Adds CSS and JS includes to the header of the discussion or post.
    *
    * @access protected
    * @param mixed $Controller The hooked controller
    * @return void
    */
    protected function PrepareController($Controller) {
      //if (!$this->IsEnabled()) return;

      $Controller->ClearCssFiles();
      $Controller->AddCssFile('style.css');
      $Controller->AddCssFile($this->GetResource('design/gallery.css', FALSE, FALSE));
      $Controller->MasterView = 'default';
      $Controller->AddCssFile($this->GetResource('design/css/fileupload.css', FALSE, FALSE));
      $Controller->AddJsFile($this->GetResource('design/js/fileupload.js', FALSE, FALSE));
      $Controller->AddDefinition('apcavailable',self::ApcAvailable());
      $Controller->AddDefinition('uploaderuniq',uniqid(''));

      $PostMaxSize = Gdn_Upload::UnformatFileSize(ini_get('post_max_size'));
      $FileMaxSize = Gdn_Upload::UnformatFileSize(ini_get('upload_max_filesize'));
      $ConfigMaxSize = Gdn_Upload::UnformatFileSize(C('Garden.Upload.MaxFileSize', '1MB'));
      $MaxSize = min($PostMaxSize, $FileMaxSize, $ConfigMaxSize);
      $Controller->AddDefinition('maxuploadsize',$MaxSize);
   }

    /*
     * Create the controller for the gallery pages
     */
   public function PluginController_Gallery_Create($Sender) {
      $Controller = $Sender->ControllerName;
      $this->PrepareController($Sender);
      $this->EnableSlicing($Sender);
      $this->Dispatch($Sender, $Sender->RequestArgs);
      $Sender->Form = new Gdn_Form();
      $Sender->Title(T('Gallery'));



   }

   public function Controller_Index($Sender) {

   // Get Request Arguments
        $View = ArrayValue('0', $Sender->RequestArgs, 'default');
        $Category = ArrayValue('1', $Sender->RequestArgs, 'home');
        $Page= ArrayValue('2', $Sender->RequestArgs, '1');
        // Set the defaults
        // use the next line for items, maybe?
        if (!is_numeric($Page) || $Page < 1)
        $Page = 1;
        self::$Page = $Page;
        $Limit = C('Plugin.Gallery.PerPage', 16);
        self::$Limit = $Limit;
	$Path = PATH_PLUGINS . DS . 'Galleries' . DS . 'views';

        $this->GalleriesModel = new GalleriesModel();
        $ItemsModel = new Gdn_Model('GalleryItems');
        //$CountItems = 100;
        // check the requested class
        if ($View != 'default') {
            // check if there's a file for the view
            if (file_exists($Path.DS.$View.DS.'home.php')) {
                // check if the view is a gallery class
                //$VerifyClass = GalleriesModel::VerifyClass($View);
                //if ($VerifyClass == TRUE) {/
                    // if it is, set the global.
                    self::$Class = $View;

                    // now the category...
                    if ($Category != 'home') {
                        //$VerifyCategory = $GalleriesModel->VerifyCategory($Category,$Class);
                        //if ($VerifyCategory) {
                        self::$Category = $Category;
                     } else {
                            self::$Category = 'home';
                     }
                    } else {
                        //self::$Category = 'home';
                    }
                // check other views besides gallery classes
                } else if ($View == 'default') {
                    self::$Class = 'default';

                    if ($Category != 'home') {
                        //$VerifyCategory = $GalleriesModel->VerifyCategory($Category,$Class);
                        //if ($VerifyCategory) {
                        self::$Category = $Category;
                     } else {
                            self::$Category = 'home';
                     }
                } else if ($View == 'item') {
                    self::$Class = 'item';
		} else {
                   self::$Class = 'default';
                   $View = 'default';
		   self::$Category = 'home';
                }
	 $Sender->Head->Title($Sender->Head->Title());
        $Sender->View = ($Path.DS.$View.DS.$Category.'.php');
        $Sender->Render();
    }

   public function Controller_Upload($Sender) {
	 //// else set the data

	 $this->Form = new Gdn_Form;
	 $this->Form->SetModel($this->UploadModel());
	 if ($Sender->Form->AuthenticatedPostBack() === FALSE) {
	    //if(!empty($Sender->AboutMe)) {
	       //$Sender->Form->SetData($Sender->AboutMe);
	    //}
	    $FilesData = GalleriesModel::GetFilesInfo($Class, $Category, $Page);
	    //$this->Form->SetData($FilesData);

	    foreach ($Classes as $Class) {
	       //$this->UploadAllFiles($Label);
	       $Label = $Class->ClassLabel;
	       $AllStuff = GalleriesModel::GetFilesInfo($Label);
	       foreach ($AllStuff as $File) {
		  //save everything, for now.
		  $FileName = $File['FileName'];

	       }
	    }
	 } else {
	    if ($Sender->Form->Save() !== FALSE) {
            		$Sender->StatusMessage = Gdn::Translate("Your settings have been saved.");
            		 // Trying to record activity if the person changed his/her info
                  	// AddActivity($AboutMeUserID, 'Story', 'has updated his profile');

				}
     			else{
					$Sender->StatusMessage = T("Oops, changes not saved");
     			}
	 }
	 self::$Class = 'upload';
	 self::$Category = $Sender->RequestArgs;

	 echo $Sender->FetchView(PATH_PLUGINS . DS . 'Galleries' . DS . 'views'.DS.'link_files.php');
	 $Sender->ClearCssFiles();
	 $Sender->AddCssFile('style.css');
	 $Sender->AddCssFile($this->GetResource('design/gallery.css', FALSE, FALSE));
	 $Sender->AddCssFile($this->GetResource('design/css/fileupload.css', FALSE, FALSE));
	 $Sender->MasterView = 'default';
	 $Sender->Render(PATH_PLUGINS . DS . 'Galleries' . DS . 'views'.DS.'upload.php');

   }

   public function Controller_SendFile($Sender) {
	 //the following was taken from the upload plugin's postcontroller upload create

	 list($FieldName) = $Sender->RequestArgs;

	 $Sender->DeliveryMethod(DELIVERY_METHOD_JSON);
         $Sender->DeliveryType(DELIVERY_TYPE_VIEW);

         $Sender->FieldName = $FieldName;
	 $Sender->ApcKey = Gdn::Request()->GetValueFrom(Gdn_Request::INPUT_POST,'APC_UPLOAD_PROGRESS');

         // this will hold the IDs and filenames of the items we were sent. booyahkashaa.
         $ItemResponse = array();

         $FileData = Gdn::Request()->GetValueFrom(Gdn_Request::INPUT_FILES, $FieldName, FALSE);
         try {
	    //if (!$this->CanUpload)
	       //throw new FileUploadPluginUploadErrorException("You do not have permission to upload files",11,'???');

            if (!$Sender->Form->IsPostBack()) {
               $PostMaxSize = ini_get('post_max_size');
	       throw new FileUploadPluginUploadErrorException("The post data was too big (max {$PostMaxSize})",10,'???');
	    }

            if (!$FileData) {
               //$PostMaxSize = ini_get('post_max_size');
	       $MaxUploadSize = ini_get('upload_max_filesize');
	       //throw new FileUploadPluginUploadErrorException("The uploaded file was too big (max {$MaxUploadSize})",10,'???');
	       throw new FileUploadPluginUploadErrorException("No file data could be found in your post",10,'???');
	    }

            // Validate the file upload now.
	    $FileErr  = $FileData['error'];
	    $FileType = $FileData['type'];
            $FileName = $FileData['name'];
            $FileTemp = $FileData['tmp_name'];
	    $FileSize = $FileData['size'];
	    $FileKey  = ($Sender->ApcKey ? $Sender->ApcKey : '');

            if ($FileErr != UPLOAD_ERR_OK) {
               $ErrorString = '';
	       switch ($FileErr) {
	          case UPLOAD_ERR_INI_SIZE:
	             $MaxUploadSize = ini_get('upload_max_filesize');
		     $ErrorString = sprintf(T('The uploaded file was too big (max %s).'), $MaxUploadSize);
		     break;
		  case UPLOAD_ERR_FORM_SIZE:
		     $ErrorString = 'The uploaded file was too big';
		     break;
                  case UPLOAD_ERR_PARTIAL:
                     $ErrorString = 'The uploaded file was only partially uploaded';
	             break;
	          case UPLOAD_ERR_NO_FILE:
	             $ErrorString = 'No file was uploaded';
		     break;
                  case UPLOAD_ERR_NO_TMP_DIR:
                     $ErrorString = 'Missing a temporary folder';
	             break;
	          case UPLOAD_ERR_CANT_WRITE:
	             $ErrorString = 'Failed to write file to disk';
		     break;
		  case UPLOAD_ERR_EXTENSION:
                     $ErrorString = 'A PHP extension stopped the file upload';
                     break;
	       }

	       throw new FileUploadPluginUploadErrorException($ErrorString, $FileErr, $FileName, $FileKey);
         }

	    $FileNameParts = pathinfo($FileName);
	    $Extension = strtolower($FileNameParts['extension']);
	    $AllowedExtensions = C('Garden.Upload.AllowedFileExtensions', array("*"));
	    if (!in_array($Extension, $AllowedExtensions) && !in_array('*',$AllowedExtensions))
	       throw new FileUploadPluginUploadErrorException("Uploaded file type is not allowed.", 11, $FileName, $FileKey);

	    $MaxUploadSize = Gdn_Upload::UnformatFileSize(C('Garden.Upload.MaxFileSize', '1G'));
	    if ($FileSize > $MaxUploadSize) {
            $Message = sprintf(T('The uploaded file was too big (max %s).'), Gdn_Upload::FormatFileSize($MaxUploadSize));
            throw new FileUploadPluginUploadErrorException($Message, 11, $FileName, $FileKey);
	    }



	    $SaveFilename = md5(microtime()).'.'.strtolower($Extension);
	    $SaveFilename = '/FileUpload/'.substr($SaveFilename, 0, 2).'/'.substr($SaveFilename, 2);
	    $SavePath = UploadModel::PathUploads().$SaveFilename;
	    if (!is_dir(dirname($SavePath)))
            @mkdir(dirname($SavePath), 0777, TRUE);

	    if (!is_dir(dirname($SavePath)))
            throw new FileUploadPluginUploadErrorException("Internal error, could not save the file.", 9, $FileName);

	    $MoveSuccess = @move_uploaded_file($FileTemp, $SavePath);

	    if (!$MoveSuccess)
	       throw new FileUploadPluginUploadErrorException("Internal error, could not move the file.", 9, $FileName);

	    // Get the image dimensions (if this is an image).
	    list($ImageWidth, $ImageHeight) = UploadModel::GetImageSize($SavePath);

	    $Item = array(
	       'Name'            => $FileName,
	       'Type'            => $FileType,
	       'Size'            => $FileSize,
	       'ImageWidth'      => $ImageWidth,
	       'ImageHeight'     => $ImageHeight,
	       'InsertUserID'    => Gdn::Session()->UserID,
	       'DateInserted'    => date('Y-m-d H:i:s'),
	       'StorageMethod'   => 'local',
	       'Path'            => $SaveFilename
	    );
	    $ItemKey = $this->UploadModel()->Save($Item);

            $FinalImageLocation = '';
            $PreviewImageLocation = Upload::ThumbnailUrl($Media);
	    //$PreviewImageLocation = Asset('plugins/FileUpload/images/file.png');
	    //if (getimagesize($ScratchFileName)) {
	    //$FinalImageLocation = Asset(
	       //'uploads/'
//               .FileUploadPlugin::FindLocalMediaFolder($MediaID, Gdn::Session()->UserID, FALSE, TRUE)
//               .'/'
//               .$MediaID
//               .'.'
//               .GetValue('extension', pathinfo($FileName), '')
//            );
//            $PreviewImageLocation = Asset('uploads/FileUpload/scratch/'.$TempFileName);
//         }
	    $ItemResponse = array(
	       'Status'             => 'success',
	       'ItemKey'            => $ItemKey,
	       'Filename'           => $FileName,
	       'Filesize'           => $FileSize,
	       'FormatFilesize'     => Gdn_Format::Bytes($FileSize,1),
	       'ProgressKey'        => $Sender->ApcKey ? $Sender->ApcKey : '',
	       'PreviewImageLocation' => Url($PreviewImageLocation),
	       'FinalImageLocation' => Url(UploadModel::Url($SaveFilename))
	    );

	 } catch (FileUploadPluginUploadErrorException $e) {

	    $MediaResponse = array(
	       'Status'          => 'failed',
	       'ErrorCode'       => $e->getCode(),
	       'Filename'        => $e->getFilename(),
	       'StrError'        => $e->getMessage()
	    );
	    if (!is_null($e->getApcKey()))
	       $MediaResponse['ProgressKey'] = $e->getApcKey();

	    if ($e->getFilename() != '???')
	       $MediaResponse['StrError'] = '('.$e->getFilename().') '.$MediaResponse['StrError'];
	 }

	 $Sender->SetJSON('MediaResponse', $MediaResponse);
	 $Sender->Render(PATH_PLUGINS . DS . 'Galleries' . DS . 'views'.DS.'upload.php');
      }

   public function Controller_UploadAlt($Sender) {

   }

   public function Controller_Delete($Sender) {
      list($Action, $ItemKey) = $Sender->RequestArgs;
      $Sender->DeliveryMethod(DELIVERY_METHOD_JSON);
      $Sender->DeliveryType(DELIVERY_TYPE_VIEW);

      $Delete = array(
         'ItemKey'   => $ItemKey,
         'Status'    => 'failed'
      );

      $Media = $this->UploadModel()->GetKey($ItemKey);

      if ($Item) {
         $Delete['Item'] = $Item;
         $UserID = GetValue('UserID', Gdn::Session());
         if (GetValue('InsertUserID', $Item, NULL) == $UserID || Gdn::Session()->CheckPermission("Garden.Settings.Manage")) {
            $this->UploadModel()->Delete($Item, TRUE);
            $Delete['Status'] = 'success';
         }
      }

      $Sender->SetJSON('Delete', $Delete);
      $Sender->Render($this->GetView('blank.php'));
   }

   protected function TrashFile($ItemSlug) {
      $Item = $this->UploadModel()->GetID($ItemSlug);

      if ($Media) {
         $this->UploadModel()->Delete($Media);
         $Deleted = FALSE;

         if (!$Deleted) {
            $DirectPath = UploadModel::PathUploads().DS.$Item->Path;
            if (file_exists($DirectPath))
               $Deleted = @unlink($DirectPath);
         }

         if (!$Deleted) {
            $CalcPath = GalleriesPlugin::FindLocalMedia($Item, TRUE, TRUE);
            if (file_exists($CalcPath))
               $Deleted = @unlink($CalcPath);
         }

      }
   }

   public function Download($Sender) {
      //if (!$this->IsEnabled()) return;
      //if (!$this->CanDownload) throw new PermissionException("File could not be streamed: Access is denied");

      if (strlen($ItemSlug) > 6) {

      }
      list($ItemSlug) = $Sender->RequestArgs;
      $Item = $this->UploadModel()->GetSlug($ItemSlug);

      if (!$Item) return;

      $Filename = Gdn::Request()->Filename();
      if (!$Filename) $Filename = $Item->Name;

      $DownloadPath = CombinePaths(array(UploadModel::PathUploads(),GetValue('Path', $Item)));

      if (in_array(strtolower(pathinfo($Filename, PATHINFO_EXTENSION)), array('bmp', 'gif', 'jpg', 'jpeg', 'png')))
         $ServeMode = 'inline';
      else
         $ServeMode = 'attachment';

      $this->EventArguments['Item'] = $Item;
      $this->FireEvent('BeforeDownload');

      return Gdn_FileSystem::ServeFile($DownloadPath, $Filename, '', $ServeMode);
      throw new Exception('File could not be streamed: missing file ('.$DownloadPath.').');

      exit();
   }

    /**
    * AttachAllFiles function.
    *
    * @access protected
    * @param mixed $ScannedFilesData
    * @param mixed $AllFilesData
    * @param mixed $ForeignID
    * @param mixed $ForeignTable
    * @return void
    */
    protected function UploadAllFiles($ScannedFilesData, $AllFilesData, $ForeignID, $ForeignTable) {
      if (!$this->IsEnabled()) return;

      // No files attached
      if (!$ScannedFilesData) return;

      $SuccessFiles = array();
      foreach ($ScannedFilesData as $ItemData) {
	 $ItemSlug = $ItemData->Slug;
         $Attached = $this->UploadFile($ItemSlug, $ForeignID, $ForeignTable);
         if ($Attached)
            $SuccessFiles[] = $FileID;
      }

      // clean up failed and unattached files
      $DeleteKeys = array_diff($AllFilesData, $SuccessFiles);
      foreach ($DeleteIDs as $DeleteID) {
         $this->TrashFile($DeleteID);
      }
   }

   protected function UploadFile($ItemData, $ForeignID, $ForeignType) {
      $Item = $this->UploadModel()->GetSlug($ItemSlug);
      if ($Item) {
         $Item->ForeignID = $ForeignID;
         $Item->ForeignTable = $ForeignType;
         try {
//            $PlacementStatus = $this->PlaceMedia($Media, Gdn::Session()->UserID);
            $this->UploadModel()->Save($Item);
         } catch (Exception $e) {
            die($e->getMessage());
            return FALSE;
         }
         return TRUE;
      }
      return FALSE;
   }

   protected function PlaceFile(&$Item, $UserID) {
      $NewFolder = GalleriesPlugin::FindLocalMediaFolder($Item->ItemSlug, $UserID, TRUE, FALSE);
      $CurrentPath = array();
      foreach ($NewFolder as $FolderPart) {
         array_push($CurrentPath, $FolderPart);
         $TestFolder = CombinePaths($CurrentPath);

         if (!is_dir($TestFolder) && !@mkdir($TestFolder, 0777, TRUE))
            throw new Exception("Failed creating folder '{$TestFolder}' during PlaceMedia verification loop");
      }

      $FileParts = pathinfo($Media->Name);
      $SourceFilePath = CombinePaths(array($this->PathUploads(),$Media->Path));
      $NewFilePath = CombinePaths(array($TestFolder,$Media->MediaID.'.'.$FileParts['extension']));
      $Success = rename($SourceFilePath, $NewFilePath);
      if (!$Success)
         throw new Exception("Failed renaming '{$SourceFilePath}' -> '{$NewFilePath}'");

      $NewFilePath = GalleriesPlugin::FindLocalMedia($Item, FALSE, TRUE);
      $Item->Path = $NewFilePath;

      return TRUE;
   }

   public static function FindLocalMediaFolder($ItemSlug, $UserID, $Absolute = FALSE, $ReturnString = FALSE) {
      $DispersionFactor = C('Plugin.FileUpload.DispersionFactor',20);
      $FolderID = $ItemSlug % $DispersionFactor;
      $ReturnArray = array('FileUpload',$FolderID);

      if ($Absolute)
         array_unshift($ReturnArray, UploadModel::PathUploads());

      return ($ReturnString) ? implode(DS,$ReturnArray) : $ReturnArray;
   }
   public static function FindLocalMedia($ItemData, $Absolute = FALSE, $ReturnString = FALSE) {
      $ArrayPath = FileUploadPlugin::FindLocalMediaFolder($ItemData->ItemSlug, $ItemData->InsertUserID, $Absolute, FALSE);

      $FileParts = pathinfo($ItemData->Name);
      $RealFileName = $ItemData->MediaID.'.'.$FileParts['extension'];
      array_push($ArrayPath, $RealFileName);

      return ($ReturnString) ? implode(DS, $ArrayPath) : $ArrayPath;
   }

   public static function ApcAvailable() {
      $ApcAvailable = TRUE;
      if ($ApcAvailable && !ini_get('apc.enabled')) $ApcAvailable = FALSE;
      if ($ApcAvailable && !ini_get('apc.rfc1867')) $ApcAvailable = FALSE;

      return $ApcAvailable;
   }


   public function UtilityController_GalleryThumbnail_Create($Sender, $Args) {
      $SubPath = implode('/', $Args);
      $Path = UploadModel::PathUploads()."/$SubPath";
      if (!file_exists($Path))
         throw NotFoundException('File');

      // Figure out the dimensions of the upload.
      $ImageSize = getimagesize($Path);
      $SHeight = $ImageSize[1];
      $SWidth = $ImageSize[0];

      $Options = array();

      $ThumbHeight = UploadModel::ThumbnailHeight();
      $ThumbWidth = UploadModel::ThumbnailWidth();

      if (!$ThumbHeight || $SHeight < $ThumbHeight) {
         $Height = $SHeight;
         $Width = $SWidth;
      } else {
         $Height = $ThumbHeight;
         $Width = round($Height * $SWidth / $SHeight);
      }

      if ($ThumbWidth && $Width > $ThumbWidth) {
         $Width = $ThumbWidth;

         if (!$ThumbHeight) {
            $Height = round($Width * $SHeight / $SWidth);
         } else {
            $Options['Crop'] = TRUE;
         }
      }

      $TargetPath = UploadModel::PathUploads()."/thumbnails/$SubPath";
      if (!file_exists(dirname($TargetPath))) {
         mkdir(dirname($TargetPath), 0777, TRUE);
      }
      Gdn_UploadImage::SaveImageAs($Path, $TargetPath, $Height, $Width, $Options);

      $Url = UploadModel::Url("/thumbnails/$SubPath");
      Redirect($Url, 302);
//      Gdn_FileSystem::ServeFile($TargetPath, basename($Path), '', 'inline');
   }



    /**
     * DrawAttachFile function.
     *
     * Helper method that allows the plugin to insert the file uploader UI into the
     * Post Discussion and Post Comment forms.
     *
     * @access public
     * @param mixed $Sender
     * @return void
     */

    public function Data($Path, $Default = '' ) {
        $Result = GetValueR($Path, $this->Data, $Default);
        return $Result;
    }

    public function SetData($Key, $Value = NULL, $AddProperty = FALSE) {
      if (is_array($Key)) {
         $this->Data = array_merge($this->Data, $Key);

         if ($AddProperty === TRUE) {
            foreach ($Key as $Name => $Value) {
               $this->$Name = $Value;
            }
         }
         return;
      }

      $this->Data[$Key] = $Value;
      if($AddProperty === TRUE) {
         $this->$Key = $Value;
      }
      return $Value;
    }

    /*
     * Renders Modules, dependent on the controller name
     */
    public function Base_Render_Before(&$Sender) {
        $route['gallery/([a-z]+)/(\d+)'] = "$1/$2";
        $Controller = $Sender->ControllerName;
        $Session = Gdn::Session();
        //$Hide=Gdn::Config('GalleryModule.Hide', TRUE);

	//if($Hide && !$Session->IsValid())	return;

        if ($Controller == "plugincontroller") {
	    $Sender->MasterView = 'default';
	    $Menu = $Sender->EventArguments['sidemenu'];
	    $Menu->ClearGroups;
            include_once(PATH_PLUGINS.DS.'Galleries'.DS.'class.galleryheadmodule.php');
            include_once(PATH_PLUGINS.DS.'Galleries'.DS.'class.gallerysidemodule.php');
            $GalleryHeadModule = new GalleryHeadModule($Sender);
            //$GalleryHeadModule->GetData();
            $GallerySideModule = new GallerySideModule($Sender);
            $Sender->AddModule($GalleryHeadModule);
            $Sender->AddModule($GallerySideModule);
            $Session = Gdn::Session();
            //$Limit = Gdn::Config('GalleriesHeadModule.Limit', 6);
            //if (!is_numeric($Limit))
                //$Limit = 6;

            //$Sender->AddDefinition('GalleriesModuleLimit', $Limit);
        }
        if(in_array($Sender->ControllerName, array('plugincontroller','categoriescontroller', 'discussioncontroller', 'discussionscontroller'))) {
            include_once(PATH_PLUGINS.DS.'Galleries'.DS.'class.galleryvideomodule.php');
            $GalleryVideoModule = new GalleryVideoModule($Sender);
            $Sender->AddModule($GalleryVideoModule);
        }
    }

    public function Setup() {
      $this->Structure();
       $this->PermissionSetup();
       $SQL = Gdn::SQL();
       SaveToConfig('Garden.Upload.MaxFileSize', '1G');

//Set Classes
$SQL->Replace('GalleryClass', array('ClassLabel' => 'default', 'Visible' => '1'),
        array('ClassKey' => 1), TRUE);

$SQL->Replace('GalleryClass', array('ClassLabel' => 'tins', 'Visible' => '1'),
        array('ClassKey' => 2), TRUE);

$SQL->Replace('GalleryClass', array('ClassLabel' => 'covers', 'Visible' => '1'),
        array('ClassKey' => 3), TRUE);

$SQL->Replace('GalleryClass', array('ClassLabel' => 'builder', 'Visible' => '1'),
        array('ClassKey' => 4), TRUE);

$SQL->Replace('GalleryClass', array('ClassLabel' => 'customart', 'Visible' => '0'),
        array('ClassKey' => 5), TRUE);

$SQL->Replace('GalleryClass', array('ClassLabel' => 'completepackages', 'Visible' => '0'),
        array('ClassKey' => 10), TRUE);

// Class 1 Categories (default)
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'home', 'Visible' => '1', 'ClassKey' => 1),
	array('CategoryKey' => 101), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'howitworks', 'Visible' => '1', 'ClassKey' => 1),
	array('CategoryKey' => 102), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'pricing', 'Visible' => '1', 'ClassKey' => 1),
	array('CategoryKey' => 103), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'contactus', 'Visible' => '1', 'ClassKey' => 1),
	array('CategoryKey' => 104), TRUE);
// Class 2 Categories (tins)
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'home', 'Visible' => '1', 'ClassKey' => 2),
	array('CategoryKey' => 201), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'round', 'Visible' => '1', 'ClassKey' => 2),
	array('CategoryKey' => 202), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'square', 'Visible' => '1', 'ClassKey' => 2),
	array('CategoryKey' => 203), TRUE);
// Class 3 Categories (covers)
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'home', 'Visible' => '1', 'ClassKey' => 3),
	array('CategoryKey' => 301), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'artnouveau', 'Visible' => '1', 'ClassKey' => 3),
	array('CategoryKey' => 302), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'cityscapes', 'Visible' => '1', 'ClassKey' => 3),
	array('CategoryKey' => 303), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'holiday', 'Visible' => '1', 'ClassKey' => 3),
	array('CategoryKey' => 304), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'impressionism', 'Visible' => '1', 'ClassKey' => 3),
	array('CategoryKey' => 305), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'industry', 'Visible' => '1', 'ClassKey' => 3),
	array('CategoryKey' => 306), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'nature', 'Visible' => '1', 'ClassKey' => 3),
	array('CategoryKey' => 307), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'realism', 'Visible' => '1', 'ClassKey' => 3),
	array('CategoryKey' => 308), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'renaissance', 'Visible' => '1', 'ClassKey' => 3),
	array('CategoryKey' => 309), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'romanticism', 'Visible' => '1', 'ClassKey' => 3),
	array('CategoryKey' => 310), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'space', 'Visible' => '1', 'ClassKey' => 3),
	array('CategoryKey' => 311), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'textures', 'Visible' => '1', 'ClassKey' => 3),
	array('CategoryKey' => 312), TRUE);
// Class 4 Categories (builder)
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'home', 'Visible' => '1', 'ClassKey' => 4),
	array('CategoryKey' => 401), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'text', 'Visible' => '1', 'ClassKey' => 4),
	array('CategoryKey' => 402), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'logo', 'Visible' => '1', 'ClassKey' => 4),
	array('CategoryKey' => 403), TRUE);
// Class 5 Categories (templates)
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'home', 'Visible' => '1', 'ClassKey' => 5),
	array('CategoryKey' => 501), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'requirements', 'Visible' => '1', 'ClassKey' => 5),
	array('CategoryKey' => 502), TRUE);

    }

   public function Structure() {

   $Structure = Gdn::Structure();

   $Structure->Table('Upload')
        ->PrimaryKey('ItemKey')
        ->Column('ItemID', 'varchar(50)')
        ->Column('ClassKey', 'varchar(50)')
        ->Column('CategoryKey', 'varchar(50)')
	->Column('CategoryCAPS', 'varchar(50)')
	->Column('ImageWidth', 'usmallint', NULL)
	->Column('ImageHeight', 'usmallint', NULL)
	->Column('StorageMethod', 'varchar(24)')
        ->Column('Name', 'varchar(100)')
        ->Column('Visible', 'tinyint(1)', '1')
	->Column('Private', 'tinyint(1)', '1')
        ->Column('DateInserted', 'datetime')
        ->Column('Description', 'text', TRUE)
        ->Column('Path', 'varchar(255)', TRUE)
        ->Column('Slug', 'varchar(200)', TRUE)
        ->Column('InsertUserID', 'int(11)')
        //->Column('CountComments', 'int', '0')
        ->Column('CountUses', 'int', '0')
        ->Set(FALSE, FALSE); // If you omit $Explicit and $Drop they default to false.

   $Structure->Table('GalleryClass')
        ->PrimaryKey('ClassKey')
        ->Column('ClassLabel', 'varchar(50)')
        ->Column('Visible', 'tinyint(1)', '1')
        ->Set(FALSE,FALSE);

   $Structure->Table('GalleryCategory')
        ->PrimaryKey('CategoryKey')
        ->Column('ClassKey', 'varchar(50)')
        ->Column('CategoryLabel', 'varchar(50)')
        ->Column('Visible', 'tinyint(1)', '1')
        ->Set(FALSE, FALSE);

}

   public function PermissionSetup() {
      $Database = Gdn::Database();
        $PermissionModel = Gdn::PermissionModel();
        $PermissionModel->Database = $Database;
        $PermissionModel->SQL = Gdn::SQL();
        // Define some global permissions.
        $PermissionModel->Define(array(
        'Gallery.Item.Add',
        'Gallery.Items.Manage',
        'Gallery.Manage',
	'Gallery.Docs.Download',
	'Gallery.Docs.Manage'
        //'Gallery.Comments.Manage'
        ));

        if (isset($$PermissionTableExists) && $PermissionTableExists) {
        // Set the intial member permissions.
        $PermissionModel->Save(array(
            'RoleID' => 8,
            'Gallery.Item.Add' => 0,
	    'Gallery.Docs.Download' => 1
            ));

        // Set the initial administrator permissions.
        $PermissionModel->Save(array(
            'RoleID' => 16,
            'Gallery.Item.Add' => 1,
            'Gallery.Items.Manage' => 1,
	    'Gallery.Docs.Download' => 1,
	    'Gallery.Docs.Manage' => 1,
            'Gallery.Manage' => 1,
            //'Gallery.Comments.Manage' => 1
          ));
        }
   }
}

class GalleriesPluginUploadErrorException extends Exception {

   protected $Filename;
   protected $ApcKey;

   public function __construct($Message, $Code, $Filename, $ApcKey = NULL) {
      parent::__construct($Message, $Code);
      $this->Filename = $Filename;
      $this->ApcKey = $ApcKey;
   }

   public function getFilename() {
      return $this->Filename;
   }

   public function getApcKey() {
      return $this->ApcKey;
   }

}