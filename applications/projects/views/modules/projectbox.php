<?php if (!defined('APPLICATION'))
	exit();
?>
<div id="ProjectBoxWrapper">
	<div class="Box">
		<div id="Project">
			<ul>
				<li><a href="#" id="ToggleUploads" class="BigButton Show">Show Uploads</a></li>
				<li><a href="#" id="ToggleProject" class="BigButton Show">Show Project</a></li>

			</ul>
			<div class="ClearFix"></div>
		</div>
		<div class="ProjectBox" userid="<? echo $this->UserID ?>" projectid ="<? echo $this->CurrentProject->ProjectKey ?>"></div>
		<ul>
			<li class="Button"><a href="/project" id="ViewProjects" class="TabButton View">View All Projects</a></li>
			<li class="Button"><a href="/designer" id="ViewDesigner" class="TabButton View">Go To Designer</a></li>
		</ul>
	</div>
</div>