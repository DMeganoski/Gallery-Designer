<?php if (!defined('APPLICATION'))
	exit();


class DownloadController extends GalleriesController {

	public function Initialize() {
		parent::Initialize();
		//$Sender->Form = new Gdn_Form();
		if ($this->Head) {
			$this->AddJsFile('jquery.js');
			$this->AddJsFile('jquery.livequery.js');
			$this->AddJsFile('jquery.form.js');
			$this->AddJsFile('jquery.popup.js');
			$this->AddJsFile('jquery.gardenhandleajaxform.js');
			$this->AddJsFile('global.js');
		}
		$this->MasterView = 'default';
	}

	public function Index($Args) {
		$this->AddJsFile('jquery.qtip.js');
		$this->AddCssFile('jquery.qtip.css');
		$this->AddJsFile('gallery.js');

		//$GalleryHeadModule->GetData();
		$this->AddModule('GalleryHeadModule');
		$this->AddModule('GallerySideModule');
		$this->AddCssFile('gallery.css');
		// Get Request Arguments
		$DownloadClass = ArrayValue('0', $this->RequestArgs, 'none');
		$FileName = ArrayValue('1', $this->RequestArgs, 'none');
		$this->FileName = urldecode($FileName);
		$DownloadOption = ArrayValue('2', $this->RequestArgs, 'none');

		$BasePath = PATH_ROOT.DS."downloads/templates"; // change the path to fit your websites document structure
		//$FullPath = $path.$_GET['download_file'];
		$this->ServeFile = 'empty';
		if ($DownloadClass == 'none') {
			$this->Alert = "oops, wrong specification";
			$this->View = 'notfound';
		} else { // has a DownloadClass
			$this->Alert = 'past class check';
			$FullPath = $BasePath.DS.$DownloadClass;
			if ($FileName == 'none') {
				$this->Alert = "oops, wrong specification";
				$this->View = 'notfound';
			} else { // has a FileName
				$this->Alert = "past name check";
				$FullPath .= DS.$FileName;
				$this->Path = $FullPath;
				// now check to see if we$this->Path = $FullPath; have an option
				if ($DownloadOption == 'none') {
					// since there is no option, check for a file
					if (file_exists($FullPath)) { // no option and file exists, serve file
						$this->ServeFile = $FullPath;
						$ServeFile = TRUE;
						$this->Alert = "File Exists";
					} else { // no option and no file, oops.
						$this->Alert = "oops, wrong name";
						$this->View = 'notfound';
					}
				} else { // there is a dowload option
					//header("Content-type: application/pdf"); // add here more headers for diff. extensions
					//$FullPath .= DS.$DownloadOption;
					// now check for the file
					if (file_exists($FullPath)) {
						$ServeFile == TRUE;
					}
				}
			}
		}
		$PathParts = pathinfo($FullPath);
		//$Filename = Gdn_Format::Url($PathParts['filename']).'.zip';
		if ($ServeFile === TRUE) {
			$this->Alert = 'Suppposed to be serving file...';
			$this->ServeFile($FullPath, $FileName);

		}
	$this->Render();
	}
	
	public function ServeFile($FullPath, $FileName) {
		Gdn_FileSystem::ServeFile($FullPath, $Filename);
	}

}