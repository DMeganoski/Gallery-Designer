<?php if (!defined('APPLICATION')) exit();

class PageModel extends Gdn_Model {
	
	public $PrimaryKey = 'PageID';
	
	public function __construct() {
		parent::__construct('Page');
		$this->Validation->AddRule('UrlPath', 'function:ValidateUrlPath');
	}
	
	public function Save($PostValues, $PreviousValues = False) {
		ReplaceEmpty($PostValues, Null);
		$URI = GetValue('URI', $PostValues, Null);
		$bCreateSection = GetValue('CreateSection', $PostValues);
		$RowID = GetValue('PageID', $PostValues);
		$Insert = ($RowID === False);
		if ($bCreateSection) {
			$SectionModel = Gdn::Factory('SectionModel');
			$this->Validation->ApplyRule('URI', 'UrlPath');
			$this->Validation->ApplyRule('SectionID', 'Required');
			if ($Insert && $URI && CandyModel::GetRoute($URI)) {
				$this->Validation->AddValidationResult('URI', '%s: Already exists.');
			}
		}
		
		$this->EventArguments['PostValues'] =& $PostValues;
		$this->FireEvent('BeforeSave');
		$RowID = parent::Save($PostValues);
		if ($RowID) {
			if ($URI) CandyModel::SaveRoute($URI, 'candy/content/page/'.$RowID);
			if ($bCreateSection) $this->CreateSection($RowID, $PostValues);
		}

		return $RowID;
	}
	
	protected function CreateSection($RowID, $PostValues) {
		$SectionModel = Gdn::Factory('SectionModel');
		$NodeFields = array(
			'Name' => $PostValues['Title'],
			'Url' => GetValue('URI', $PostValues, Null),
			'RequestUri' => 'candy/content/page/'.$RowID
		);
		$ParentSectionID = GetValue('SectionID', $PostValues);
		$PageSectionID = $SectionModel->InsertNode($ParentSectionID, $NodeFields);
		$this->SQL
			->Update($this->Name)
			->Set('SectionID', $PageSectionID)
			->Where($this->PrimaryKey, $RowID)
			->Put();
	}
	
	public function SetProperty($RowID, $Field, $Value) {
		parent::SetProperty($RowID, $Field, $Value);
	}
	
	public function GetCount($Where = False) {
		$Where['bCountQuery'] = True;
		$Result = $this->Get($Where);
		return $Result;
	}
	
	public function Get($Where = False, $Offset = False, $Limit = False, $OrderBy = 'p.PageID', $OrderDirection = 'desc') {
		$bCountQuery = GetValue('bCountQuery', $Where, False, True);
		if ($bCountQuery) {
			$this->SQL->Select('*', 'count', 'RowCount');
			$Offset = $Limit = False;
		}
		if (GetValue('Browse', $Where, True, True) && !$bCountQuery) {
			$this->SQL
				//->Select('p.Title as Name') // Hmmm... Not sure
				->Select('p.PageID, p.Title, p.Visible, p.SectionID, p.URI')
				->Select('p.DateInserted, p.DateInserted, p.UpdateUserID, p.DateUpdated');
		}
		if ($Join = GetValue('WithSection', $Where, False, True)) {
			if (!in_array($Join, array('left', 'inner'), True)) $Join = 'left';
			$this->SQL
				->Join('Section s', 's.SectionID = p.SectionID', $Join);
			if (!$bCountQuery) {
				$this->SQL
					->Select('s.SectionID as SectionID')
					->Select('s.TreeLeft as SectionTreeLeft')
					->Select('s.TreeRight as SectionTreeRight')
					->Select('s.Depth as SectionDepth')
					->Select('s.ParentID as SectionParentID')
					->Select('s.Name as SectionName')
					->Select('s.RequestUri as SectionRequestUri');
			}
		}
		
		$this->EventArguments['bCountQuery'] = $bCountQuery;
		$this->EventArguments['Where'] =& $Where;
		$this->FireEvent('BeforeGet');
		
		if ($OrderBy !== False && !$bCountQuery) $this->SQL->OrderBy($OrderBy, $OrderDirection);
		if (is_array($Where)) $this->SQL->Where($Where);
		$Result = $this->SQL->From('Page p')->Limit($Limit, $Offset)->Get();
		if ($bCountQuery) $Result = $Result->FirstRow()->RowCount;
		return $Result;
	}
	
	public function GetFullID($PageID) {
		$this->SQL->Select('p.*');
		$Where = array('p.PageID' => $PageID, 'WithSection' => True);
		$DataSet = $this->Get($Where, False, False, False, False);
		$Result = $DataSet->FirstRow();
		return $Result;
	}
	
	public function Delete($PageID) {
		$this->SQL
			->Where('PageID', $PageID)
			->Delete($this->Name);
	}
	
}












