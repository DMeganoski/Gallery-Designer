<?php if (!defined('APPLICATION'))
	exit(); ?>
<?php if (!defined('APPLICATION'))
	exit(); ?>
<div id="Custom">
	<div class="Heading">
		<h2>My Personal Uploads</h2>
	</div>
	<div class="Uploads">
		<?php
		$Uploads = $this->Uploads;
		echo '<ul id="Uploads">';
		$HTML = '';
		foreach ($Uploads as $File) {
			$FileName = $File->FileName;
			$FileParts = pathinfo($FileName);
			$BaseName = $FileParts['filename'];
			$HTML .= '<li>';
			$HTML .= '<img src="/uploads/'.$BaseName.'-Thumb.jpg" class="Thumb"></img>';
			$HTML .= '<strong>'.$File->FileName.'</strong>';
			$HTML .= '</br>';
			$HTML .= $File->Description;
			$HTML .= '<div class="ClearFix"></div>';
			$HTML .= '</li>';

		}
		echo $HTML;
		echo '</ul>';

	?></div>
</div>
