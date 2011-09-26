<?php if (!defined('APPLICATION'))
	exit();
if (!empty($this->Frame)) {
	$FrameClass = $this->Frame.'Frame';
} else {
	$FrameClass = 'None';
}
?>


<div id="Custom">

	<div class="Heading"><?
	if ($this->SignedIn) {
		?><h1>Resize and Crop Your Image Below</h1>
	</div>
	<div id="ResizeBox">
		<div id="ImageBase" file="<? echo '/uploads/item/tins'.$this->CurrentItem->FileName ?>" itemType="tin"><img src="/uploads/item/<? echo $this->CurrentItem->ClassLabel.DS.$this->CurrentItem->FileName ?>" class="Tin Static" id="Base"></img></div>
		</div><?
	} else {
		?><h1>You must be signed in to start a project</h1></div><?
	}
	?></div>
