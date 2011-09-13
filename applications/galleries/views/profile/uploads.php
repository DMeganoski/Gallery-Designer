<?php if (!defined('APPLICATION'))
	exit(); ?>
<div id="Custom">
	<div class="Heading">
		<h2>My Personal Uploads</h2>
	</div>
	<div class="Uploads">
		<?php
		$Session = Gdn::Session();
		echo '<ul id="Uploads" class="Datalist">';
		$HTML = '';
		foreach ($this->Uploads as $File) {
			$FileName = $File->FileName;
			$FileParts = pathinfo($FileName);
			$BaseName = $FileParts['filename'];
			$HTML .= '<li class="Item Upload">';
			$HTML .= '<div class="Image"><img src="/uploads/'.$BaseName.'-Thumb.jpg" class="Thumb"></img></div>';
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
