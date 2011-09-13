<?php if (!defined('APPLICATION')) exit();

class CandyController extends Gdn_Controller {
	
	protected $AdminView;
	
	public function Initialize() {
		if ($this->DeliveryType() == DELIVERY_TYPE_ALL) {
			$this->Head = new HeadModule($this);
			$this->AddJsFile('jquery.js');
			$this->AddJsFile('jquery.livequery.js');
			$this->AddJsFile('jquery.menu.js');
			$this->AddJsFile('global.js');
			if ($this->AdminView) {
				$this->MasterView = 'admin';
				$this->AddCssFile('admin.css');
			} else {
				$this->AddCssFile('style.css');
			}
			$this->AddCssFile('candy.css');
			$this->AddJsFile('candy.js'); // Application global js
		}
		parent::Initialize();
	}
	
	public function AddSideMenu($CurrentUrl = '') {
		if ($this->_DeliveryType == DELIVERY_TYPE_ALL) {
			$SideMenu = new SideMenuModule($this);
			//$SideMenu->HtmlId = 'CandySideMenu';
			$SideMenu->CssClass = 'CandySideMenu';
			$SideMenu->HighlightRoute($CurrentUrl);
			$SideMenu->Sort = C('Garden.DashboardMenu.Sort');
			$this->EventArguments['SideMenu'] =& $SideMenu;
			$this->FireEvent('GetAppSettingsMenuItems');
			$this->AddModule($SideMenu, 'Panel');
		}
	}
	
	public function Slug() {
		$Session = Gdn::Session();
		if ($Session->IsValid()) {
			$Text = GetIncomingValue('Text');
			echo CandyModel::Slug($Text);
		}
	}
	
}





