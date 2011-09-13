<?php if (!defined('APPLICATION')) exit();

class RoutesController extends CandyController {
	
	public $Uses = array('Form');
	public $Editing;
	protected $AdminView = True;
	
	public function Initialize() {
		parent::Initialize();
		if ($this->DeliveryType() == DELIVERY_TYPE_ALL) {
			$this->AddSideMenu();
		}
	}
	
	public function Index($Page = '') {
		$this->Permission('Candy.Settings.View');
/*		list($Offset, $Limit) = OffsetLimit($Page, 30);
		$this->X = $this->XModel->Get('', $Offset, $Limit);
		$this->RecordCount = $this->XModel->GetCount();
		$this->Url = '/candy//%s';
		$this->Pager = new PagerModule($this);
		$this->Pager->Configure($Offset, $Limit, $this->RecordCount, $this->Url);*/
		$this->RouteDataSet = CandyModel::GetRoutes();
		$this->Title(T('Routes'));
		$this->Render();
	}
	
	public function Update($Reference = '', $PostBackKey = '') {
		$this->Permission('Candy.Routes.Manage');
	}
	
	public function Delete($EncodedURI) {
		$this->Permission('Candy.Routes.Manage');
		$URI = base64_decode($EncodedURI);
		CandyModel::DeleteRoute($URI);
		if ($this->DeliveryType() == DELIVERY_TYPE_ALL) {
			Redirect('/candy/routes');
		}
	}
	
}



