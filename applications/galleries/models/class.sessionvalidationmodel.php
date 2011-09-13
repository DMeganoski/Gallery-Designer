<?php if (!defined('APPLICATION'))
	exit();

class SessionValidationModel extends Gdn_Model {

	public function __construct() {
        parent::__construct('Session');
	}

	public function SessionQuery() {
		$this->SQL
			->Select('s.*')
			->From('Session s');
	}

	/*
	 *  Compares the given user id and transient key to the keystored in the session table
	 *	@Params $UserID the user to verify
	 *	@Params $TransientKey the key to match up with the user
	 */
	public function VerifyTransientKey($UserID = '', $TransientKey = '') {

		if ($UserID != '') {
			$this->SessionQuery();
			$this->SQL
				->Where('UserID', $UserID);
			$Session = $this->SQL->Get()->FirstRow();
			$SessionTransient = $Session->TransientKey;
			if ($SessionTransient == $TransientKey)
				return TRUE;
			else return TRUE;
		}
	}
}
