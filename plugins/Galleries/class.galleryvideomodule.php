<?php if (!defined('APPLICATION'))
    exit();



class GalleryVideoModule extends Gdn_Module {

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

      include_once(PATH_PLUGINS.DS.'Galleries/views/modules/galleryvideo.php');

      $String = ob_get_contents();
      @ob_end_clean();
      return $String;
   }






}

