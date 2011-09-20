<?php if (!defined('APPLICATION'))
	exit();
if (!empty($this->Frame)) {
	$FrameClass = $this->Frame.'Frame';
} else {
	$FrameClass = 'None';
}
$CurrentSeleciton = $this->MyExplode($this->CurrentProject->Selection);
$TinSize = $this->TinFile->Name;
?>


<div id="Custom">

	<div class="Heading">
		<h1>Welcome to the Interactive Tin Designer</h1>
		<p>You can use items you have selected or uploaded to design your tin.</p>
		<p>Note: the backgrounds are approximately 3000 x 3000 px images, your images will
		appear smaller to retain definition</p>
	</div>
	<div class="Zoom"><div class="Slider"></div></div>
	<div id="DesignBox"><?
			if (!empty($this->BackgroundFile) && !empty($this->TinFile)) {
				?><img src="/uploads/item/borders/<? echo $TinSize ?>.png" class="Border"></img><?
			}
			?><div id="FrameWrapper" class="<? echo $FrameClass ?>" itemType="frame"></div><div class="ClearFix"></div>
			<img src="/uploads/item/backgrounds/<? echo $this->BackgroundFile->FileName ?>" class="Background Large" id="Background"></img>
			<div class="ClearFix"></div>

					<? $Count = 0;
					foreach ($this->UploadList as $Upload => $Positions) {
						$Count = $Count + 1; ?>
						<img src="/uploads/<? echo $Upload ?>" class="Upload Draggable Individual" id="<? echo $Upload ?>"
							 projectid="<? echo $this->CurrentProject->ProjectKey ?>" style="<? echo 'top: '.$Positions['top'].'; left: '.$Positions['left'] ?>"></img>
					<? }
			if (!empty($this->CurrentProject->Message)) {
				echo '<img src="/uploads/project/text/'.$this->CurrentProject->ProjectKey.'.png" id="Text" class="Text Draggable Individual" style="top: '.$this->MessagePosition['top'].'; left: '.$this->MessagePosition['left'].'" projectid="'.$this->CurrentProject->ProjectKey.'"/>';
			}
	?></div><div id="NoticeBox"></div>
	<div class="MessageDisplay">
		<h1>Included Message:</h1><?
		if (!empty($this->CurrentProject->Message)) {
		echo '<img src="/uploads/project/text/'.$this->CurrentProject->ProjectKey.'.png" class=""/>';
		} ?></div>

	<div class="Buttons">
		<button type="button" id="SubmitProjectCheck" class="SubmitProjectCheck Button" projectstage="<? echo $this->ProjectStage ?>" projectid="<? echo $this->CurrentProject->ProjectKey ?>">Submit project for review and processing</button>
	</div>
</div>