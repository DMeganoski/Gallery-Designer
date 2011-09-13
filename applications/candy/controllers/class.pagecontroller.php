<?php if (!defined('APPLICATION')) exit();

class PageController extends CandyController {
	
	public $Uses = array('Form', 'PageModel');
	public $Editing;
	protected $AdminView = True;
	
	public function Initialize() {
		parent::Initialize();
		if ($this->DeliveryType() == DELIVERY_TYPE_ALL) {
			$this->AddSideMenu();
			//$this->AddCssFile('candy.css');
		}
	}
	
	public function Browse($Page = '') {
		$this->Permission('Candy.Settings.View');
		list($Offset, $Limit) = OffsetLimit($Page, 30);
		$this->Pages = $this->PageModel->Get('', $Offset, $Limit);
		$this->RecordCount = $this->PageModel->GetCount();
		$this->Url = '/candy/page/browse/%s';
		$this->Pager = new PagerModule($this);
		$this->Pager->Configure($Offset, $Limit, $this->RecordCount, $this->Url);
		$this->Title(T('Pages'));
		$this->Render();
	}
	
	public function Visible($PageID) {
		$Page = $this->PageModel->GetID($PageID);
		if ($this->Form->IsPostBack()) {
			$Visible = ForceBool($Page->Visible, 0, 0, 1); // Invert Visible property.
			$this->PageModel->SetProperty($Page->PageID, 'Visible', $Visible);
			$Page = $this->PageModel->GetID($PageID); // Get just updated content.
			if ($this->DeliveryType() == DELIVERY_TYPE_ALL) {
				$Target = GetIncomingValue('Target', '/candy/page/browse');
				Redirect($Target);
			}
			$this->SetData('Content', $Page);
			$PageInfoModule = new PageInfoModule($this);
			$this->JsonTarget('#PageInfoModule', $PageInfoModule->ToString(), 'Html');
		} else {
			$this->Form->SetData($Page);
		}
		$this->Render();
	}
	
	public function AddNew() {
		$this->View = 'Edit';
		$this->Edit();
	}
	
	public function Edit($Reference = '') {
		
		//$this->AddJsFile('jquery.autocomplete.pack.js');
		$this->AddJsFile('jquery.textpandable.js');
		$this->AddJsFile('editform.js');
		$this->Form->SetModel($this->PageModel);
		
		$SectionModel = new SectionModel();
		$this->Tree = $SectionModel->DropDownArray('Name', $SectionModel->Full('', array('Depth >' => 0)));
		
		$Content = False;
		if ($Reference != '') {
			$Content = $this->PageModel->GetID($Reference);
			if (!IsContentOwner($Content, 'Candy.Pages.Edit')) $Content = False;
			if ($Content) {
				$this->Form->AddHidden('PageID', $Content->PageID);
				$this->Form->SetData($Content);
				$this->Editing = True;
			}
		}
		if (!$Content) $this->Permission('Candy.Pages.Add');
		
		if ($this->Form->AuthenticatedPostBack()) {
			if ($this->Form->ButtonExists('Delete')) {
				$this->PageModel->Delete($Content->PageID);
				$this->InformMessage(T('Page deleted'), array('Sprite' => 'SkullBones', 'CssClass' => 'Dismissable AutoDismiss'));
			} else {
				$SavedID = $this->Form->Save($Content);
				if ($SavedID) {
					$Message = LocalizedMessage('Saved. You can check it here: %s', Anchor($this->Form->GetFormValue('Title'), 'content/page/'.$SavedID));
					$this->InformMessage($Message, array('Sprite' => 'Check', 'CssClass' => 'Dismissable'));
				}
			}
		} else {
			$URI = trim(GetIncomingValue('URI'), '/');
			$this->Form->SetValue('URI', $URI);
		}
		$this->SetData('Content', $Content, True);
		$this->Title(ConcatSep(' - ', T('Page'), GetValue('Title', $Content)));
		$this->Render();
	}
	
	public function Delete($Reference) {
		$this->Permission('Candy.Pages.Delete');
		$this->PageModel->Delete($Reference);
		if ($this->DeliveryType() == DELIVERY_TYPE_ALL) {
			Redirect('/candy/page/browse');
		}
	}
	
}



