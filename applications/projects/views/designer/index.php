<?php if (!defined('APPLICATION'))
	exit();
if (!empty($this->Frame)) {
	$FrameClass = $this->Frame.'Frame';
} else {
	$FrameClass = 'None';
}
$TinSize = '5C';
?>


<div id="Custom">

	<div class="Heading">
		<h1>Welcome to the Interactive Tin Designer</h1>
		<p>You can use items you have selected or uploaded to design your tin.</p>
		<p>Note: the backgrounds are approximately 3000 x 3000 px images, your images will
		appear smaller to retain definition</p>
	</div>
	<div id="DesignBox"><?
			if (!empty($this->BackgroundFile)) {
				?><img src="/uploads/item/borders/<? echo $TinSize ?>.png" class="Border"></img><?
			}
			?><div id="FrameWrapper" class="<? echo $FrameClass ?>" itemType="frame"></div><div class="ClearFix"></div>
			<img src="/uploads/item/covers/<? echo $this->BackgroundFile->FileName ?>" class="Background Large" id="Background"></img>
			<div class="ClearFix"></div>

					<? $Count = 0;
					foreach ($this->UploadList as $Upload => $Positions) {
						$Count = $Count + 1; ?>
						<img src="/uploads/<? echo $Upload ?>" class="Upload Draggable Individual" id="<? echo $Upload ?>"
							 projectid="<? echo $this->CurrentProject->ProjectKey ?>" style="<? echo 'top: '.$Positions['top'].'; left: '.$Positions['left'] ?>"></img>
					<? }
			if (!empty($CurrentProject->Message)) {

			}
			if (!empty($this->BackgroundFile)) {
				?><img src="/uploads/item/borders/<? echo $TinSize ?>.png" class="Border Draggable"></img><?
			}
	?></div>
	<div class="MessageDisplay">
		<h1>Included Message:</h1>
		<? echo $this->CurrentProject->Message ?>
	</div>
	<div id="NoticeBox"></div>
	<div class="Buttons">
		<button type="button" id="SubmitProjectCheck" class="SubmitProjectCheck Button" projectstage="<? echo $this->ProjectStage ?>" projectid="<? echo $this->CurrentProject->ProjectKey ?>">Submit project for review and processing</button>
	</div>
</div>