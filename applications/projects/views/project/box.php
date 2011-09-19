<?php if (!defined('APPLICATION'))
	exit();

$CurrentProject = $this->CurrentProject;
			if ($this->CurrentProject === FALSE) {
				echo 'No Project Currently Selected. Would you like to start a new one or select an existing one?';
			}
			echo '<div id="ProjectItems">';
			echo '<div class="Heading">';
				echo '<table align="Center">';
				echo '<tr class="Heading">';
				echo '<th><h2>My Current Project:  '.$this->CurrentProject->ProjectName.'<h2></th>';
				echo '</tr>';
				// Start Selected Items
				$Selection = $this->MyExplode($this->CurrentProject->Selected);
				if (!empty($Selection)) {
					// Start Tin Selection
					$Tin = $this->GalleryItemModel->GetWhere(array('Slug' => $Selection['tins']))->FirstRow();
						if (!empty($Tin)) {
							echo '<tr>';
							echo '<th>Selected Tin:</th>';
							echo '</tr><tr>';
							if (!empty($Tin->Name)) {
								echo '<th><a href="/item/'.$Tin->Slug.'">'.$Tin->Name.'</a></th>';
							} else {
								echo '<th><a href="/item/'.$Tin->Slug.'">'.$Tin->Slug.'</a></th>';
							}
						echo '</tr><tr>';
							echo '<td align="Center" class="Background">';
							echo '<img src="/uploads/item/tins/'.$Tin->Slug.'S.jpg"></img>';
							echo '</td>';
							echo '</tr><tr>';
							echo '<td>';
							echo '<button type="button" id="TinRemove" class="Button TinRemove" itemtype="tins" itemslug="'.$Tin->Slug.'">Remove Tin</button>';
							echo '</td>';
						} else {
							echo "<tr><th>No Tin Selected</th><tr>";
							echo '<tr><td><a href="/gallery/tins/" class="Button">Choose Tin</a></td></tr>';
						}
						// Start CoverSelection
						$Background = $this->GalleryItemModel->GetWhere(array('Slug' => $Selection['covers']))->FirstRow();
						if (!empty($Background)) {
						echo '<tr>';
						echo '<th colspan="2">Selected Background:</th>';
						echo '</tr><tr>';
							if (!empty($Background->Name))
								echo '<th><a href="/item/'.$Background->Slug.'">'.$Background->Name.'</a></th>';
							else
								echo '<th><a href="/item/'.$Background->Slug.'">'.$Background->Slug.'</a></th>';
							$Frame = $Selection['frame'];
							if (!empty($Frame)) {
								echo '</tr>';
								echo '<th>Selected Frame:</th>';
								echo '</tr><tr>';
								echo '<th>'.$Frame.'</th>';
							}
						echo '</tr><tr>';
							echo '<td align="Center" class="Background">';
							echo '<div class=ProjectWrapper>';
							if (!empty($Frame)) {
								echo '<img src="/uploads/item/frames/'.strtolower($Frame).'.png" class="FrameSmall">';
							}
							echo '<img src="/uploads/item/covers/'.$Background->Slug.'S.jpg"></img>';
							echo '</td>';
							echo '</tr><tr>';
							echo '<td>';
							if (!empty($Frame))
							echo '<button type="button" id="FrameRemove" class="Button FrameRemove" itemtype="frame" itemslug="'.$Frame.'">Remove Frame</button>';
							echo '<button type="button" id="CoverRemove" class="Button CoverRemove" itemtype="covers" itemslug="'.$Background->Slug.'">Remove Background</button>';
							echo '</td>';
							echo '</div>';
						} else {
							echo "<tr><th>No Background Selected</th><tr>";
							echo '<tr><td><a href="/gallery/covers/" class="Button">Choose Background</a></td></tr>';
						}
			} else {
				echo '<tr><th>Nothing Selected</th></tr>';
			}
			echo '<div class="ClearFix"></div>';
			echo '</tr>';
			// Start Uploaded Images
			$IncludedUploads = $this->MyExplode($CurrentProject->Included);
			if (is_array($IncludedUploads)) {
				echo '<tr>';
				echo '<th colspan="2">Included Uploaded Images</th>';
				echo '</tr><tr><td>';

				foreach ($IncludedUploads as $Upload) {
					$UploadData = $this->GalleryUploadModel->GetWhere(array('UploadKey' => $Upload))->FirstRow();
					if (!empty($UploadData)) {
						$TopPositions = $this->MyExplode($CurrentProject->TopPositions);
						$TopPosition = $TopPositions[$UploadData->FileName];
						$LeftPositions = $this->MyExplode($CurrentProject->LeftPositions);
						$LeftPosition = $LeftPositions[$UploadData->FileName];
						$this->UploadList[$UploadData->FileName] = array('top' => $TopPosition, 'left' => $LeftPosition);
					}
				}
				foreach ($this->UploadList as $Upload => $Positions) {
					if (!empty($UploadData)) {
					$FileParts = pathinfo($Upload);
					$BaseName = $FileParts['filename'];?>
							<div id="UploadWrapper">
							<img src="/uploads/<? echo $BaseName ?>-Thumb.jpg" class="Upload" style="<? echo 'top: '.$Positions['top'].'; left: '.$Positions['left'] ?>"></img>
							<button type="button" id="<? echo $Upload ?>" class="UploadRemove Button" projectid="<? echo $this->CurrentProject->ProjectKey ?>">Remove</button>
							</div>
					<? }
				}
				// Start Message Display
				if (!empty($CurrentProject->Message)) {
					?>
					<div class="MessageDisplay">
						<h2>Included Message:</h2>
					<? echo $CurrentProject->Message;
					?></div><a href="/designer/text" class="Button">Edit</a><?
				} else {
					echo '<a href="/designer/text" class="Button">Add a Message</a>';
				}
				echo '</td></tr></table>';
				echo '</div>';
				}
