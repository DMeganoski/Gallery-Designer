<?php if (!defined('APPLICATION'))
	exit();

class GalleryUploadModel extends Gdn_Model {

	public function __construct() {
		parent::__construct('GalleryUpload');
	}

	public function UploadQuery() {

		$this->SQL
			->Select('gu.*')
			//->Select('t.Label', '', 'Type')
			//->Select('v.AddonVersionID, v.File, v.Version, v.DateReviewed, v.TestedWith, v.MD5, v.FileSize')
			//->Select('v.DateInserted', '', 'DateUploaded')
			//->Select('iu.Name', '', 'InsertName')
			->From('GalleryUpload gu');

   }

   public function GetUploads($Offset = '0', $Limit = '', $Wheres = '') {
	   if ($Limit == '')
         $Limit = Gdn::Config('Gallery.Uploads.PerPage', 16);

      $Offset = !is_numeric($Offset) || $Offset < 0 ? 0 : $Offset;

      $this->UploadQuery();

      if (is_array($Wheres))
         $this->SQL->Where($Wheres);

      return $this->SQL
         ->Limit($Limit, $Offset)
         ->Get();
   }

	public function GetUploadID($UploadID) {
      if ($Limit == '')
         $Limit = Gdn::Config('Galleries.Items.PerPage', 16);

      $this->UploadQuery();

      if (is_array($Wheres))
         $this->SQL->Where($Wheres);

      return $this->SQL
         ->Limit($Limit, $Offset)
         ->Get()->FirstRow();
   }


	public function GetCount($Wheres = '') {

		if (!is_array($Wheres))
         $Wheres = array();
      return $this->SQL
         ->Select('gu.UploadKey', 'count', 'CountItems')
	 ->Select('gu.*')
         ->From('GalleryUpload gu')
         ->Where($Wheres)
         ->Get()
         ->FirstRow()
         ->CountItems;
   }

	public function UploadFile($FileInfo) {
		$ExistingItem = $this->GalleriesModel->GetCount(array('FileName' => $FileInfo['name']));
		if ($ExistingItem < 1) {
			$this->Insert('GalleryUpload', array(
				'FileName' => $FileInfo['name']
				));
		} else {
			$this->Update('GalleryUpload', array(
	       'FileName' => $FileInfo['name']
				), array('FileName' => $FileInfo['name']));
		}
	}

	public function Insert($Table, $Set, $Where = FALSE, $Limit = FALSE) {
       if (!is_array($Set))
	  return NULL;
       $this->DefineSchema();
       $this->SQL->Insert($Table, $Set, $Where, $Limit);
    }

	public function Update($Table, $Set, $Where = FALSE) {
	if (!is_array($Set))
	  return NULL;
       $this->DefineSchema();

       $this->SQL->Update($Table)
         ->Set($Set);

       if ($Where != FALSE)
         $this->SQL->Where($Where);

	 $this->SQL->Put();
     }

	 public function Remove($UploadID) {
		 if ($UploadID != '' ) {
			 $this->SQL->Delete('GalleryUpload', array('UploadKey' => $UploadID));
		 }
	 }



}