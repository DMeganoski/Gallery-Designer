<?php if (!defined('APPLICATION'))
	exit();

class SettingsController extends Gdn_Controller {
   /**
    * Models to include.
    *
    * @since 2.0.0
    * @access public
    * @var array
    */
   public $Uses = array('Database', 'Form', 'GalleryCategoryModel');

   public function PrepareController() {
	   $this->AddCssFile('gallery.css');
	   $this->AddJsFile('gallery.js');
   }

	/**
    * Advanced settings.
    *
    * Allows setting configuration values via form elements.
    *
    * @since 2.0.0
    * @access public
    */
	public function Advanced() {
	   // Check permission
      $this->Permission('Gallery.Items.Manage');

		// Load up config options we'll be setting
		$Validation = new Gdn_Validation();
      $ConfigurationModel = new Gdn_ConfigurationModel($Validation);
      $ConfigurationModel->SetField(array(
         'Galleries.Items.PerPage',
		  'Galleries.FireEvents.Show'
      ));

      // Set the model on the form.
      $this->Form->SetModel($ConfigurationModel);

      // If seeing the form for the first time...
      if ($this->Form->AuthenticatedPostBack() === FALSE) {
         // Apply the config settings to the form.
         $this->Form->SetData($ConfigurationModel->Data);
		} else {
         // Define some validation rules for the fields being saved
         $ConfigurationModel->Validation->ApplyRule('Galleries.Items.PerPage', 'Required');
         $ConfigurationModel->Validation->ApplyRule('Galleries.Items.PerPage', 'Integer');
         $ConfigurationModel->Validation->ApplyRule('Vanilla.Comments.AutoRefresh', 'Integer');
         $ConfigurationModel->Validation->ApplyRule('Vanilla.Archive.Date', 'Date');

			// Grab old config values to check for an update.
			$ArchiveDateBak = Gdn::Config('Vanilla.Archive.Date');
			$ArchiveExcludeBak = (bool)Gdn::Config('Vanilla.Archive.Exclude');

			// Save new settings
			$Saved = $this->Form->Save();
			if($Saved) {
				$ArchiveDate = Gdn::Config('Vanilla.Archive.Date');
				$ArchiveExclude = (bool)Gdn::Config('Vanilla.Archive.Exclude');

				if($ArchiveExclude != $ArchiveExcludeBak || ($ArchiveExclude && $ArchiveDate != $ArchiveDateBak)) {
					$DiscussionModel = new DiscussionModel();
					$DiscussionModel->UpdateDiscussionCount('All');
				}
            $this->InformMessage(T("Your changes have been saved."));
			}
		}

      $this->AddSideMenu('galleries/settings/advanced');
      $this->AddJsFile('settings.js');
      $this->Title(T('Advanced Gallery Settings'));

		// Render default view (settings/advanced.php)
		$this->Render();
	}

   /**
    * Alias for ManageCategories method.
    *
    * @since 2.0.0
    * @access public
    */
   public function Index() {
      $this->View = 'managecategories';
      $this->ManageCategories();
   }

   /**
    * Switch MasterView. Include JS, CSS used by all methods.
    *
    * Always called by dispatcher before controller's requested method.
    *
    * @since 2.0.0
    * @access public
    */
   public function Initialize() {
      // Set up head
      $this->Head = new HeadModule($this);
      $this->AddJsFile('jquery.js');
      $this->AddJsFile('jquery.livequery.js');
      $this->AddJsFile('jquery.form.js');
      $this->AddJsFile('jquery.popup.js');
      $this->AddJsFile('jquery.gardenhandleajaxform.js');
      $this->AddJsFile('global.js');

      if (in_array($this->ControllerName, array('profilecontroller', 'activitycontroller'))) {
         $this->AddCssFile('style.css');
      } else {
         $this->AddCssFile('admin.css');
      }

      // Change master template
      $this->MasterView = 'admin';
      parent::Initialize();
   }

   /**
    * Configures navigation sidebar in Dashboard.
    *
    * @since 2.0.0
    * @access public
    *
    * @param $CurrentUrl Path to current location in dashboard.
    */
   public function AddSideMenu($CurrentUrl) {
      // Only add to the assets if this is not a view-only request
      if ($this->_DeliveryType == DELIVERY_TYPE_ALL) {
         $SideMenu = new SideMenuModule($this);
         $SideMenu->HtmlId = '';
         $SideMenu->HighlightRoute($CurrentUrl);
			$SideMenu->Sort = C('Garden.DashboardMenu.Sort');
         $this->EventArguments['SideMenu'] = &$SideMenu;
         $this->FireEvent('GetAppSettingsMenuItems');
         $this->AddModule($SideMenu, 'Panel');
      }
   }

   /**
    * Display flood control options.
    *
    * @since 2.0.0
    * @access public
    */
   public function FloodControl() {
      // Check permission
      $this->Permission('Vanilla.Spam.Manage');

      // Display options
      $this->Title(T('Spam'));
      $this->AddSideMenu('vanilla/settings/floodcontrol');

      // Load up config options we'll be setting
      $Validation = new Gdn_Validation();
      $ConfigurationModel = new Gdn_ConfigurationModel($Validation);
      $ConfigurationModel->SetField(array(
         'Vanilla.Discussion.SpamCount',
         'Vanilla.Discussion.SpamTime',
         'Vanilla.Discussion.SpamLock',
         'Vanilla.Comment.SpamCount',
         'Vanilla.Comment.SpamTime',
         'Vanilla.Comment.SpamLock',
         'Vanilla.Comment.MaxLength'
      ));

      // Set the model on the form.
      $this->Form->SetModel($ConfigurationModel);

      // If seeing the form for the first time...
      if ($this->Form->AuthenticatedPostBack() === FALSE) {
         // Apply the config settings to the form.
         $this->Form->SetData($ConfigurationModel->Data);
      } else {
         // Define some validation rules for the fields being saved
         $ConfigurationModel->Validation->ApplyRule('Vanilla.Discussion.SpamCount', 'Required');
         $ConfigurationModel->Validation->ApplyRule('Vanilla.Discussion.SpamCount', 'Integer');
         $ConfigurationModel->Validation->ApplyRule('Vanilla.Discussion.SpamTime', 'Required');
         $ConfigurationModel->Validation->ApplyRule('Vanilla.Discussion.SpamTime', 'Integer');
         $ConfigurationModel->Validation->ApplyRule('Vanilla.Discussion.SpamLock', 'Required');
         $ConfigurationModel->Validation->ApplyRule('Vanilla.Discussion.SpamLock', 'Integer');
         $ConfigurationModel->Validation->ApplyRule('Vanilla.Comment.SpamCount', 'Required');
         $ConfigurationModel->Validation->ApplyRule('Vanilla.Comment.SpamCount', 'Integer');
         $ConfigurationModel->Validation->ApplyRule('Vanilla.Comment.SpamTime', 'Required');
         $ConfigurationModel->Validation->ApplyRule('Vanilla.Comment.SpamTime', 'Integer');
         $ConfigurationModel->Validation->ApplyRule('Vanilla.Comment.SpamLock', 'Required');
         $ConfigurationModel->Validation->ApplyRule('Vanilla.Comment.SpamLock', 'Integer');
         $ConfigurationModel->Validation->ApplyRule('Vanilla.Comment.MaxLength', 'Required');
         $ConfigurationModel->Validation->ApplyRule('Vanilla.Comment.MaxLength', 'Integer');

         if ($this->Form->Save() !== FALSE) {
            $this->InformMessage(T("Your changes have been saved."));
         }
      }

      // Render default view
      $this->Render();
   }

   /**
    * Adding a new category.
    *
    * @since 2.0.0
    * @access public
    */
   public function AddCategory() {
      // Check permission
      $this->Permission('Galleries.Items.Manage');

      // Set up head
      $this->AddJsFile('jquery.alphanumeric.js');
      $this->AddJsFile('categories.js');
      $this->AddJsFile('jquery.gardencheckboxgrid.js');
      $this->Title(T('Add Category'));
      $this->AddSideMenu('vanilla/settings/managecategories');

      // Prep models
      $RoleModel = new RoleModel();
      $PermissionModel = Gdn::PermissionModel();
      $this->Form->SetModel($this->CategoryModel);

      // Load all roles with editable permissions
      $this->RoleArray = $RoleModel->GetArray();

      if ($this->Form->AuthenticatedPostBack() == FALSE) {
			$this->Form->AddHidden('CodeIsDefined', '0');
      } else {
			// Form was validly submitted
			$IsParent = $this->Form->GetFormValue('IsParent', '0');
			$this->Form->SetFormValue('AllowDiscussions', $IsParent == '1' ? '0' : '1');
         $CategoryID = $this->Form->Save();
         if ($CategoryID) {
				Redirect('vanilla/settings/managecategories');
         } else {
				unset($CategoryID);
			}
      }
		// Get all of the currently selected role/permission combinations for this junction.
		$Permissions = $PermissionModel->GetJunctionPermissions(array('JunctionID' => isset($CategoryID) ? $CategoryID : 0), 'Category');
		$Permissions = $PermissionModel->UnpivotPermissions($Permissions, TRUE);

      $this->SetData('PermissionData', $Permissions, TRUE);

      // Render default view
      $this->Render();
   }

   /**
    * Deleting a category.
    *
    * @since 2.0.0
    * @access public
    *
    * @param int $CategoryID Unique ID of the category to be deleted.
    */
   public function DeleteCategory($CategoryID = FALSE) {
      // Check permission
      $this->Permission('Vanilla.Categories.Manage');

      // Set up head
      $this->AddJsFile('categories.js');
      $this->Title(T('Delete Category'));
      $this->AddSideMenu('vanilla/settings/managecategories');

      // Get category data
      $this->Category = $this->CategoryModel->GetID($CategoryID);


      if (!$this->Category) {
         $this->Form->AddError('The specified category could not be found.');
      } else {
         // Make sure the form knows which item we are deleting.
         $this->Form->AddHidden('CategoryID', $CategoryID);

         // Get a list of categories other than this one that can act as a replacement
         $this->OtherCategories = $this->CategoryModel->GetWhere(
            array(
               'CategoryID <>' => $CategoryID,
               'AllowDiscussions' => $this->Category->AllowDiscussions, // Don't allow a category with discussion to be the replacement for one without discussions (or vice versa)
					'CategoryID >' => 0
            ),
            'Sort'
         );

         if (!$this->Form->AuthenticatedPostBack()) {
            $this->Form->SetFormValue('DeleteDiscussions', '1'); // Checked by default
         } else {
            $ReplacementCategoryID = $this->Form->GetValue('ReplacementCategoryID');
            $ReplacementCategory = $this->CategoryModel->GetID($ReplacementCategoryID);
            // Error if:
            // 1. The category being deleted is the last remaining category that
            // allows discussions.
            if ($this->Category->AllowDiscussions == '1'
               && $this->OtherCategories->NumRows() == 0)
               $this->Form->AddError('You cannot remove the only remaining category that allows discussions');

            /*
            // 2. The category being deleted allows discussions, and it contains
            // discussions, and there is no replacement category specified.
            if ($this->Form->ErrorCount() == 0
               && $this->Category->AllowDiscussions == '1'
               && $this->Category->CountDiscussions > 0
               && ($ReplacementCategory == FALSE || $ReplacementCategory->AllowDiscussions != '1'))
               $this->Form->AddError('You must select a replacement category in order to remove this category.');
            */

            // 3. The category being deleted does not allow discussions, and it
            // does contain other categories, and there are replacement parent
            // categories available, and one is not selected.
				/*
            if ($this->Category->AllowDiscussions == '0'
               && $this->OtherCategories->NumRows() > 0
               && !$ReplacementCategory) {
               if ($this->CategoryModel->GetWhere(array('ParentCategoryID' => $CategoryID))->NumRows() > 0)
                  $this->Form->AddError('You must select a replacement category in order to remove this category.');
            }
				*/

            if ($this->Form->ErrorCount() == 0) {
               // Go ahead and delete the category
               try {
                  $this->CategoryModel->Delete($this->Category, $this->Form->GetValue('ReplacementCategoryID'));
               } catch (Exception $ex) {
                  $this->Form->AddError($ex->getMessage());
               }
               if ($this->Form->ErrorCount() == 0) {
                  $this->RedirectUrl = Url('vanilla/settings/managecategories');
                  $this->InformMessage(T('Deleting category...'));
               }
            }
         }
      }

      // Render default view
      $this->Render();
   }

   /**
    * Editing a category.
    *
    * @since 2.0.0
    * @access public
    *
    * @param int $CategoryID Unique ID of the category to be updated.
    */
   public function EditCategory() {
      // Check permission
      $this->Permission('Galleries.Items.Manage');
	  $ClassLabel = ArrayValue('0', $this->RequestArgs, '');
       $CategoryLabel = ArrayValue('1', $this->RequestArgs, '');
      // Set up models
      $this->Form->SetModel($this->GalleryCategoryModel);

      // Get category data
		$AllClassCats = $this->GalleryCategoryModel->GetCategories($ClassLabel);
		foreach($AllClassCats as $Category) {
			if ($Category->CategoryLabel == $CategoryLabel) {
				$this->Category = $Category;
			}
		}
      // Set up head
      $this->AddJsFile('jquery.alphanumeric.js');
      $this->AddJsFile('categories.js');
      $this->AddJsFile('jquery.gardencheckboxgrid.js');
      $this->Title(T('Edit Category'));

      $this->AddSideMenu('galleries/settings/managecategories');

      // Make sure the form knows which item we are editing.
      $this->Form->AddHidden('CategoryKey', $CategoryKey);

      if ($this->Form->AuthenticatedPostBack() === FALSE) {
         $this->Form->SetData($this->Category);
      } else {
         if ($this->Form->Save())
				Redirect('galleries/settings/managecategories');
	  }
      // Render default view
      $this->Render();
   }

   /**
    * Enabling and disabling categories from list.
    *
    * @since 2.0.0
    * @access public
    */
	public function ManageCategories() {
		// Check permission
		$this->Permission('Gallery.Items.Manage');
		$this->PrepareController();

		// Set up head
		$this->AddSideMenu('galleries/settings/managecategories');
		$this->AddJsFile('categories.js');
//       $this->AddJsFile('jquery.ui.packed.js');
		$this->AddJsFile('js/library/jquery.alphanumeric.js');
		$this->AddJsFile('js/library/nestedSortable.1.2.1/jquery-ui-1.8.2.custom.min.js');
		$this->AddJsFile('js/library/nestedSortable.1.2.1/jquery.ui.nestedSortable.js');
		$this->Title(T('Gallery Categories'));

		// Get Class Data
		$Classes = $this->GalleryClassModel->GetClasses();
		// Get category data
		$ClassNum = 0;
		foreach ($Classes as $Class) {
			$ClassLabel = $Class->ClassLabel;
			$ClassKey = $Class->ClassKey;
			$Categories = $this->GalleryClassModel->GetCategories($ClassLabel);
			$CategoryData[$ClassKey] = array('ClassLabel' => $ClassLabel, 'Visible' => $Class->Visible, 'Categories' => array());
			foreach ($Categories as $Category) {
				$CategoryLabel = $Category->CategoryLabel;
				$CategoryKey = $Category->CategoryKey;
				$CategoryData[$ClassKey]['Categories'][$CategoryKey] = $CategoryLabel;
			}

		}
		$this->CategoryData = $CategoryData;
		/* Enable/Disable Categories
		if (Gdn::Session()->ValidateTransientKey(GetValue(1, $this->RequestArgs))) {
			$Toggle = GetValue(0, $this->RequestArgs, '');
			if ($Toggle == 'enable') {
				SaveToConfig('Vanilla.Categories.Use', TRUE);
			} else if ($Toggle == 'disable') {
				SaveToConfig('Vanilla.Categories.Use', FALSE);
			}
				Redirect('vanilla/settings/managecategories');
		}


		// Setup & save forms
		$Validation = new Gdn_Validation();
		$ConfigurationModel = new Gdn_ConfigurationModel($Validation);
		$ConfigurationModel->SetField(array(
			'Vanilla.Categories.MaxDisplayDepth',
			'Vanilla.Categories.DoHeadings',
			'Vanilla.Categories.HideModule'
		));
	   *
	   */

      // Set the model on the form.
      $this->Form->SetModel($this->GalleriesCategoryModel);

      // If seeing the form for the first time...
      if ($this->Form->AuthenticatedPostBack() === FALSE) {
         // Apply the config settings to the form.
      } else {
         if ($this->Form->Save() !== FALSE)
            $this->InformMessage(T("Your settings have been saved."));
		}

      // Render default view
      $this->Render();
   }

   /**
    * Sorting display order of categories.
    *
    * Accessed by ajax so its default is to only output true/false.
    *
    * @since 2.0.0
    * @access public
    */
   public function SortCategories() {
      // Check permission
      $this->Permission('Vanilla.Categories.Manage');

      // Set delivery type to true/false
      $this->_DeliveryType = DELIVERY_TYPE_BOOL;
		$TransientKey = GetIncomingValue('TransientKey');
      if (Gdn::Session()->ValidateTransientKey($TransientKey)) {
			$TreeArray = GetValue('TreeArray', $_POST);
			$this->CategoryModel->SaveTree($TreeArray);
		}

      // Renders true/false rather than template
      $this->Render();
   }

   /**
    * Enable/Disable Flagging.
    */
   public function FireEventToggle($Sender) {

		// Enable/Disable Content Flagging
		if (Gdn::Session()->ValidateTransientKey(GetValue(1, $Sender->RequestArgs))) {
			if (C('Plugins.Flagging.Enabled')) {
				$this->_Disable();
			} else {
				$this->_Enable();
			}
			Redirect('plugin/flagging');
		}
   }

}