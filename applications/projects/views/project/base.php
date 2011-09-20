<?php if (!defined('APPLICATION'))
	exit();


$Selection = $this->MyExplode($this->CurrentProject->Selected);
$Tin = $this->GalleryItemModel->GetWhere(array('Slug' => $Selection['bases']))->FirstRow();
	if (!empty($Tin)) {
		?><table><tr>
			<th class="Selected">Selected Tin:</th>
		</tr><tr><?
		if (!empty($Tin->Name)) {
			echo '<th><a href="/item/'.$Tin->Slug.'">'.$Tin->Name.'</a></th>';
		} else {
			echo '<th><a href="/item/'.$Tin->Slug.'">'.$Tin->Slug.'</a></th>';
		}
		?></tr><tr><?
		echo '<td align="Center" class="Background">';
		echo '<img src="/uploads/item/bases/'.$Tin->Slug.'S.jpg"></img>';
		echo '</td>';
		echo '</tr><tr>';
		echo '<td>';
		echo '<button type="button" id="TinRemove" class="Button TinRemove" itemtype="bases" itemslug="'.$Tin->Slug.'">Remove Tin</button>';
		echo '</td>';
	} else {
		?><tr><th>No Tin Selected</th><tr>
		<tr><td><a href="/gallery/bases/" class="Button">Choose Tin</a></td></tr>
		</table><?
	}