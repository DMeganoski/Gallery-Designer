<?php if (!defined('APPLICATION'))
	exit(); ?>
<div id="Custom">
	<div class="Heading">
		<h1>All projects across the web site</h1>
	</div>
		<? foreach ($this->Projects as $Project) {

			echo '<div id="Project">';
			echo '<h2>'.$Project->User->Name.': </h2><h1>'.$Project->ProjectName.'</h1>';
			$Selection = $this->MyExplode($Project->Selected);
				if (!empty($Selection)) {
					$Tin = $this->GalleryItemModel->GetWhere(array('Slug' => $Selection['tins']))->FirstRow();
						if (!empty($Tin)) {
							echo '<div>';
							if (!empty($Tin->Name))
								echo '<h2 colspan="2">Selected Tin:  <a href="/item/'.$Tin->Slug.'">'.$Tin->Name.'</a></h2>';
							else
								echo '<h2 colspan="2">Selected Tin :  <a href="/item/'.$Tin->Slug.'">'.$Tin->Slug.'</a></h2>';
						echo '</div><div>';
							echo '<td align="Center" class="Background">';
							echo '<img src="/uploads/item/tins/'.$Tin->Slug.'M.jpg"></img>';
							echo '</td>';
						}

						$Background = $this->GalleryItemModel->GetWhere(array('Slug' => $Selection['covers']))->FirstRow();
						if (!empty($Background)) {


						echo '<div>';
							if (!empty($Background->Name))
								echo '<h2>Selected Background:  <a href="/item/'.$Background->Slug.'">'.$Background->Name.'</a></h2>';
							else
								echo '<h2>Selected Background :  <a href="/item/'.$Background->Slug.'">'.$Background->Slug.'</a></h2>';
							echo '<h2>Selected Frame: '.$Frame[0].'</h2>';
						echo '</div><div>';
							echo '<td align="Center" class="Background">';
							if (!empty($Frame)) {
								echo '<img src="/uploads/item/frames/'.strtolower($Frame[0]).'.png" class="FrameSmall"></div>';
							}
							echo '<img src="/uploads/item/covers/'.$Background->Slug.'M.jpg"></img>';
							echo '</td>';
						}

			$IncludedUploads = $this->MyExplode($CurrentProject->Included);
			foreach ($IncludedUploads as $Upload) {
				$UploadData = $this->GalleryUploadModel->GetWhere(array('UploadKey' => $Upload))->FirstRow();
				if (!empty($UploadData))
					$TopPositions = $this->MyExplode($CurrentProject->TopPositions);
					$TopPosition = $TopPositions[$UploadData->FileName];
					$LeftPositions = $this->MyExplode($CurrentProject->LeftPositions);
					$LeftPosition = $LeftPositions[$UploadData->FileName];
					$this->UploadList[$UploadData->FileName] = array('top' => $TopPosition, 'left' => $LeftPosition);
			}
			foreach ($this->UploadList as $Upload => $Positions) {
				$FileParts = pathinfo($Upload);
				$BaseName = $FileParts['filename'];?>
						<img src="/uploads/<? echo $BaseName ?>-Thumb.jpg" class="Upload Draggable Individual" id="<? echo $Upload ?>"
							 projectid="<? echo $this->CurrentProject->ProjectKey ?>" style="<? echo 'top: '.$Positions['top'].'; left: '.$Positions['left'] ?>"></img>
					<? }
			echo '</td></tr></table>';
			echo '</div>';
		} else {
			echo "Must Be Signed in to start a project";
		}
			echo '</div>';
		}
		?>
	</div>