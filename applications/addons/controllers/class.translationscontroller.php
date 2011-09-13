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
 * MessagesController handles displaying lists of conversations and conversation messages.
 */
class TranslationsController extends AddonsController {
   
   public $Uses = array('Form');
   
   public function Initialize() {
      parent::Initialize();
		$this->AddJsFile('jquery.js');
		$this->AddJsFile('jquery.livequery.js');
		$this->AddJsFile('jquery.form.js');
		$this->AddJsFile('jquery.popup.js');
		$this->AddJsFile('jquery.gardenhandleajaxform.js');
		$this->AddJsFile('global.js');
   }
   
   public function NotFound() {
      $this->Render();
   }

   /**
    * Home Page
    */
   public function Index($LanguageID = '') {
      if ($LanguageID != '')  {
			$this->View = 'Language';
			$LanguageModel = new Gdn_Model('Language');
         $this->Language = $LanguageModel->GetWhere(array('LanguageID' => $LanguageID));
			if (!is_object($this->Language))
				$this->View = 'NotFound';
		} else {
			$UserLanguageModel = new UserLanguageModel();
			$this->LanguageData = $UserLanguageModel->Get();
			$TranslationModel = new Gdn_Model('Translation');
			$this->CountTranslations = $TranslationModel->GetCount();
      }
      $this->Render();
   }
	
	public function Mine() {
		$Session = Gdn::Session();
		$UserLanguageModel = new UserLanguageModel();
		$this->LanguageData = $UserLanguageModel->Get(array('InsertUserID' => $Session->UserID));
		$TranslationModel = new Gdn_Model('Translation');
		$this->CountTranslations = $TranslationModel->GetCount();
		$this->View = 'index';
		$this->Render();
	}
   
   /**
    * Add a new translation
    */
   public function Add() {
		$this->Permission('Addons.Translations.Add');
      $UserLanguageModel = new UserLanguageModel();
      $this->Form->SetModel($UserLanguageModel);
		$LanguageModel = new LanguageModel();
		$this->LanguageData = $LanguageModel->Get();
      
      if ($this->Form->AuthenticatedPostBack()) {
         // Save the addon
         $UserLanguageID = $this->Form->Save();
         // Redirect to the new translation
         if ($UserLanguageID !== FALSE)
				$this->RedirectUrl = Url('translations/edit/'.$UserLanguageID);
      }
      $this->Render();      
   }
   
	// Edit a set of translations
   public function View($UserLanguageID = '') {
		$Session = Gdn::Session();
		$this->Permission('Addons.Translations.Add');
		$this->AddJsFile('/js/library/jquery.autogrow.js');
		$this->AddJsFile('forms.js');
      
		$UserLanguageModel = new UserLanguageModel();
      $this->UserLanguage = $UserLanguageModel->GetID($UserLanguageID);
      if (!$this->UserLanguage)
         Redirect('dashboard/home/filenotfound');
			
		$TranslationModel = new TranslationModel();
		$this->CountTranslations = $TranslationModel->GetCount();
		$this->TranslationData = $TranslationModel->Get();
         
		// Don't allow the user to edit if they aren't associated with it
		if ($UserLanguageModel->GetWhere(
			array(
				'UserID' => $Session->UserID,
				'LanguageID' => $this->UserLanguage->LanguageID
			)
		)->NumRows() == 0)
         $this->Permission('Addons.Translations.Manage');
         
      $this->Form->SetModel($UserLanguageModel);
      $this->Form->AddHidden('UserLanguageID', $this->UserLanguage->UserLanguageID);
      
      if ($this->Form->AuthenticatedPostBack() === FALSE) {
         $this->Form->SetData($this->UserLanguage);
      } else {
         if ($this->Form->Save() !== FALSE) {
            $this->StatusMessage = T("Your changes have been saved successfully.");
            $this->RedirectUrl = Url('/translation/'.$UserLanguageID.'/');
         }
      }
      
      $this->Render();
   }
   
	// Only admins can delete entire sets of translations
   public function Delete($UserLanguageID = '') {
		$this->Permission('Addons.Translations.Manage');
		
		$UserLanguageModel = new Gdn_Model('UserLanguage');
      $UserLanguage = $UserLanguageModel->GetID($UserLanguageID);
		$UserLanguageModel->Delete(array('UserLanguageID' => $UserLanguage->UserLanguageID));

		$TranslationModel = new Gdn_Model('Translation');
		$TranslationModel->Delete(array('UserLanguageID' => $UserLanguage->UserLanguageID));
   }
	
	// Import language definitions from a csv file
	public function ImportLanguages() {
		$this->Permission('Addons.Languages.Manage'); // Only root admins can run this method
		$LanguageModel = new Gdn_Model('Language');
		$Arr = array();
		$Lines = file(PATH_APPLICATIONS . DS . $this->ApplicationFolder . DS . 'settings/languages.txt');
		if (is_array($Lines)) {
			foreach ($Lines as $Line) {
				$Parts = explode(',', $Line);
				$Code = trim($Parts[0]);
				$Name = trim($Parts[2]);
				
				// Insert the language if it does not already exist
				if ($LanguageModel->GetWhere(array('Code' => $Code))->NumRows() == 0) {
					echo '<div style="color: green;">Inserting: '.$Name.' ('.$Code.')</div>';
					$LanguageModel->Save(array(
						'Name' => $Name,
						'Code' => $Code
					));
				} else {
					echo '<div style="color: red;">Exists: '.$Name.' ('.$Code.')</div>';
				}
			}
		}
	}
}