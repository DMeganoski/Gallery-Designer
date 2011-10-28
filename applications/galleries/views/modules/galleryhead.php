<?php if (!defined('APPLICATION')) exit();
//$ActiveClass = GalleryController::$Class;
//$ActiveCategory = GalleryController::$Category;
?>
<div class="Tabs">
    <ul>
        <?php
        foreach ($this->Classes as $Class) {
            $Label = ($Class->ClassLabel);
            if ($Class->Visible == '1'  && $Label != 'designer') {
				if ($Label == $ActiveClass) {
					$CSS = 'Active';
				} else {
					$CSS = 'Depth';
				}
		if ($Label == 'default') {
			echo '<li'.($this->RequestMethod == '' ? ' class="Navigation Images '.$CSS.'"' : '').'>'
					.'<a href="/info/home" class="TabButton">'.T('default').'</a>';

			?><ul class="Sublist"><?
			$Categories = $this->GetCategories($Label);
			foreach ($Categories as $Category) {
				if ($Category->Visible == '1') {
					if ($Category->Label == $ActiveCategory) {
						$CatCSS = 'Active';
					} else {
						$CatCSS = 'Depth';
					}
					if ($Category->CategoryLabel != 'home' && $Label != 'designer')
					echo '<li>'.Anchor(T($Category->CategoryLabel), '/info'.DS.$Category->CategoryLabel).'</li>';
				}
			}
			?><li class="ClearFix"></li>
			</ul>
	</li><?
		} else {
			echo '<li'.($this->RequestMethod == '' ? ' class="Navigation Images '.$CSS.'"' : '').'>'
					.'<a href="/gallery/'.$Label.'" class="TabButton">'.T($Label).'</a>';

			?><ul class="Sublist"><?
			$Categories = $this->GetCategories($Label);
			foreach ($Categories as $Category) {
				if ($Category->Visible == '1') {
					if ($Category->Label == $ActiveCategory) {
						$CatCSS = 'Active';
					} else {
						$CatCSS = 'Depth';
					}
					if ($Category->CategoryLabel != 'home' && $Label != 'designer')
					echo '<li>'.Anchor(T($Category->CategoryLabel), 'gallery'.DS.$Label.DS.$Category->CategoryLabel).'</li>';
				}
			}
			?><li class="ClearFix"></li>
			</ul><?
		}}}
    ?><li>
		<a href="/designer"><? echo T('designer') ?></a>
		<ul class="Sublist">
			<li><a href="/designer/text/">Text</a></li>
		</ul>
	</li>
	</ul>
</div>

