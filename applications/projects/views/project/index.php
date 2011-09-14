<?php if (!defined('APPLICATION'))
	exit();

?><div id="Custom">
	<div class="Heading">
		<h1>All My Projects</h1>
		<h2>Start a new Project</h2>
	</div>
</div>
<?
echo $this->Form->Open();
echo $this->Form->ShowErrors();

echo $this->Form->Label('Project Name', 'ProjectName');
echo $this->Form->Textbox('ProjectName');

echo $this->Form->Close('Save');

foreach ($this->Projects as $Project) {
	if ($Project->CurrentProject == 1) {
		$Css = 'Active';
	} else {
		$Css = 'Inactive';
	}
	?><div id="ProjectPage"  class="<? echo $Css ?>">
			<div class="Heading">
				<h1>Project Name: <? echo $Project->ProjectName ?></h1>
				<h1>
					<button type="button" id="ProjectCurrent" class="ProjectCurrent Button" projectid="<? echo $Project->ProjectKey ?>">Set Project as Current</button>
					<button type="button" id="ProjectDelete" class="ProjectDelete Button" projectid="<? echo $Project->ProjectKey ?>">Delete Project</button>
				</h1>
			</div><?
				$Selection = $this->MyExplode($Project->Selected);
				if (!empty($Selection)) {
					$Tin = $this->GalleryItemModel->GetWhere(array('Slug' => $Selection['tins']))->FirstRow();
					if (!empty($Tin)) {
							echo '<table>';
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
							echo '<img src="/uploads/item/tins/'.$Tin->Slug.'M.jpg"></img>';
							echo '</td>';
							echo '</tr><tr>';
							echo '<td>';
							echo '<button type="button" id="TinRemove" class="Button TinRemove" itemtype="tins" itemslug="'.$Tin->Slug.'">Remove Tin</button>';
							echo '</td>';
							echo '</table>';
						} else {
							echo 'No Tin Selected';
						}

						$Background = $this->GalleryItemModel->GetWhere(array('Slug' => $Selection['covers']))->FirstRow();
						if (!empty($Background)) {
						echo '<table>';
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
							echo '</table>';
						} else {
							echo "<table><tr><th>No Background Selected</th></tr></table>";
						}
			}
			echo '<div class="ClearFix"></div>';
			echo '</tr>';
			$IncludedUploads = $this->MyExplode($Project->Included);

			if (is_array($IncludedUploads)) {
				echo '<tr>';
				echo '<th colspan="2">Included Uploaded Images</th>';
				echo '</tr><tr><td>';

				foreach ($IncludedUploads as $Upload) {
					$UploadData = $this->GalleryUploadModel->GetWhere(array('UploadKey' => $Upload))->FirstRow();
					if (!empty($UploadData)) {
						$TopPositions = $this->MyExplode($Project->TopPositions);
						$TopPosition = $TopPositions[$UploadData->FileName];
						$LeftPositions = $this->MyExplode($Project->LeftPositions);
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
							<button type="button" id="<? echo $Upload ?>" class="UploadRemove Button" projectid="<? echo $this->Project->ProjectKey ?>">Remove</button>
							</div>
					<? }
				}
				echo '</td></tr></table>';
				echo '</div>';
			}
		}