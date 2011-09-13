<?php if (!defined('APPLICATION')) exit();


?>
<div id="Custom">

   <div class="Heading">
      <h1>This is where item details can be edited.</h1>
   </div>
	<div class="FormWrapper">
		<?php
		echo $this->Form->Open();
		echo $this->Form->Errors();
		echo '<ul><li class="Form">';
        echo $this->Form->Label('Item Name', 'Item Name');
        echo $this->Form->TextBox('Name');

		echo '</li><li class="Form">';
		echo $this->Form->Label('Base Base Price', 'BaseBasePrice');
        echo $this->Form->TextBox('BaseBasePrice');
		echo '</li><li class="Form">';
		echo $this->Form->Label('Base Lid Price', 'Base Lid Price');
        echo $this->Form->TextBox('BaseLidPrice');
		echo '</li><li class="Form">';
		echo $this->Form->Label('License', 'License');
        echo $this->Form->TextBox('License');
		echo '</li><li class="Form">';
		echo $this->Form->Label('Artist', 'Artist');
        echo $this->Form->TextBox('Artist');
		echo '</li><li class="Form">';
		echo $this->Form->Label('Width', 'Width');
        echo $this->Form->TextBox('Width');
		echo '</li><li class="Form">';
		echo $this->Form->Label('Description', 'Description');
        echo $this->Form->TextBox('Description', array('multiline' => TRUE));
		echo '</li></ul>';

		echo $this->Form->Close('Save');
	?></div>
</div>