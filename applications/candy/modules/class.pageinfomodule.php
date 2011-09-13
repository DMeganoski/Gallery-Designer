<?php if (!defined('APPLICATION')) exit();

class PageInfoModule extends Gdn_Module {
	
	public function __construct($Sender = '') {
		parent::__construct($Sender);
		$this->WrapString = $Sender->DeliveryType() == DELIVERY_TYPE_ALL;
	}
	
	public function AssetTarget() {
		return 'Panel';
	}
	
	public function CheckPermission() {
		$Content = $this->_Sender->Data('Content');
		if (CheckPermission('Candy.Page.Edit') || (IsContentOwner($Content) && CheckPermission('Candy.Page.Add'))) {
			return True;
		}
	}

	public function ToString() {
		$String = '';
		if ($this->CheckPermission()) $String = parent::ToString();
		if ($String != '') {
			$WrapString = GetValue('WrapString', $this, True);
			if ($WrapString) 
				$String = Wrap($String, 'div', array('class' => 'Box', 'id' => 'PageInfoModule'));
		}
		return $String;
	}
	
}
