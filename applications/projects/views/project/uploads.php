<?php if (!defined('APPLICATION'))
	exit();

/*--------------------------- Start Uploaded Images ----------------------------*/
$IncludedUploads = $this->MyExplode($CurrentProject->Included);
if (is_array($IncludedUploads)) {
	?><table><tr>
		<th>Included Uploaded Images</th>
	</tr><tr><?
	$Count = count($IncludedUploads);
	if ($Count > 1) {
		$Plural = 's';
	} else {
		$Plural = '';
	}
		?><th class="Grey"><? echo count($IncludedUploads); ?> Image<? echo $Plural ?> Included</th>
	</tr><tr>
		<td><?
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
			$BaseName = $FileParts['filename'];
				?><div id="UploadWrapper">
					<img src="/uploads/<? echo $BaseName ?>-Thumb.jpg" class="Upload" style="<? echo 'top: '.$Positions['top'].'; left: '.$Positions['left'] ?>"></img>
					<button type="button" id="<? echo $Upload ?>" class="UploadRemove Button" projectid="<? echo $this->CurrentProject->ProjectKey ?>">Remove</button>
				</div>
			<? }
		}
	?></table><?
}