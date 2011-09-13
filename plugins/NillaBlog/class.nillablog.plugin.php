<?php if (!defined("APPLICATION")) exit();
/*
 *  Nillablog vanilla plugin.
 *  Copyright (C) 2011 ddumont@gmail.com
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>. 
 */

$PluginInfo["NillaBlog"] = array(
	"Name" => "NillaBlog",
	"Description" => "A blog plugin for vanilla 2+",
	"Version" => "1.1",
	"Author" => "Dan Dumont",
	"AuthorEmail" => "ddumont@gmail.com",
	"SettingsUrl" => "/dashboard/settings/nillablog",
	"SettingsPermission" => "Garden.Settings.Manage",
	"AuthorUrl" => "http://blog.canofsleep.com"
);

class NillaBlog extends Gdn_Plugin {	
	
	public function SettingsController_NillaBlog_Create($Sender) {
		$Validation = new Gdn_Validation();
		$ConfigurationModel = new Gdn_ConfigurationModel($Validation);
		$ConfigurationModel->SetField(array("Plugins.NillaBlog.CategoryID"));
		$Sender->Form->SetModel($ConfigurationModel);

		if ($Sender->Form->AuthenticatedPostBack() === FALSE) {
			$Sender->Form->SetData($ConfigurationModel->Data);
		} else {
        	$Data = $Sender->Form->FormValues();
        	$ConfigurationModel->Validation->ApplyRule("Plugins.NillaBlog.CategoryID", array("Required", "Integer"));
        	if ($Sender->Form->Save() !== FALSE)
        		$Sender->StatusMessage = T("Your settings have been saved.");
		}
		
		$Sender->AddSideMenu();
		$Sender->SetData("Title", T("NillaBlog Settings"));
		$Sender->Render($this->GetView("settings.php"));
	}	
	
	
	public function CategoriesController_AfterDiscussionTitle_Handler(&$Sender) {
		$Discussion = $Sender->EventArguments['Discussion'];
		
		if ($Sender->CategoryID != C("Plugins.NillaBlog.CategoryID"))
			return;	
		
		$Body = $Discussion->Body;
		$end = strrpos($Body, "<hr");
		if ($end)
			$Body = substr($Body, 0, $end);
		$Discussion->FormatBody = Gdn_Format::To($Body, $Discussion->Format);
		?>
			<ul class="MessageList">
				<li>
					<div class="Message NillaBlogBody">
						<?php echo $Discussion->FormatBody; ?>
					</div>
				</li>
				<?php if ($end) { ?>
					<li>
						<a href="<?php echo "/discussion/".$Discussion->DiscussionID."/".Gdn_Format::Url($Discussion->Name)?>"
						   class="NillaBlogMore"><?php echo T("Read more");?></a>
					</li> 
				<?php } ?>
			</ul>
		<?php 
	}
	
	public function CategoriesController_Render_Before(&$Sender) {
		if ($Sender->CategoryID != C("Plugins.NillaBlog.CategoryID"))
			return;

		$Sender->AddCssFile($this->GetResource('design/custom.css', FALSE, FALSE));
	}
	
	public function DiscussionController_Render_Before(&$Sender) {
		if ($Sender->CategoryID != C("Plugins.NillaBlog.CategoryID"))
			return;

		$Sender->AddCssFile($this->GetResource('design/custom.css', FALSE, FALSE));
	}
	
	public function Setup() {

	}
}