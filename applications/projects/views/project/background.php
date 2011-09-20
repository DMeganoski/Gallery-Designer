<?php if (!defined('APPLICATION'))
	exit();

/*--------------- Start Selected Items --------------------*/
				$Selection = $this->MyExplode($this->CurrentProject->Selected);
				if (!empty($Selection)) {

		/*------------------------ Start Background Selection -------------------------------*/
						$Background = $this->GalleryItemModel->GetWhere(array('Slug' => $Selection['backgrounds']))->FirstRow();
						if (!empty($Background)) {
						?><table><tr>
						<th colspan="2" class="Selected">Selected Background:</th>
						</tr><tr><?
							if (!empty($Background->Name))
								echo '<th><a href="/item/'.$Background->Slug.'">'.$Background->Name.'</a></th>';
							else
								echo '<th><a href="/item/'.$Background->Slug.'">'.$Background->Slug.'</a></th>';
							$Frame = $Selection['frame'];

						?></tr><tr>
							<td align="Center" class="Background">
							<div class=ProjectWrapper><?
							if (!empty($Frame)) {
								echo '<img src="/uploads/item/frames/'.strtolower($Frame).'.png" class="FrameSmall">';
							}
							echo '<img src="/uploads/item/backgrounds/'.$Background->Slug.'S.jpg"></img>';
							echo '</td></tr><tr><td>';
							echo '<button type="button" id="BackgroundRemove" class="Button BackgroundRemove" itemtype="backgrounds" itemslug="'.$Background->Slug.'">Remove Background</button>';
							echo '</td>';

							if (!empty($Frame)) {
								echo '</tr>';
								echo '<th>Selected Frame:</th>';
								echo '</tr><tr>';
								echo '<th>'.$Frame.'</th>';
							}
							echo '</tr><tr>';
							echo '<td>';
							if (!empty($Frame))
							echo '<button type="button" id="FrameRemove" class="Button FrameRemove" itemtype="frame" itemslug="'.$Frame.'">Remove Frame</button>';

							echo '</td>';
							echo '</div>';
						} else {
							echo '<table>';
							echo "<tr><th>No Background Selected</th><tr>";
							echo '<tr><td><a href="/gallery/backgrounds/" class="Button">Choose Background</a></td></tr>';
							echo '</table>';
						}
			} else {
				echo '<tr><th>Nothing Selected</th></tr>';
			}
			?><div class="ClearFix"></div>
			</tr></table><?

