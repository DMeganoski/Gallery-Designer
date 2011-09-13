<?php if (!defined('APPLICATION'))
	exit();

class DesignerModule extends Gdn_Module {



	public function __construct(&$Sender = '') {
      parent::__construct($Sender);
   }

	public function AssetTarget() {
      return 'Account';
   }

   public function ToString() {
	   $PublicDir = GalleryController::$PublicDir;
	   ?><div class="Box Items">
	<h4 id="BoxLabel">Selected Items</h4>
	<img src="/themes/TinsDirect/design/images/round_platinum.jpg" class="Chosen"></img>
        <img src="<?php
           echo $PublicDir.'covers/HOL004M.jpg'
        ?>" class="Chosen"></img>
        <h4>Your selected items will go here.</h4>
</div><?php
      $String = ob_get_contents();
      @ob_end_clean();
      return $String;

   }
}