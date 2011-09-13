<?php if (!defined('APPLICATION'))
	exit(); ?>

<h2>This is the random number generator</h2>

<?php	echo $this->Form->Open();
		echo $this->Form->Errors();
		echo $this->Form->Textbox('GalleryItem');
		echo $this->Form->Close('Save');