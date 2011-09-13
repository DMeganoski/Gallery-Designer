<?php if (!defined('APPLICATION'))
	exit(); ?>
<div id="Custom">
	<h1>Oops, The item you were looking for isn't here.</h1>
</div>
<?php echo $this->Alert;
	echo '<br/><a href="'.$this->Path.DS.$this->FileName.'">'.$this->Path.'</a>';
	echo '<br/>'.$this->FileName;