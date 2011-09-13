<?php if (!defined('APPLICATION')) exit();
/*
Copyright 2008, 2009 Vanilla Forums Inc.
This file is part of Garden.
Garden is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Garden is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Garden.  If not, see <http://www.gnu.org/licenses/>.
Contact Vanilla Forums Inc. at support [at] vanillaforums [dot] com
*/
/**
 * Category Model
 *
 * @package Vanilla
 */

/**
 * Manages discussion categories.
 *
 * @since 2.0.0
 * @package Vanilla
 */
class GalleryCategoryModel extends Gdn_Model {

	public $Uses = array('GalleryClassModel');

	public function __construct() {
        parent::__construct('GalleryCategory');
    }

	public function CategoryQuery() {
		$this->SQL
        ->Select('ga.*')
		->From('GalleryCategory ga');
   }
    /*
     * Get the list of categories
     * @param $GetClass, get the categories of the given class
     */
    public function GetCategories($GetClass = '') {
		$SQL = Gdn::SQL();
		$GalleryClassModel = new GalleryClassModel();
		$Key = $GalleryClassModel->GetClassKey($GetClass);
		$this->CategoryQuery();
		$SQL
			->Where('ga.ClassKey', $Key);
			$Result = $SQL->Get();
			return $Result;
   }

	public function GetCatWhere($Where = FALSE) {
        $this->CategoryQuery();

        if ($Where !== FALSE)
            $this->SQL->Where($Where);

        $Result = $this->SQL->Get();
        //$this->SetCalculatedFields($Result);
        return $Result;
    }

	public function GetCategoryKey($GetCategoryCaps) {
		$ClassModel = new GalleryClassModel();
		$Classes = $ClassModel->GetClasses();
		foreach ($Classes as $Class) {
			$ClassName = $Class->ClassLabel;
			$Categories = $this->GetCategories($ClassName);
			foreach ($Categories as $Category) {
				$CategoryName = $Category->CategoryLabel;
				$ShortCat = substr($CategoryName, 0, 3);
				$CapsCat = strtoupper($ShortCat);
				$Key = $Category->CategoryKey;
				if ($CapsCat == $GetCategoryCaps) {
					return $Key;

				}
			}
		}
	}

	public function GetCategoryLabel($CategoryKey) {
		$Categories = $this->GetCategories();
		$SQL = Gdn::SQL();
		$SQL
		->Select('ga.*')
		->From('GalleryCategory ga');
		$SQL->Where('ga.CategoryKey', $CategoryKey);
		$Result = $SQL->Get();
		return $Result;
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

	public function Save($FileInfo) {
		$Session = Gdn::Session();

		$this->DefineSchema();

		if (!is_array($FileInfo)) {
            $this->Validation->AddValidationResult('Name', 'FileInfo was not an array');
            return FALSE;
         }

		//$this->Validation->AddValidationResult($VersionModel->ValidationResults());

        $Slug = $FileInfo['Slug'];
	    $this->SQL->Update('GalleryCategory');
		$this->SQL->Set(array(
			'CategoryLabel' => $FileInfo['CategoryLabel']
	       ));
		$this->SQL->Where(array('CategoryKey' => $FileInfo['CategoryKey']));
		$this->SQL->Put();

		return TRUE;
	}


}

