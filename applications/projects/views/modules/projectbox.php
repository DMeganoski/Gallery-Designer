<?php if (!defined('APPLICATION'))
	exit();
?>
<div id="ProjectBoxWrapper">
<div class="Tabs">
<div id="Project">
	<ul>
		<li class="TabButton"><a href="#" id="ToggleUploads" class="TabButton Show">Show Uploads</a></li>
		<li class="TabButton"><a href="#" id="ToggleProject" class="TabButton Show">Show Project</a></li>
		<li class="TabButton"><a href="/project" id="ViewProjects" class="TabButton View">View All Projects</a></li>
		<li class="TabButton"><a href="/designer" id="ViewDesigner" class="TabButton View">Go To Designer</a></li>
	</ul>
	<div class="ClearFix"></div>
</div>
	<div class="ProjectBox" userid="<? echo $this->UserID ?>" projectid ="<? echo $this->CurrentProject->ProjectKey ?>"></div>
</div>
</div>