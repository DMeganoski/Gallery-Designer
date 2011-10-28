<?php if (!defined('APPLICATION'))
	exit();

class GalleryItemModel extends Gdn_Model {

	public function __construct() {
		parent::__construct('GalleryItem');
	}

	public function ItemQuery() {
      $this->SQL
         ->Select('gi.*')
         //->Select('t.Label', '', 'Type')
         //->Select('v.AddonVersionID, v.File, v.Version, v.DateReviewed, v.TestedWith, v.MD5, v.FileSize')
         //->Select('v.DateInserted', '', 'DateUploaded')
         //->Select('iu.Name', '', 'InsertName')
         ->From('GalleryItem gi')
         //->Join('GalleryCategory ga', 'gi.CategoryKey = ga.CategoryKey')
         ->Where('gi.Visible', '1');
   }

	public function Get($Offset = '0', $Limit = '', $Wheres = '') {
      if ($Limit == '')
         $Limit = Gdn::Config('Gallery.Items.PerPage', 16);

      $Offset = !is_numeric($Offset) || $Offset < 0 ? 0 : $Offset;

      $this->ItemQuery();

      if (is_array($Wheres))
         $this->SQL->Where($Wheres);

      return $this->SQL
         ->Limit($Limit, $Offset)
         ->Get();
   }

	public function GetWhere($Where = FALSE, $OrderFields = '', $OrderDirection = 'asc', $Limit = FALSE, $Offset = FALSE) {
        $this->ItemQuery();

        if ($Where !== FALSE)
            $this->SQL->Where($Where);

        if ($OrderFields != '')
            $this->SQL->OrderBy($OrderFields, $OrderDirection);

        if ($Limit !== FALSE) {
            if ($Offset == FALSE || $Offset < 0)
            $Offset = 0;

            $this->SQL->Limit($Limit, $Offset);
        }

        $Result = $this->SQL->Get();
        //$this->SetCalculatedFields($Result);
        return $Result;
    }

	public function GetCount($Wheres = '') {
      if (!is_array($Wheres))
         $Wheres = array();

      $Wheres['gi.Visible'] = '1';
      return $this->SQL
         ->Select('gi.ItemKey', 'count', 'CountItems')
	 ->Select('gi.*')
         ->From('GalleryItem gi')
         ->Where($Wheres)
         ->Get()
         ->FirstRow()
         ->CountItems;
   }

	public function GetSlug($ItemSlug) {
		$this->ItemQuery();
		$this->SQL->Where('Slug', $ItemSlug);
		$Result = $this->SQL->Get()->FirstRow();
		return $Result;
	}

	public function GetDirectory($GetClass) {
        $Files = array();
        $Directory = PATH_UPLOADS.DS.'item'.DS;
		$GalleryClassModel = new GalleryClassModel();
        if (!$GalleryClassModel->VerifyClass($GetClass)) {
            $Classes = $GalleryClassModel->GetClasses();
            foreach ($Classes as $Class) {
                $ClassName = $Class->ClassLabel;
                $Files = scandir($Directory.$ClassName);
            }
        } else {
            $Files = scandir($Directory.$GetClass);
        }
        return $Files;
    }

    public function GetFilesInfo($GetClass, $Category = 'home') {
		$Files = $this->GetDirectory($GetClass);

        foreach ($Files as $FileName) {
			if (substr($FileName, 0, 1) != '.') {
				$Item = $this->SplitFileName($FileName);

				if ($Item)
					$AllFiles[] = $Item;
			}
		}
    $SortArray = array();
    foreach ($AllFiles as $FileName) {
        foreach ($FileName as $Key => $Value) {
            if(!isset($SortArray[$Key]))
                $SortArray[$key] = array();

        $SortArray[$Key][] = $Value;
        }
    }
    $OrderBy = "ItemID"; //change this to whatever key you want from the array

    array_multisort($SortArray[$OrderBy],SORT_ASC,$AllFiles);
    return $AllFiles;
    }

	public function SplitFileName($FileName) {
		if (substr($FileName, 6, 1) == 'X') {
			$Item['FileName'] = $FileName;
			$Item['ItemID'] = (substr($FileName, 3, 3));
					if (!is_numeric($Item['ItemID'])) {
						$Item['Visible'] = 0;
					} else {
						$Item['Visible'] = 1;
					}
					if (strlen($FileName) > 11) {
						if (substr($FileName, 6, 1) == 'L') {
							$Item['Name'] = trim(trim(substr($FileName, 10), '.jpg'), '.png');
							$Item['Large'] = $FileName;
						}
					} else {
						$Item['Large'] = $FileName;
					}
					$ShortCat = (substr($FileName, 0, 3));
					$CapsCat = strtoupper($ShortCat);
					$Item['CategoryCAPS'] = $CapsCat;
					$CategoryModel = new GalleryCategoryModel();
					$CategoryKey = $CategoryModel->GetCategoryKey($CapsCat);
					$Item['CategoryKey'] = $CategoryKey;
					$Item['Slug'] = $CapsCat.$Item['ItemID'];
					return $Item;
		}
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

	public function Insert($Table, $Set, $Where = FALSE, $Limit = FALSE) {
       if (!is_array($Set))
	  return NULL;
       $this->DefineSchema();
       $this->SQL->Insert($Table, $Set, $Where, $Limit);
    }

    public function Replace($Table = '', $Set = NULL, $Where, $CheckExisting = FALSE) {
	if (!is_array($Set))
	  return NULL;

       $this->SQL->Replace($Table, $Set, $Where, $CheckExisting);

     }

}