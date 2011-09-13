<?php if (!defined('APPLICATION'))
	exit();


class TinFooterModule extends Gdn_Module {

   public function __construct(&$Sender = '') {
      parent::__construct($Sender);
   }

   public function AssetTarget() {
      return 'Foot';
   }

   public function ToString() {
      $String = '';
      $Session = Gdn::Session();
	  $permissions=$Session->User->Permissions;
	  $admin=preg_match('/Garden.Settings.Manage/',$permissions);

      ob_start();

      include_once(PATH_THEMES.DS.'views/footerinfo.php');

      $String = ob_get_contents();
      @ob_end_clean();
      return $String;
   }
}