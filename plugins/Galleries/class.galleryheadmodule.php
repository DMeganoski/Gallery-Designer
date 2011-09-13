<?php if (!defined('APPLICATION')) exit();

class GalleryHeadModule extends Gdn_Module {

   public function __construct(&$Sender = '') {
      parent::__construct($Sender);
   }

  public function GetClasses() {
      $GalleriesModel = new GalleriesModel();
      $Classes = $GalleriesModel->GetClasses();
      return $Classes;
   }

   public function AssetTarget() {
      return 'Content';
   }

   public function ToString() {
      $String = '';
      $Session = Gdn::Session();
	  $permissions=$Session->User->Permissions;
	  $admin=preg_match('/Garden.Settings.Manage/',$permissions);

      ob_start();
      //$Limit = Gdn::Config('UserList.Limit');
      //$Photo = Gdn::Config('UserList.Photo');
      //$Title = Gdn::Config('UserList.Title');
      //$ShowNumUsers = Gdn::Config('UserList.ShowNumUsers');

      //if(empty($Title)) {
      	//$Title="Members";
      //}

      //if($Photo) {

      include_once(PATH_PLUGINS.DS.'Galleries/views/modules/galleryhead.php');

      $String = ob_get_contents();
      @ob_end_clean();
      return $String;
   }
}
