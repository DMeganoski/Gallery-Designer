<?php if (!defined('APPLICATION'))
    exit();


class GallerySideModule extends Gdn_Module {

   public function __construct(&$Sender = '') {
      parent::__construct($Sender);
   }


   public function AssetTarget() {
      return 'Panel';
   }

   public function ToString() {
      $String = '';
      $Session = Gdn::Session();
	  $permissions=$Session->User->Permissions;
	  $admin=preg_match('/Garden.Settings.Manage/',$permissions);

      ob_start();

      include_once(PATH_APPLICATIONS.DS.'galleries/views/modules/galleryside.php');

      $String = ob_get_contents();
      @ob_end_clean();
      return $String;
   }
}