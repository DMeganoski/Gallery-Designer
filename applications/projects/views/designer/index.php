<?php if (!defined('APPLICATION'))
	exit();
if (!empty($this->Frame)) {
	$FrameClass = $this->Frame.'Frame';
} else {
	$FrameClass = 'None';
}
?>


<div id="Custom">

	<div class="Heading">
		<h1>Welcome to the Interactive Tin Designer</h1>
		<p>You can use items you have selected or uploaded to design your tin.</p>
	</div>
	<div id="DesignBox">
			<div id="FrameWrapper" class="<? echo $FrameClass ?>" itemType="frame"></div><div class="ClearFix"></div>
			<img src="/uploads/item/covers/<? echo $this->BackgroundFile->FileName ?>" class="Background Large" id="Background"></img>
			<div class="ClearFix"></div>

					<? $Count = 0;
					foreach ($this->UploadList as $Upload => $Positions) {
						$Count = $Count + 1; ?>
						<img src="/uploads/<? echo $Upload ?>" class="Upload Draggable Individual" id="<? echo $Upload ?>"
							 projectid="<? echo $this->CurrentProject->ProjectKey ?>" style="<? echo 'top: '.$Positions['top'].'; left: '.$Positions['left'] ?>"></img>
					<? } ?>
	</div>
	<div id="NoticeBox"></div>
	<div class="Buttons">
		<button type="button" id="SubmitProjectCheck" class="SubmitProjectCheck BigButton" projectstage="<? echo $this->ProjectStage ?>" projectid="<? echo $this->CurrentProject->ProjectKey ?>">Submit project for review and processing</button>
	</div>
</div>