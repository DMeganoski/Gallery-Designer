<?php if (!defined('APPLICATION'))
	exit();

?><div id="Custom">
	<div class="Heading">
		<h1>All My Projects</h1>
		<h2>Start a new Project</h2>
	</div>
</div>
<?
echo $this->Form->Open();
echo $this->Form->ShowErrors();

echo $this->Form->Label('Project Name', 'ProjectName');
echo $this->Form->Textbox('ProjectName');

echo $this->Form->Close('Save');

foreach ($this->Projects as $Project) {
	if ($Project->CurrentProject == 1) {
		$Css = 'Active';
	} else {
		$Css = 'Inactive';
	}
	echo '<ul class="Project '.$Css.'" projectid="'.$Project->ProjectKey.'">';
	echo '<li><h1>'.$Project->ProjectName.'</h1></li>';


	$Backgrounds = $this->MyExplode($Project->Selected);
	echo '<li class="Float"><h2>Background</h2>';
	foreach ($Backgrounds as $Selection) {
		echo '<img src="/uploads/item/covers/'.$Selection.'M.jpg"></img>';
		echo $Type;
	}
	echo '</li>';
	echo '<li class="Float"><h2>Tin</h2>';
	foreach ($this->MyExplode($Project->Included) as $Tin) {
		echo '<img src="/uploads/item/tins/'.$Tin.'M.jpg"></img>';
	}
	echo '</li>';
	echo '<li><a href="/project/'.$Project->ProjectKey.'" class="Button">Click Here</a>';
	echo '<input projectid="'.$Project->ProjectKey.'" class="Current Button" type="button" value="Make Current"/>';
	echo '<a href="/project/delete/'.$Project->ProjectKey.'" class="Button">Delete</a></li>';
	echo '<div class="ClearFix"></div>';
echo '</ul>';

}