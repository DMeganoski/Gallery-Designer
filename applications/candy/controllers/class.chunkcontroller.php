<?php if (!defined('APPLICATION')) exit();

class ChunkController extends CandyController {
	
	public $Uses = array('Form', 'ChunkModel');
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
		$this->Chunks = $this->ChunkModel->Get('', $Offset, $Limit, 'DateUpdated');
		$this->RecordCount = $this->ChunkModel->GetCount();
		$this->Url = '/candy/chunk/browse/%s';
		$this->Pager = new PagerModule($this);
		$this->Pager->Configure($Offset, $Limit, $this->RecordCount, $this->Url);
		$this->Title(T('Chunks'));
		$this->Render();
	}
	
	public function Update($Reference = '', $PostBackKey = '') {
		$this->Permission('Candy.Chunks.Edit');
		
		$Content = False;		
		$Session = Gdn::Session();
		
		$this->AddJsFile('jquery.textpandable.js');
		$this->AddJsFile('editform.js');
		$this->Form->SetModel($this->ChunkModel);
		
		if ($Reference != '') {
			$Content = $this->ChunkModel->GetID($Reference);
			if ($Content) {
				$this->Form->AddHidden('ChunkID', $Content->ChunkID);
				$this->Editing = True;
				$this->Form->SetData($Content);
			}
		}
		
		$IsFormPostBack = $this->Form->AuthenticatedPostBack();
		$PostAuthenticatedByKey = ($Session->ValidateTransientKey($PostBackKey) && $this->Form->IsPostBack());
		
		if ($IsFormPostBack || $PostAuthenticatedByKey) {
			if ($PostAuthenticatedByKey) {
				// AJAX, set form values.
				$this->Form->SetFormValue('ChunkID', $Content->ChunkID);
				$this->Form->SetFormValue('Body', GetPostValue('Body'));
			}
			$SavedID = $this->Form->Save($Content);
			if ($SavedID) {
				$Message = T('Saved');
				$this->InformMessage($Message, array('Sprite' => 'Check', 'CssClass' => 'Dismissable AutoDismiss'));
				if ($this->DeliveryType() == DELIVERY_TYPE_BOOL) {
					//$this->SetData('Content', $Content);
					//$this->SetData('NewBody', Gdn_Format::To($this->Form->GetFormValue('Body'), $Content->Format));
					$this->SetJson('NewBody', Gdn_Format::To($this->Form->GetFormValue('Body'), $Content->Format));
				}
			}
		} else {
			$this->SetData('Content', $Content);
			$this->Form->SetData($Content);
		}
		
		$this->Title(ConcatSep(' - ', T('Chunk'), GetValue('Name', $Content)));
		$this->Render();
	}
	
	public function Delete($Reference) {
		$this->Permission('Candy.Chunks.Delete');
		if ($this->DeliveryType() == DELIVERY_TYPE_ALL) {
			Redirect('/candy/page/browse');
		}
	}
	
}



