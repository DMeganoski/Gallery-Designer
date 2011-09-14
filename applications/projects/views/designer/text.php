<?php if (!defined('APPLICATION'))
	exit();


echo $this->Form->Open();
echo $this->Form->Errors();
echo $this->Form->Label('Message', 'Message');
echo $this->Form->TextBox('Message', array('multiline' => TRUE));
echo $this->Form->Close('Save');