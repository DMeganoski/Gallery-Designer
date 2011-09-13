<?php if (!defined('APPLICATION')) exit();

class SectionController extends CandyController {
	
	public $Uses = array('Form', 'SectionModel');
	protected $AdminView = True;
	
	public function Initialize() {
		parent::Initialize();
		if ($this->_DeliveryType == DELIVERY_TYPE_ALL) {
			$this->AddJsFile('jquery.menu.js');
			$this->AddJsFile('jquery.popup.js');
			$this->AddJsFile('jquery.form.js');
			$this->AddSideMenu();
		}
	}
	
	public function Index() {
		$this->Tree();
	}
	
	public function Tree() {
		$this->Permission('Candy.Settings.View');
		$TreeModel = new SectionModel();
		$this->AddJsFile('tree.js');
		$this->Tree = $TreeModel->Full('*')->Result(); // array('Depth <=' => 1)
		$this->View = 'Tree';
		$this->Title(T('Sections'));
		$this->Render();
	}
	
	public function Add($ParentID) {
		$this->View = 'Edit';
		$this->Edit(0, $ParentID);
	}
	
	public function Edit($Reference = 0, $ParentID = 1) {
		$Session = Gdn::Session();
		$Model = new SectionModel();
		$this->Form->SetModel($Model);
		$this->Form->AddHidden('ParentID', $ParentID);
		$Section = False;
		if ($Reference) {
			$Section = $Model->GetID($Reference);
			if (!IsContentOwner($Section, 'Candy.Sections.Edit')) $Section = False;
			if ($Section) {
				$this->Form->AddHidden('SectionID', $Section->SectionID);
				$this->Form->SetData($Section);
			}
		}
		if (!$Section) $this->Permission('Candy.Sections.Add');
		
		if ($this->Form->AuthenticatedPostBack()) {
			$this->Form->Save($Section);
			if ($this->Form->ErrorCount() == 0) {
				$this->InformMessage(T('Saved'), array('Sprite' => 'Check', 'CssClass' => 'Dismissable AutoDismiss'));
			}
		}

		$this->Title(ConcatSep(' - ', T('Section'), GetValue('Name', $Section)));
		$this->Render();
		
	}
	
	public function Delete($SectionID) {
		$this->Permission('Candy.Sections.Delete');
		$Row = $this->SectionModel->GetID($SectionID);
		$this->SectionModel->Delete($Row->SectionID);
		if ($this->_DeliveryType == DELIVERY_TYPE_ALL) {
			Redirect('candy/section/tree');
		}
	}

	public function Check() {
		$this->Permission('Candy.Settings.View');
		$this->CorruptedData = $this->SectionModel->GetCorruptedRows();
		$this->Title(T('Corrupted nodes'));
		$this->Render();
	}
	
	public function Move($SectionID) {
		$this->Permission('Candy.Sections.Move');
		$ContentModel =& $this->SectionModel;
		$this->Content = $ContentModel->GetID($SectionID);
		$EditingID = $this->Content->SectionID;
		if ($this->Form->AuthenticatedPostBack() != False) {
			$SecondID = $this->Form->GetFormValue('SecondID'); // NodeID2
			$ContentModel->MoveAll($EditingID, $SecondID);
		} else {
			$this->Form->SetFormValue('SecondID', $this->Content->SectionID);
		}
		
		$FullTree = $ContentModel->Full('', array('Depth >' => 0));
		$this->FullTreeOptions = $ContentModel->DropDownArray('Name', $FullTree);

		$this->Title($this->Content->Name);
		$this->Render();
	}
	
	protected $Branch;
	
	public function Swap($SectionID) {
		$this->Permission('Candy.Sections.Swap');
		$ContentModel =& $this->SectionModel;
		$this->Content = $ContentModel->GetID($SectionID);
		$EditingID = $this->Content->SectionID;
		if ($this->Form->AuthenticatedPostBack() != False) {
			$Position = $this->Form->GetFormValue('Position');
			try {
				if ($Position) {
					$ContentModel->ChangePositionAll($EditingID, $this->Form->GetFormValue('AllSecondID'), $Position);
				} else {
					$ContentModel->ChangePosition($EditingID, $this->Form->GetFormValue('TreeSecondID'));
				}
			} catch (Exception $Ex) {
				$this->Form->AddError($Ex, 'Position');
			}
		} else {
			$this->Form->SetFormValue('AllSecondID', $this->Content->SectionID);
			$this->Form->SetFormValue('TreeSecondID', $this->Content->SectionID);
		}

		$this->Parents = $ContentModel->Parents($EditingID, 'a.Name, a.SectionID', array('a.Depth' => $this->Content->Depth - 1));
		$Parent = $this->Parents->FirstRow();
		if ($Parent != False) {
			$Where = array('a.Depth' => $this->Content->Depth, 'a.SectionID <>' => $EditingID);
			$this->Branch = $ContentModel->Branch($Parent->SectionID, 'a.Name, a.SectionID', $Where);
		}
		
		$FullTree = $ContentModel->Full('', array('Depth >' => 0));
		$this->FullTreeOptions = $ContentModel->DropDownArray('Name', $FullTree);
		
		$this->Title($this->Content->Name);
		$this->Render();
	}
	
}






