<?php if (!defined('APPLICATION'))
	exit();
?>
<div id="ProjectBoxWrapper">
	<div class="Box BoxUploads">
			<h4 id="ToggleUploads" class="Show Toggle">My Uploads</h4>
			<div class="UploadNotify"></div><?
			if ($Session->IsValid()) {
			if ($Session->CheckPermission('Gallery.Items.Upload'))
				echo Anchor(T('Upload Image'), '/item/upload', 'BigButton');
			}
			?><div class="ClearFix"></div>
			<div class="UploadBox" userid="<? echo $this->UserID ?>"></div>
	</div>
</div>
<div id="ProjectBoxWrapper">
	<div class ="Box Project">
			<h4 id="ToggleProject" class="Show Toggle">Project Box</h4>
			<div class="ProjectNotify"></div>

			<div class="ClearFix"></div>
			<div class="ButtonBox">
				<div class="Heading">
					<h2>My Current Project:  <? echo $this->CurrentProject->ProjectName ?></h2>
				</div>
				<div class="TabWrapper">
					<div id="Tin" class="TabBox">Tin</div>
					<div id="Background" class="TabBox">Background</div>
					<div id="Uploads" class="TabBox">Uploads</div>
					<div id="Text" class="TabBox">Text</div>
					<div class="ClearFix"></div>
				</div>
				<a href="/designer" id="ViewDesigner" class="BigButton View">Go To Designer</a>
			</div>
			<div class="ProjectBox" userid="<? echo $this->UserID ?>" projectid ="<? echo $this->CurrentProject->ProjectKey ?>"></div>
			<a href="/project" id="ViewProjects" class="BigButton View">View All Projects</a>
	</div>
</div>