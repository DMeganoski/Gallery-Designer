<?php if (!defined('APPLICATION')) exit();

class CreatePageModule extends Gdn_Module {
	
	public function __construct($Sender = '', $ApplicationFolder = False) {
		parent::__construct($Sender, $ApplicationFolder);
	}
	
	public function AssetTarget() {
		return 'Content';
	}
	
	public function CheckPermission() {
		return CheckPermission('Candy.Pages.Add');
	}

	public function ToString() {
		$String = '';
		if ($this->CheckPermission()) $String = parent::ToString();
		return $String;
	}
}
