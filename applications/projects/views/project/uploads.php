<?php if (!defined('APPLICATION'))
	exit();

/*--------------------------- Start Uploaded Images ----------------------------*/
$IncludedUploads = $this->MyExplode($CurrentProject->Included);
$Count = count($IncludedUploads);
	if ($Count > 1) {
		$Plural = 's';
	} else {
		$Plural = '';
	}
if ($IncludedUploads[0] != '') {
	?><table><tr>
		<th>Included Uploaded Images</th>
	</tr><tr>
		<th class="Grey"><? echo $Count; ?> Image<? echo $Plural ?> Included</th>
	</tr><tr>
		<td><?
		foreach ($IncludedUploads as $Upload) {
			$UploadData = $this->GalleryUploadModel->GetWhere(array('UploadKey' => $Upload))->FirstRow();
			if (is_object($UploadData)) {
			$FileParts = pathinfo($UploadData->FileName);
			$BaseName = $FileParts['filename'];
				?><div id="UploadWrapper">
					<img src="/uploads/<? echo $BaseName ?>-Thumb.jpg" class="Upload"></img>
					<button type="button" id="<? echo $UploadData->FileName ?>" class="UploadRemove Button" projectid="<? echo $this->CurrentProject->ProjectKey ?>">Remove</button>
				</div><?
			} else {
				?><script type="text/javascript">

						$(this).doProjectRemove('uploads',<? echo $UploadData->FileName ?>,<? echo $this->CurrentProject->ProjectKey ?>);

				</script><?
			}
		}
	?></td></table><?
} else {
	echo "<h2>No Uploads Used</h2>";
}