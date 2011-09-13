<?php if (!defined('APPLICATION'))
	exit();

$PluginInfo['RandomNumbers'] = array(
   'Description' => 'This plugin allows for the generation and storage of random numbers',
   'Version' => '0.1',
   'Author' => "Darryl Meganoski",
   'AuthorEmail' => 'zodiacdm@gmail.com',
   'AuthorUrl' => 'www.facebook.com/zodiacdm',
   'HasLocale' => TRUE,

);

class RandomNumbers implements Gdn_IPlugin {

	public function PluginController_Random_Create($Sender) {
		$this->Form = new Gdn_Form();
		$RandomNumberModel = new Gdn_Model('RandomNumbers');
		$this->Form->SetModel($RandomNumberModel);
		if ($Sender->Form->AuthenticatedPostBack() === FALSE) {

		} else {

		}
		$Sender->View = dirname(__FILE__).DS.'views'.DS.'random.php';
		$Sender->Render();
	}

	public function PluginController_Generate_Create($Sender) {
		$SQL = Gdn::SQL();

		$LineMaxString = GetValue(0, $Sender->RequestArgs, 0);
		$LineMax = intval($LineMaxString);
		$SectionMaxString = GetValue(1, $Sender->RequestArgs, 0);
		$SectionMax = intval($SectionMaxString);
		$Replace = GetValue(2, $Sender->RequestArgs, 0);

		if ($Replace == '0')
			$UseSet = FALSE;
		else
			$UseSet = TRUE;

		if ($UseSet === FALSE)
			$SetID = rand(1,100);
		else
			$SetID = $Replace;

		if ($UseSet)
				$SQL->Delete('RandomNumbers', array('SetID' => $SetID));

		for ($i = 0; $i <= $LineMax;) {
			$ThisLine = array();
			for ($i = 0; $i <= $SectionMax; $i++) {
				$Rand = rand(1,54);
				if (!in_array($Rand, $ThisLine)) {
					array_push($ThisLine, $Rand);
					$i++;
				}
			}

			$ImpLine = implode('-' , $ThisLine);

			$SQL->Insert('RandomNumbers', array(
				'LineNums' => $ImpLine,
				'SetID' => $SetID,
				));
		}

	}

	public function RandomDelete($Sender) {
		if (Gdn::Session()->IsValid()) {
			$SetID = GetValue(0, $Sender->RequestArgs, 0);
			$SQL = Gdn::SQL();
			$SQL->Delete('RandomNumbers', array('SetID' => $SetID));
		}
	}

	public function Setup() {
		$Structure = Gdn::Structure();
		$Structure->Table('RandomNumbers')
		->PrimaryKey('LineID')
        ->Column('LineNums', 'int', FALSE)
        ->Column('SetID', 'varchar(64)', FALSE)
		->Column('Selection', 'varchar(255)', TRUE)
        ->Set(FALSE, FALSE);
	}

}
