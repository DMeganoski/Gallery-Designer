<?php if (!defined('APPLICATION'))
	exit();


/**
 *
 */
class InfoController extends GalleriesController {

	public $Uses = array('GalleryCategoryModel', 'GalleryClassModel');

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
			$this->AddJsFile('/applications/projects/js/projectbox.js');
			$this->AddCssFile('/applications/projects/design/projectbox.css');
		}

		$this->MasterView = 'default';
   }

	public function PrepareController() {

		$this->AddJsFile('jquery.qtip.js');
		$this->AddCssFile('jquery.qtip.css');

		$this->AddJsFile('jquery-ui-1.8.15.custom.min.js');

		$this->AddJsFile('gallery.js');
		$this->AddJsFile('loader.js');
		$this->AddCssFile('gallery.css');
		$this->AddCssFile('styles.css');
		$this->AddJsFile('jquery.event.drag.js');

        //$GalleryHeadModule->GetData();

		$this->AddModule('ProjectBoxModule');
		$this->AddModule('GalleryHeadModule');
		$this->AddModule('GallerySideModule');

		if (C('Galleries.ShowFireEvents'))
			$this->DisplayFireEvent('AfterItemPrepare');

	}

	public function Index() {

		if (C('Galleries.ShowFireEvents'))
			$this->DisplayFireEvent('BeforeBrowseRender');
		$this->FireEvent('BeforeBrowseRender');

		// Prepare page
		$this->PrepareController();
		// Get Request Arguments
		$View = ArrayValue('0', $this->RequestArgs, 'home');
		// define custom paths
		$Path = PATH_APPLICATIONS.DS.'galleries'.DS.'customfiles'.DS;

		/*
		 * Now check the requests for validity and set data
		 */
        if ($View != 'home') {
            // check if there's a file for the view
            if (file_exists($Path.$View.'.php')) {

                    // if it is, set the global.
                    GalleryController::$Class = $View;
					$this->Title(T(GalleryController::$Class));

				// no file, so default to not found
				} else {
					$this->NotFound();
				}

            // check other views besides gallery classes
		} else {

			GalleryController::$Class = 'default';
            $View = 'home';
			GalleryController::$Category = 'home';
			$this->Title(T('home'));
        }
		$this->Head->Title($this->Head->Title());


		$this->Categories = $this->GetCategories(GalleryController::$Class);
		$ShortCat = substr(GalleryController::$Category, 0, 3);
		$CapsCat = strtoupper($ShortCat);
	 if (C('Galleries.ShowFireEvents'))
		$this->DisplayFireEvent('AfterBrowseRender');

	 $this->FireEvent('AfterBrowseRender');
	 $this->View = $Path.$View.'.php';
     $this->Render();

	}

	public function GetCategories($GetClass) {
		$Model = $this->GalleryCategoryModel;
		$Result = $Model->GetCategories(GalleryController::$Class);
		return $Result;
    }

	public function GetClasses() {
		$Model = $this->GalleryClassModel;
		$Result = $Model->GetClasses();
		return $Result;
	}



}