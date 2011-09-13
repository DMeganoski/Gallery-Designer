<?php if (!defined('APPLICATION'))
	exit();

class GalleryClassModel extends Gdn_Model {

	public function __contstruct() {
		parent::__construct();
	}

	public function ClassQuery() {
		$this->SQL
				->Select('ga.*')
				->From('GalleryClasses ga');
	}

	public function GetClasses($Single = '') {
       $this->SQL
            ->Select('gl.*')
            ->From('GalleryClass gl');

	   if ($Single != '')
		   $this->SQL->Where(array('ClassLabel' => $Single));

        $Result = $this->SQL->Get();
        //$this->SetCalculatedFields($Result);
        return $Result;
   }

	public function GetClassKey($GetClass) {
       $SQL = Gdn::SQL();
       $Classes = $this->GetClasses();
       foreach ($Classes as $Class) {
            $Key = $Class->ClassKey;
            $ClassName = $Class->ClassLabel;
            if ($ClassName == $GetClass) {
                return $Key;

	    }
       }
   }

	public function VerifyClass($VerifyClass) {
        $Classes = $this->GetClasses();
        foreach ($Classes as $Class) {
            $Label = $Class->ClassLabel;
            if ($VerifyClass == $Label) {
                $Return = TRUE;
            }
		}
            if ($Return) {
                return TRUE;
            } else {
                return FALSE;
            }

    }

	public function VerifyCategory($VerifyCategory, $Class) {
        $Categories = $this->GetCategories($Class);
        foreach ($Categories as $Category) {
            $Label = $Category->CategoryLabel;
            if ($VerifyCategory == $Label) {
                $Return = TRUE;
            }
        }
        if ($Return) {
            return TRUE;
        } else {
            return FALSE;
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

    public function Replace($Table = '', $Set = NULL, $Where, $CheckExisting = FALSE) {
	if (!is_array($Set))
	  return NULL;

       $this->SQL->Replace($Table, $Set, $Where, $CheckExisting);

     }



}