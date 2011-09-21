<?php if (!defined('APPLICATION'))
	exit();
echo '<div id="Color"></div>';
$Colors = array(
	'white' => T('white'),
	'black' => T('black'),
	'red' => T('red'),
	'blue' => T('blue'),
	'green' => T('green'));
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
$Lengths = array('10' => '10', '12' => '12', '14' => '14', '16' => '16', '18' => '18', '20' => '20', '22' => '22', '24' => '24', '26' => '26', '28' => '28', '30' => '30');

echo $this->Form->Open();
echo $this->Form->Errors();
echo '<table class="Font"><tr><td>';
echo $this->Form->Label('Message', 'Message');
echo '</td><td>';
echo $this->Form->TextBox('Message', array('multiline' => TRUE));
echo '</td></tr><tr><td>';
echo $this->Form->Label('Font Color', 'FontColor');
echo '</td><td>';
echo $this->Form->Dropdown('FontColor', $Colors);
echo '</td></tr><tr><td>';
echo $this->Form->Label('Font Size', 'FontSize');
echo '</td><td>';
echo $this->Form->Dropdown('FontSize', $FontSizes);
echo '</td></tr><tr><td>';
echo $this->Form->Label('Font Style', 'FontName');
echo '</td><td>';
echo $this->Form->Dropdown('FontName', $FontNames);
echo '</td></tr><tr><td>';
echo $this->Form->Label('Line Length', 'LineLength');
echo '<br/>';
echo "(Number of characters allowed in one line)";
echo '</td><td>';
echo $this->Form->DropDown('LineLength', $Lengths);
echo '</td></tr></table>';
echo $this->Form->Close('Save');
echo "<div class='Yellow'>";
echo "<img src='$this->TextImage' class='TextImage'/>";
echo '</div>';