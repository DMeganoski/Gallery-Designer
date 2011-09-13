<?php if (!defined('APPLICATION')) exit();
$Classes = $this->GetClasses();
$ActiveClass = GalleriesPlugin::$Class;
?>
<div class="Tabs">
    <ul>
        <?php
        foreach ($Classes as $Class) {
            $Label = $Class->ClassLabel;
            if ($Class->Visible == '1') {
		if ($Label == $ActiveClass) {
		    $CSS = 'Active';
		} else {
		    $CSS = 'Depth';
		}
	    echo '<li'.($Sender->RequestMethod == '' ? ' class="Gallery Images '.$CSS.'"' : '').'>'
			.Anchor(T($Label), 'plugin/gallery'.DS.$Label, 'TabButton Home')
		.'</li>';
        }}
        ?>
    </ul>
</div>

