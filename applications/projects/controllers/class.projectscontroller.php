<?php if (!defined('APPLICATION')) exit();
/**
 * Projects controller for viewing, editing, and retrieving project information.
 * @package Projects
 */

/**
 * A brief description of the controller.
 *
 * @since 0.1
 * @package Projects
 */
class ProjectsController extends Gdn_Controller {
   /**
    * Do-nothing construct to let children constructs bubble up.
    *
    * @access public
    */
   public function __construct() {
      parent::__construct();
   }

   /**
    * This is a good place to include JS, CSS, and modules used by all methods of this controller.
    *
    * Always called by dispatcher before controller's requested method.
    *
    * @since 1.0
    * @access public
    */
   public function Initialize() {
      // There are 4 delivery types used by Render().
      // DELIVERY_TYPE_ALL is the default and indicates an entire page view.
      if ($this->DeliveryType() == DELIVERY_TYPE_ALL) {
         $this->Head = new HeadModule($this);
         $this->Head-> AddTag('meta', array(
             'name' => 'description',
             'content' => "X"
         ));

      }
		$this->AddCssFile('style.css');
		$this->AddCssFile('gallery.css');
      // Call Gdn_Controller's Initialize() as well.
      parent::Initialize();
   }

   public function Index() {
		$this->PrepareController();
		$this->AddModule('GallerySideModule');
		$this->AddModule('GalleryHeadModule');
		$this->AddJsFile('/js/library/jquery.autogrow.js');
        $this->AddJsFile('forms.js');
		$Session = Gdn::Session();
		$UserID = $Session->UserID;
		$this->Form = new Gdn_Form('Project');
		$this->Form->SetModel($this->ProjectModel);
		$this->Form->AddHidden('UserID', $UserID);
		if ($this->Form->AuthenticatedPostBack() === FALSE) {

		} else {
				if ($this->Form->Save() !== FALSE) {
				$this->ProjectModel->Insert('Project', array(
					'UserID' => $UserID,
					'ProjectName' => $this->Form->GetFormValue('ProjectName'),
					'CurrentProject' => 1
				));
				$CurrentProject = $this->ProjectModel->GetNew($UserID);
				$this->ProjectModel->SetCurrent($UserID,$CurrentProject->ProjectKey);
				//$this->ProjectModel->NewProject($UserID);
                $this->StatusMessage = T("Your changes have been saved successfully.");
                //$this->RedirectUrl = Url('/item/'.$Item->Slug);
				}
		}
		$this->Projects = $this->ProjectModel->GetAllUser($Session->UserID);
		$this->Render();
	}


	/*-------------- Functions for imploding and exploding associative arrays -------------*/
	/*
	 * Takes an array implodes it with '-' and ':', and returns the new string
	 */
	function MyImplode($Array) {

		$Result = '';
		foreach ($Array as $Key => $Value) {
			if (strlen($Result) > 1)
				$Result .= '-';
				$Result .= $Key.':'.$Value;
		}
		return $Result;
		ImplodeAssoc($KeyGlue, $ElementGlue, $Array);
   }

   /*
    * Takes an imploded string containing an associative array and explodes it into an array again
    */
	public function MyExplode($Array) {
		$WierdArray = explode('-', $Array);
		foreach ($WierdArray as $Weird) {
			list ($Key, $Value) = explode(':', $Weird);
			$Return[$Key] = $Value;
		}
		return $Return;
	}

}
