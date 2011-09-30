<?php if (!defined('APPLICATION'))
	exit();

$CurrentSeleciton = $this->MyExplode($this->CurrentProject->Selection);
// @todo the template can be based on the chosen base size, though the size issue needs resolved.
//$BaseSize = $this->BaseFile->Name;
$BaseSize = '5C';
?>


<div id="Custom">
	<div class="Heading"><?
	if ($this->SignedIn) {
		?><h1>Welcome to the Interactive <? echo T('base') ?> Designer</h1>
		<p>You can use items you have selected or uploaded to design your <? echo T('base') ?>.</p>
		<p>Note: the backgrounds are approximately 3000 x 3000 px images, and have been shrunk to fit the screen.
			Certain web browsers will display the shrunk images better, though the purpose is preserving print quality.</p>
	</div>
	<div id="DesignBox"><?
			if (!empty($this->BackgroundFile) && !empty($this->BaseFile)) {
				?><img src="/uploads/item/borders/<? echo $BaseSize ?>.png" class="Border"></img><?
			}
			if (!empty($this->Frame)) {
				?><img src="/uploads/item/frames/<? echo strtolower($this->Frame) ?>L.png"></img><?
				} ?><div class="ClearFix"></div>
			<img src="/uploads/item/backgrounds/<? echo $this->BackgroundFile->FileName ?>" class="Background Large" id="Background"></img>
			<div class="ClearFix"></div>

					<? $Count = 0;
					foreach ($this->UploadList as $Upload => $Positions) {
						$Count = $Count + 1; ?>
			<img src="/uploads/<? echo $Upload ?>" class="Upload Draggable Individual" id="<? echo $Upload ?>"
							 projectid="<? echo $this->CurrentProject->ProjectKey ?>" style="<? echo 'top: '.$Positions['top'].'; left: '.$Positions['left'] ?>"></img><div id="Remove">X</div>
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
	</div><?
	} else {
		?><h1>You must be signed in to start a project</h1></div><?
	}
?></div>