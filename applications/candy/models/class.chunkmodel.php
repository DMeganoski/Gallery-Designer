<?php if (!defined('APPLICATION')) exit();

class ChunkModel extends Gdn_Model {
	
	public function __construct() {
		parent::__construct('Chunk');
	}
	
	
	public function GetCount($Where = False) {
		$Where['bCountQuery'] = True;
		return $this->Get($Where);
	}
	
	public function Save($PostValues, $EditingData = False) {
		ReplaceEmpty($PostValues, Null);
		$Insert = (GetValue('ChunkID', $PostValues) === False);
		if ($Insert) $this->AddUpdateFields($PostValues);
		$RowID = parent::Save($PostValues);
		return $RowID;
	}
	
	public function Get($Where = False, $Offset = False, $Limit = False, $OrderBy = False, $OrderDirection = 'desc') {
		$bCountQuery = GetValue('bCountQuery', $Where, False, True);
		if ($bCountQuery) {
			$this->SQL->Select('*', 'count', 'RowCount');
			$Offset = False;
			$Limit = False;
			$OrderBy = False;
		}
		if (GetValue('Browse', $Where, True, True) && !$bCountQuery) {
			$this->SQL
				->Select('c.ChunkID, c.Name, c.Url, c.InsertUserID, c.DateInserted, c.UpdateUserID, c.DateUpdated');
		}
/*		if (GetValue('InsertAuthor', $Where, False, True) && !$bCountQuery) {
		}
		if (GetValue('UpdateAuthor', $Where, False, True) && !$bCountQuery) {
		}
		*/
		
		$this->EventArguments['bCountQuery'] = $bCountQuery;
		$this->EventArguments['Where'] =& $Where;
		$this->FireEvent('BeforeGet');
		
		if ($OrderBy !== False) $this->SQL->OrderBy($OrderBy, $OrderDirection);
		if (is_array($Where)) $this->SQL->Where($Where);
		$Result = $this->SQL->From($this->Name . ' c')->Limit($Limit, $Offset)->Get();
		if ($bCountQuery) $Result = $Result->FirstRow()->RowCount;
		return $Result;
	}



}







