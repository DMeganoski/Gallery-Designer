<?php if (!defined('APPLICATION')) exit();
$ActiveClass = GalleryController::$Class;
?>
<div class="Tabs">
    <ul>
        <?php
        foreach ($this->Classes as $Class) {
            $Label = ($Class->ClassLabel);
            if ($Class->Visible == '1') {
				if ($Label == $ActiveClass) {
					$CSS = 'Active';
				} else {
					$CSS = 'Depth';
				}
			echo '<li'.($this->RequestMethod == '' ? ' class="Gallery Images '.$CSS.'"' : '').'>'
					.Anchor(T($Label), 'gallery'.DS.$Label, 'TabButton Home');
			}
			?><ul class="Sublist"><?
			$Categories = $this->GetCategories($Label);
			foreach ($Categories as $Category) {
				echo '<li class="Gallery Images">'.Anchor(T($Category->CategoryLabel), 'gallery'.DS.$Label.DS.$Category->CategoryLabel).'</li>';
			}
			echo '</ul></li>';
		}
        ?>
    </ul>
</div>

