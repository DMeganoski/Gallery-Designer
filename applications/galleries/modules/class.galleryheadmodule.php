<?php if (!defined('APPLICATION')) exit();

class GalleryHeadModule extends Gdn_Module {

	public function __construct(&$Sender = '') {
		parent::__construct($Sender);
		if ($Sender->Head)
		$Sender->AddJsFile('/applications/galleries/js/headmodule.js');
	}


	public function GetClasses() {
		$GalleryClassModel = new GalleryClassModel();
		$Classes = $GalleryClassModel->GetClasses();
		foreach ($Classes as $Class) {
			$Categories = $this->GetCategories($Class);
			$Class->Categories = $Categories;
		}
		return $Classes;
	}

	public function VerifyCategory($VerifyCategory, $Class) {
        $Categories = $this->GetCategories($Class);
        foreach ($Categories as $Category) {
            $Label = $Category->CategoryLabel;
            if ($Label == $VerifyCategory) {
                $Return = TRUE;
            }
        }
        if ($Return) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

   public function GetCategoryKey($GetClass, $CategoryCaps) {
		$Model = new GalleryCategoryModel();
		$Key = $Model->GetCategoryKey($GetClass, $CategoryCaps);
   }

   public function GetCount($Wheres = '') {
		$Model = new GalleryItemModel();
		$Count = $Model->GetCount($Wheres);
		return $Count;

   }

   public function GetDirectory($GetClass) {
		$Model = new GalleryItemModel();
		$Files = $Model->GetDirectory($GetClass);
		return $Files;
   }

   public function GetCategories($Class) {
		$GalleriesModel = new GalleryCategoryModel();
			$Categories = $GalleriesModel->GetCategories($Class);
		return $Categories;
   }

   public function AssetTarget() {
		return 'Content';
   }

   public function ToString() {
	   $this->Classes = $this->GetClasses();
		$String = '';
		$Session = Gdn::Session();
		$permissions=$Session->User->Permissions;
		$admin=preg_match('/Garden.Settings.Manage/',$permissions);

		ob_start();
		//$Limit = Gdn::Config('UserList.Limit');
		//$Photo = Gdn::Config('UserList.Photo');
		//$Title = Gdn::Config('UserList.Title');
		//$ShowNumUsers = Gdn::Config('UserList.ShowNumUsers');

		//if(empty($Title)) {
      	//$Title="Members";
		//}

		//if($Photo) {

		include_once(PATH_APPLICATIONS.DS.'galleries/views/modules/galleryhead.php');

		$String = ob_get_contents();
		@ob_end_clean();
		return $String;
	}
}
