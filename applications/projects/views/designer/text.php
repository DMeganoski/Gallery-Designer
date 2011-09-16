<?php if (!defined('APPLICATION'))
	exit();
echo '<div id="Color"></div>';
$Colors = array(
	'white' => 'white<div id="Color" class="White"></div>',
	'black' => 'black<div id="Color" class="Black"></div>',
	'red' => T('red'));
$FontSizes = array('80' => '80','100' => '100', '120' => '120', '140' => '140', '160' => '160');
$FontNames = array(
	'Big Lou' => 'Big Lou',
	'Billo Dream' => 'Billo Dream',
	'Christopherhand' => 'Christopherhand',
	'Electroharmonix' => 'Electroharmonix',
	'Florencesans' => 'Florencesans',
	'Ginga' => 'Ginga',
	'JaySetch' => 'JaySetch',
	'JFRingmaster' => 'JFRingmaster',
	'KILLED DJ' => 'KILLED DJ',
	'PaintyPaint' => 'PaintyPaint',
	'Ruritania' => 'Ruritania',
	'Saved By Zero' => 'Saved By Zero',
	'Seaside Resort' => 'Seaside Resort'
);
echo $this->Form->Open();
echo $this->Form->Errors();
echo $this->Form->Label('Message', 'Message');
echo $this->Form->TextBox('Message', array('multiline' => TRUE));
echo '<br/>';
echo $this->Form->Label('Font Color', 'FontColor');
echo $this->Form->Dropdown('FontColor', $Colors);
echo '<br/>';
echo $this->Form->Label('Font Size', 'FontSize');
echo $this->Form->Dropdown('FontSize', $FontSizes);
echo '<br/>';
echo $this->Form->Label('Font Style', 'FontName');
echo $this->Form->Dropdown('FontName', $FontNames);
echo '<br/>';
echo "Number of characters allowed in one line";
echo '<br/>';
echo $this->Form->Label('Line Length', 'LineLength');
echo $this->Form->TextBox('LineLength');
echo $this->Form->Close('Save');
echo "<div class='Yellow'></div>";
echo "<img src='$this->TextImage' class='TextImage'/>";