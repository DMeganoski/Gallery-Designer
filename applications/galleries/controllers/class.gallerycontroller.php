<?php if (!defined('APPLICATION')) exit();

class GalleryController extends GalleriesController {

    public $Uses = array('Form', 'GalleryCategoryModel', 'GalleryClassModel', 'GalleryItemModel');

    public static $SelectedFile = array();
    public static $PublicDir = '/uploads/item/';
    public static $Limit = 16;
    public static $Page = 1;
    public static $Class = 'default';
    public static $Category = 'home';

    public $Categories;

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
		$this->AddJsFile('/applications/projects/js/projectbox.js');
		$this->AddCssFile('/applications/projects/design/projectbox.css');
      }

      $this->MasterView = 'default';
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
		$this->AddCssFile('gallery.css');
		$this->AddCssFile('gallerycustom.css');
		$this->AddJsFile('jquery.qtip.js');
		$this->AddCssFile('jquery.qtip.css');
		$this->AddJsFile('gallery.js');
		$this->AddJsFile('gallerycustom.js');

		$this->AddJsFile('jquery.ui.packed.js');

        //$GalleryHeadModule->GetData();
		$this->AddModule('ProjectBoxModule');
		$this->AddModule('GalleryHeadModule');
		$this->AddModule('GallerySideModule');

		$this->AddCssFile('fileupload.css');
		$this->AddJsFile('fileupload.js');

		if (C('Galleries.ShowFireEvents'))
			$this->DisplayFireEvent('AfterGalleryPrepare');

		$this->FireEvent('AfterGalleryPrepare');
   }

   public function Index($Args) {
	   if (C('Galleries.ShowFireEvents'))
		$this->DisplayFireEvent('BeforeBrowseRender');
	   $this->FireEvent('BeforeBrowseRender');
	   $this->PrepareController();
       // Get Request Arguments
        $View = ArrayValue('0', $this->RequestArgs, 'default');
        $Category = ArrayValue('1', $this->RequestArgs, 'home');
        $Page= ArrayValue('2', $this->RequestArgs, '1');
        // Set the defaults
        // use the next line for items, maybe?
        if (!is_numeric($Page) || $Page < 1)
        $Page = 1;
        self::$Page = $Page;
        $Limit = C('Gallery.Items.PerPage', 16);
        self::$Limit = $Limit;
	$Path = PATH_APPLICATIONS . DS . 'galleries' . DS . 'customfiles'.DS.'customclasses';

	$GalleryItemModel = $this->GalleryItemModel;
        //$CountItems = 100;


		/*
		 * Ok, now check the requests for validity and set data
		 */
		$ClassData = $this->GalleryClassModel->GetClasses($View);
        if ($View != 'default') {
            // check if there's a file for the view
            if (file_exists($Path.DS.$View.DS.$View.'home.php')) {
                // check if the view is a gallery class
                $VerifyClass = $this->GalleryClassModel->VerifyClass($View);
                if ($VerifyClass == TRUE) {
                    // if it is, set the global.
                    self::$Class = $View;
					$this->Title(T(self::$Class));
                    // now the category...
                    if ($Category != 'home') {
                        $VerifyCategory = $this->GalleryCategoryModel->VerifyCategory($Category,$View);
                        if ($VerifyCategory) {
							self::$Category = $Category;
						}
                     } else {
                            self::$Category = 'home';
                     }
                    } else {
                        self::$Class = 'default';
			$this->Title(T(self::$Class));
                    }
			}
                // check other views besides gallery classes
                } else if ($View == 'default') {
                    self::$Class = 'default';

                    if ($Category != 'home') {
                        //$VerifyCategory = $GalleriesModel->VerifyCategory($Category,$Class);
                        //if ($VerifyCategory) {
                       self::$Category = $Category;
			$this->Title(T($Category));
                     } else {
                            self::$Category = 'home';
			    $this->Title(T('home'));
                     }
                } else if ($View == 'item') {
                    self::$Class = 'item';
		    self::$Category = 'home';
		    $this->Title(T(''));
		} else {
                   self::$Class = 'default';
                   $View = 'default';
		   self::$Category = 'home';
		   $this->Title(T('default'));
                }
	 $this->Head->Title($this->Head->Title());
	 if (self::$Category != 'home')
		$this->View = ($Path.DS.self::$Class.DS.self::$Category.'.php');
	 else
		 $this->View = ($Path.DS.self::$Class.DS.self::$Class.'home.php');

		$this->Categories = $this->GetCategories(self::$Class);
		$ShortCat = substr(self::$Category, 0, 3);
		$CapsCat = strtoupper($ShortCat);
		$this->Count = $this->GalleryItemModel->GetCount(array('CategoryCAPS' => $CapsCat));
	 if (C('Galleries.ShowFireEvents'))
		$this->DisplayFireEvent('AfterBrowseRender');

	 $this->FireEvent('AfterBrowseRender');
        $this->Render();
    }

	public function AddSideMenu($CurrentUrl = '') {

         $SideMenu = new SideMenuModule($this);
         $SideMenu->HtmlId = 'UserOptions';
			$SideMenu->AutoLinkGroups = FALSE;
         $Session = Gdn::Session();
         $ViewingUserID = $Session->UserID;
         $SideMenu->AddItem('Options', '');

         // Check that we have the necessary tools to allow image uploading
         $AllowImages = Gdn_UploadImage::CanUploadImages();

         if ($this->User->UserID != $ViewingUserID) {
            // Include user js files for people with edit users permissions
            if ($Session->CheckPermission('Garden.Users.Edit')) {
              $this->AddJsFile('jquery.gardenmorepager.js');
              $this->AddJsFile('user.js');
            }

            // Add profile options for everyone
            $SideMenu->AddLink('Options', T('Change Picture'), '/profile/picture/'.$this->User->UserID.'/'.Gdn_Format::Url($this->User->Name), 'Garden.Users.Edit', array('class' => 'PictureLink'));
            if ($this->User->Photo != '' && $AllowImages) {
               $SideMenu->AddLink('Options', T('Edit Thumbnail'), '/profile/thumbnail/'.$this->User->UserID.'/'.Gdn_Format::Url($this->User->Name), 'Garden.Users.Edit', array('class' => 'ThumbnailLink'));
               $SideMenu->AddLink('Options', T('Remove Picture'), '/profile/removepicture/'.$this->User->UserID.'/'.Gdn_Format::Url($this->User->Name).'/'.$Session->TransientKey(), 'Garden.Users.Edit', array('class' => 'RemovePictureLink'));
            }

            $SideMenu->AddLink('Options', T('Edit Account'), '/user/edit/'.$this->User->UserID, 'Garden.Users.Edit', array('class' => 'Popup EditAccountLink'));
            $SideMenu->AddLink('Options', T('Delete Account'), '/user/delete/'.$this->User->UserID, 'Garden.Users.Delete', array('class' => 'Popup DeleteAccountLink'));
            if ($this->User->Photo != '' && $AllowImages)
               $SideMenu->AddLink('Options', T('Remove Picture'), '/profile/removepicture/'.$this->User->UserID.'/'.Gdn_Format::Url($this->User->Name).'/'.$Session->TransientKey(), 'Garden.Users.Edit', array('class' => 'RemovePictureLink'));

            $SideMenu->AddLink('Options', T('Edit Preferences'), '/profile/preferences/'.$this->User->UserID.'/'.Gdn_Format::Url($this->User->Name), 'Garden.Users.Edit', array('class' => 'Popup PreferencesLink'));
         } else {
            // Add profile options for the profile owner
            if ($AllowImages)
               $SideMenu->AddLink('Options', T('Change My Picture'), '/profile/picture', FALSE, array('class' => 'PictureLink'));

            if ($this->User->Photo != '' && $AllowImages) {
               $SideMenu->AddLink('Options', T('Edit My Thumbnail'), '/profile/thumbnail', FALSE, array('class' => 'ThumbnailLink'));
               $SideMenu->AddLink('Options', T('Remove My Picture'), '/profile/removepicture/'.$Session->UserID.'/'.Gdn_Format::Url($Session->User->Name).'/'.$Session->TransientKey(), FALSE, array('class' => 'RemovePictureLink'));
            }
            // Don't allow account editing if it has been turned off.
            if (Gdn::Config('Garden.UserAccount.AllowEdit')) {
               $SideMenu->AddLink('Options', T('Edit My Account'), '/profile/edit', FALSE, array('class' => 'Popup EditAccountLink'));
               $SideMenu->AddLink('Options', T('Change My Password'), '/profile/password', FALSE, array('class' => 'Popup PasswordLink'));
            }
            if (Gdn::Config('Garden.Registration.Method') == 'Invitation')
               $SideMenu->AddLink('Options', T('My Invitations'), '/profile/invitations', FALSE, array('class' => 'Popup InvitationsLink'));

            $SideMenu->AddLink('Options', T('My Preferences'), '/profile/preferences/'.$this->User->UserID.'/'.Gdn_Format::Url($this->User->Name), FALSE, array('class' => 'Popup PreferencesLink'));
         }

         $this->EventArguments['SideMenu'] = &$SideMenu;
         $this->FireEvent('AfterAddSideMenu');
         $this->AddModule($SideMenu, 'Panel');

   }

	public function NotFound() {
      $this->View = 'notfound';
      $this->Render();
   }

	public function DisplayFireEvent($EventName) {
	   $Controller = $this->ControllerName;
	   echo '<img src="/applications/galleries/design/images/burn.png" class="FireEvent" content="'.$EventName.'"></img>';
	   echo '<div class="FireEvent">FireEvent: '.$Controller.':'.$EventName.'</div>';
   }

	/*
	 * Unused Functions from other applications and plugins
	 */
	public function Delete($ItemKey = '') {
      $this->Permission('Gallery.Items.Manage');
      $Session = Gdn::Session();
      if (!$Session->IsValid())
         $this->Form->AddError('You must be authenticated in order to use this form.');

      $Item = $this->ItemModel->GetKey($ItemKey);
      if (!$Item)
         Redirect('dashboard/home/filenotfound');

      if ($Session->UserID != $Item['InsertUserID'])
			$this->Permission('Gallery.Items.Manage');

      $Session = Gdn::Session();
      if (is_numeric($ItemKey))
         $this->ItemModel->Delete($ItemKey);

      if ($this->_DeliveryType === DELIVERY_TYPE_ALL)
         Redirect(GetIncomingValue('Target', Gdn_Url::WebRoot()));

      $this->View = 'index';
      $this->Render();
   }

   /*
    * Model Functions
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
   public function GetClassKey($GetClass) {
	 $Model = $this->GalleryClassModel;
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
}


?>
