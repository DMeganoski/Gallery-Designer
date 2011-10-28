<?php if (!defined('APPLICATION'))
	exit();

/**
 * Controller for handling orders to be shipped.
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
		parent::Initialize();
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
}