<?php if (!defined('APPLICATION'))
	exit();

class SettingsController extends Gdn_Controller {

	public function Initialize() {
      parent::Initialize();

      $Controller = $Sender->ControllerName;
      //$Sender->Form = new Gdn_Form();

      if ($this->Head) {
      $this->AddJsFile('jquery.js');
         $this->AddJsFile('jquery.livequery.js');
         $this->AddJsFile('jquery.form.js');
         $this->AddJsFile('jquery.popup.js');
         $this->AddJsFile('jquery.gardenhandleajaxform.js');
         $this->AddJsFile('global.js');

      }
      $this->AddDefinition('apcavailable',self::ApcAvailable());
      $this->AddDefinition('uploaderuniq',uniqid(''));
      //$this->MasterView = 'admin';


      $PostMaxSize = Gdn_Upload::UnformatFileSize(ini_get('post_max_size'));
      $FileMaxSize = Gdn_Upload::UnformatFileSize(ini_get('upload_max_filesize'));
      $ConfigMaxSize = Gdn_Upload::UnformatFileSize(C('Garden.Upload.MaxFileSize', '1MB'));
      $MaxSize = min($PostMaxSize, $FileMaxSize, $ConfigMaxSize);
      $this->AddDefinition('maxuploadsize',$MaxSize);
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

	public function PrepareController() {
		$this->AddJsFile('jquery.qtip.js');
		$this->AddCssFile('jquery.qtip.css');
		$this->AddJsFile('gallery.js');

        //$GalleryHeadModule->GetData();
		$this->AddModule('GalleryHeadModule');
		$this->AddModule('GallerySideModule');
		$this->AddCssFile('gallery.css');
		$this->AddCssFile('fileupload.css');
		$this->AddJsFile('fileupload.js');
   }

	public function Index($Args) {
		$this->PrepareController();

		$this->Render();
	}





}