<?php if (!defined('APPLICATION'))
    exit();

class GalleriesModel extends Gdn_Model {

    public static $PublicDir = '/tinsdirect/uploads/item/';

    public function __construct() {
        parent::__construct('GalleriesPlugin');
    }

    public function ItemQuery() {
      $this->SQL
         ->Select('gi.*')
         //->Select('t.Label', '', 'Type')
         //->Select('v.AddonVersionID, v.File, v.Version, v.DateReviewed, v.TestedWith, v.MD5, v.FileSize')
         //->Select('v.DateInserted', '', 'DateUploaded')
         //->Select('iu.Name', '', 'InsertName')
         ->From('GalleryItems gi')
         ->Join('GalleryClass gl', 'gi.ClassKey = gl.ClassKey')
         ->Join('GalleryCategory ga', 'gi.CategoryKey = ga.CategoryKey')
         ->Where('gi.Visible', '1');
   }

    public function Get($Offset = '0', $Limit = '', $Wheres = '') {
      if ($Limit == '')
         $Limit = Gdn::Config('Plugin.Gallery.PerPage', 20);

      $Offset = !is_numeric($Offset) || $Offset < 0 ? 0 : $Offset;

      $this->ItemQuery();

      if (is_array($Wheres))
         $this->SQL->Where($Wheres);

      return $this->SQL
         ->Limit($Limit, $Offset)
         ->Get();
   }

    public static function GetImageSize($Path) {
	if (!in_array(strtolower(pathinfo($Path, PATHINFO_EXTENSION)), array('bmp', 'gif', 'jpg', 'jpeg', 'png')))
	    return array(0, 0);

	$ImageSize = @getimagesize($Path);
	if (is_array($ImageSize))
	    return array($ImageSize[0], $ImageSize[1]);
	return array(0, 0);
    }

    public static function GetClasses() {
       $SQL = Gdn::SQL();
       $SQL
            ->Select('gl.*')
            ->From('GalleryClass gl');
        $Result = $SQL->Get();
        //$this->SetCalculatedFields($Result);
        return $Result;
   }
    /*
     * Get the list of categories
     * @param $GetClass, get the categories of the given class
     */
    public static function GetCategories($GetClass) {
       $SQL = Gdn::SQL();
       $Key = self::GetClassKey($GetClass);
       //$Classes = self::GetClasses();
       //foreach ($Classes as $Class) {
            //$Key = $Class->ClassKey;
            //$ClassName = $Class->ClassLabel;
            //if ($ClassName == $GetClass) {
            $SQL
                   ->Select('ga.*')
                   ->From('GalleryCategory ga')
                   ->Where('ga.ClassKey', $Key);
                   $Result = $SQL->Get();
                   return $Result;
           //$this->SetCalculatedFields($Result);
           //}
       //}
   }

    public static function GetClassKey($GetClass) {
       $SQL = Gdn::SQL();
       $Classes = self::GetClasses();
       foreach ($Classes as $Class) {
            $Key = $Class->ClassKey;
            $ClassName = $Class->ClassLabel;
            if ($ClassName == $GetClass) {
                return $Key;
            }
       }
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
        $this->SetCalculatedFields($Result);
        return $Result;
    }

    public function GetFileInfo($Category, $Class, $ItemID) {

        if ($Category == '')
            $Category = self::$Category;

        if ($Class == '')
            $Class = self::$Class;

        if ($ItemID == '') {
            return FALSE;

        $AllFiles = $this->GetFilesInfo($Class, $Category);
        foreach ($AllFiles as $Files => $File)
        $FoundFile = $AllFiles[array_search($ItemID, $File)];

        if ($FoundFile)
        $Result = $FoundFile;
        }

        return $Result;
    }

    public function GetCount($Wheres = '') {
      if (!is_array($Wheres))
         $Wheres = array();

      $Wheres['gi.Visible'] = '1';
      return $this->SQL
         ->Select('gi.ItemKey', 'count', 'CountItems')
         ->From('GalleryItems gi')
         ->Where($Wheres)
         ->Get()
         ->FirstRow()
         ->CountItems;
   }
    /*
     * @ param $Class class directory to scan
     * @ param $Category category to filter to
     */
    public static function GetDirectory($GetClass) {
        $Files = array();
        $Directory = PATH_UPLOADS.DS.'item'.DS;
        if ($GetClass == 'item') {
            $Classes = self::GetClasses();
            foreach ($Classes as $Class) {
                $ClassName = $Class->ClassLabel;
                $Files = scandir($Directory.$ClassName);
            }
        } else {
            $Files = scandir($Directory.$GetClass);
        }
        return $Files;
    }

    public static function GetFilesInfo($GetClass, $Category = 'home') {
            $Files = self::GetDirectory($GetClass);
            if ($Category != 'home' && $GetClass != 'item') {
                if (GalleriesModel::VerifyCategory($Category,$GetClass)) {
                    $ShortCat = substr($Category, 0, 3);
                    $CapsCat = strtoupper($ShortCat);
                }
            }
            foreach ($Files as $FileName) {
                if (substr($FileName, 0, 1) != '.') {
                    $Item['FileName'] = $FileName;
                    $Item['ClassLabel'] = $GetClass;
                    $ItemID = (substr($FileName, 3, 3));
                    $Item['ItemID'] = $ItemID;
                    $Item['Size'] = (substr($FileName, 6, 1));
                    if (strlen($FileName) > 11) {
                        if (substr($FileName, 7, 1) != 'F') {
                            $Item['Name'] = (substr($FileName, 9));
                            $Item['Frame'] = 0;
                        } else {
                            $Item['Name'] = NULL;
                            $Item['Frame'] = 1;
                        }
                    } else {
                        $Item['Name'] = NULL;
                        $Item['Frame'] = 0;
                    }
                    $ItemCategory = (substr($FileName, 0, 3));
                    $Item['CategoryID'] = $ItemCategory;
                    $Item['Slug'] = $ItemCategory.$ItemID;

                    // now filter the results
                    if ($Category != 'home') {
                        if ($Item['CategoryID'] == $CapsCat) {
                            if ($Item['Name'] == NULL) {
                                if ($Named != 1) {
                                    if (($Item['Size'] != 'L') && ($Item['Frame'] != 1)) {
                                        if ($Item['Name'] == NULL) {
                                            $AllFiles[] = $Item;
                                            $Named = 0;
                                        }

                                    }
                                }
                            } else {
                            $AllFiles[] = $Item;
                            $Named = 1;
                            }
                        }
                    } else {
                        $AllFiles[] = $Item;
                    }
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

    public static function VerifyClass($VerifyClass) {
        $Classes = GalleriesModel::GetClasses();
        foreach ($Classes as $Class) {
            $Label = $Class->ClassLabel;
            if ($VerifyClass == $Label) {
                $Return = TRUE;
            }
            if ($Return) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    public static function VerifyCategory($VerifyCategory, $Class) {
        $Categories = GalleriesModel::GetCategories($Class);
        foreach ($Categories as $Category) {
            $Label = $Category->CategoryLabel;
            if ($Label == $VerifyCategory) {
                $Return = TRUE;
            }
        }
        if ($Return) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function SetCalculatedFields(&$Data, $Unset = TRUE) {
      if (!$Data)
         return;

      if (is_a($Data, 'Gdn_DataSet')) {
         $this->SetCalculatedFields($Data->Result());
      } elseif (is_object($Data) || !isset($Data[0])) {
         $FileName = GetValue('FileName', $Data);
         $Class = GetValue('ClassLabel', $Data);
         SetValue('URL', $Data, Url("/uploads/item/$Class/$FileName", TRUE));

         if (GetValue('ItemID', $Data) && GetValue('CategoryID', $Data)) {
            $Slug = strtolower(GetValue('CategoryID', $Data).GetValue('ItemID', $Data));
            SetValue('Slug', $Data, $Slug);
         }

         if ($Unset) {
//            unset($Data['File']);
         }

      } else {
         foreach ($Data as &$Row) {
            GalleriesModel::SetCalculatedFields($Row);
         }
      }
   }

    public function SetProperty($ItemKey, $Property, $ForceValue = FALSE) {
      if ($ForceValue !== FALSE) {
         $Value = $ForceValue;
      } else {
         $Value = '1';
         $Item = $this->GetKey($ItemKey);
         $Value = ($Item[$Property] == '1' ? '0' : '1');
      }
      $this->SQL
         ->Update('GalleryItems')
         ->Set($Property, $Value)
         ->Where('ItemKey', $ItemKey)
         ->Put();
      return $Value;
   }
    /*
     *  Combined function. Checks Directories for a given class and/or category.
     *  Then checks to see if each file exists
     *  If it doesn't, it saves it to the database
     */
    public function SetFilesInfo($Class, $Category) {
        $AllFiles = $this->GetFilesInfo($Class, $Category);
        foreach ($AllFiles as $FileInfo) {
            $this->SetCalculatedFields($FileInfo);
            $Exists = $this->GetWhere(array('ClassID' => $FileInfo['ClassID'],
                'CategoryID' => $FileInfo['CategoryID'], 'ItemID' => $FileInfo['ItemID']));
            if (!$Exists) {
                $this->Save($FileInfo);
            }
        }


    }
    /*
     *  Saves a single file
     */
    public function Save($Item) {
      $Session = Gdn::Session();

      $this->DefineSchema();

      // Most of the values come from the file itself.
      if (isset($Item['URL'])) {
         $Path = $Item['URL'];
      } elseif (isset($Item['FileName'])) {
         $Path = CombinePaths(array(PATH_UPLOADS.DS.'item'.DS.$Item['ClassID'].DS.$Item['FileName']));
      } else {
         if (!$Session->CheckPermission('Plugin.Gallery.Manage') && isset($Item['Filename'])) {
            // Only admins can modify plugin attributes without the file.
            $this->Validation->AddValidationResult('Filename', 'ValidateRequired');
            return FALSE;
         }
      }
}
}
