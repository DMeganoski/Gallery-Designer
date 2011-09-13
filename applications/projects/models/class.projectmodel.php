<?php if (!defined('APPLICATION'))
	exit();

class ProjectModel extends Gdn_Model {

	public function __construct() {

		parent::__construct('Project');

	}

	public function ProjectQuery() {

		$this->SQL
			->Select('p.*')
			->From('Project p');
	}

	public function Get() {
		$this->ProjectQuery();
		$Return = $this->SQL->Get();
		return $Return;
	}

	public function GetOpen() {

		$this->ProjectQuery();
		$this->SQL->
				Where('ProjectStage', 2);
		$Result = $this->SQL->Get();
		return $Result;

	}

	public function GetAllUser($UserID = '') {
		if ($UserID != '') {

			$this->ProjectQuery();
			$this->SQL->Where('UserID', $UserID);
			$Result = $this->SQL->Get();
			return $Result;

		}
	}

	public function GetCurrent($UserID = '') {
		if ($UserID != '') {

			$this->ProjectQuery();

				$this->SQL->Where(array('UserID' => $UserID, 'CurrentProject' => 1));

			$Result = $this->SQL->Get()->FirstRow();
			return $Result;

		} else {
			return FALSE;
		}
	}

	public function GetNew($UserID = '') {
		if ($UserID != '') {

			$this->ProjectQuery();

				$this->SQL->Where(array('UserID' => $UserID));

			$Result = $this->SQL->Get()->LastRow();
			return $Result;

		} else {
			return FALSE;
		}
	}

	public function GetSingle($ProjectID = '') {

		if ($ProjectID != '') {

			$this->ProjectQuery();

				$this->SQL->Where(array( 'ProjectKey' => $ProjectID));

			$Result = $this->SQL->Get()->FirstRow();
			return $Result;

		} else {
			return FALSE;
		}

	}

	public function SetCurrent($UserID, $ProjectID = '') {
		$this->SQL->Update('Project');
		$this->SQL->Set('CurrentProject', 0);
		$this->SQL->Where('UserID',$UserID);
		$this->SQL->Put();

		$this->SQL->Update('Project');
		$this->SQL->Set('CurrentProject', 1);
		$this->SQL->Where('ProjectKey',$ProjectID);
		$this->SQL->Put();

	}

	public function NewProject($UserID, $ProjectName = '') {
		$this->SQL->Update('Project', array(
			'CurrentProject' => 0,
			array('UserID' => $UserID
				)));

		$this->SQL->Insert('Project', array(
			'UserID' => $UserID,
			'CurrentProject' => 1,
			'ProjectName' => $ProjectName
			));
	}

	public function DeleteProject($ProjectID) {

		$this->SQL->Delete('Project', array(
			'ProjectKey' => $ProjectID
		));

	}

	public function Insert($Table, $Set, $Where = FALSE, $Limit = FALSE) {
       if (!is_array($Set))
	  return NULL;
       $this->DefineSchema();
       $this->SQL->Insert($Table, $Set, $Where, $Limit);
    }

	public function Update($Table, $Set, $Where = FALSE) {
	if (!is_array($Set))
	  return NULL;
       $this->DefineSchema();

       $this->SQL->Update($Table)
         ->Set($Set);

       if ($Where != FALSE)
         $this->SQL->Where($Where);

	 $this->SQL->Put();
     }

}