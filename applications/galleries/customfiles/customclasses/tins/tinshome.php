<?php if (!defined('APPLICATION')) exit();
include(PATH_APPLICATIONS.DS.'galleries/views/helper/helper.php');
// This is the Tin Home Page
$Categories = $this->GetCategories($ActiveClass);
?>
<div id="Custom">
   <div class="Heading">
      <h1>Choose your tin shape, size, and color below</h1>
      <p>We unfortunately only have round tins available at this time</p>
   </div>
<?php
    ?><div class="Home Gallery Tins">
        <?php
        foreach ($Categories as $Category) {
				$Label = $Category->CategoryLabel;
            if ($Label != $ActiveCategory && $Category->Visible == '1') {
				$CategoryKey = $Category->CategoryKey;
            echo '<div'.($Sender->RequestMethod == '' ? ' class="Image Category Tins"' : '').'>';
	    echo '<a href="/gallery/'.$ActiveClass.DS.$Label.'">';
	    echo '<img src="'.$PublicDir.$ActiveClass.DS.'categories'.DS.$Label.'.jpg" class="Gallery Category Tins"></img>';
	    echo '</a>';
	    echo '<span class="Count">'.$this->GalleryItemModel->GetCount(array('CategoryKey' => $CategoryKey)).' Total</span>';
	    echo '<table>';
	    echo '<tr>';
	    echo '<th>'.T($Label).' Finish</th>';
	    echo '</tr>';
	    echo '<tr>';
	    echo '<td class="Size">Sizes Available:</td>';
	    echo '</tr>';
	    $Items = $this->GalleryItemModel->Get(0,0, array('CategoryKey' => $CategoryKey));
	    foreach ($Items as $FileInfo) {
	    echo '<tr><td><a href="/item/'.$FileInfo->Slug.'">'.$FileInfo->Name.'</a></td></tr>';
	    }
	    echo '</table>';
	    echo '</div>';
        }}?>
       <div class="PriceLink">
	  <a href="/gallery/default/pricing" class="BigButton">Price Guide</a>
       </div>
    </div>
</div>