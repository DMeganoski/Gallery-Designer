<?php if (!defined('APPLICATION')) exit();
$Session = Gdn::Session();
?>
<div class="Help Aside">
   <?php
   echo '<h2>', T('Need More Help?'), '</h2>';
   echo '<ul>';
   echo '<li>', Anchor(T('Managing Categories'), 'http://vanillaforums.org/docs/managecategories'), '</li>';
   echo '<li>', Anchor(T('Adding & Editing Categories'), 'http://vanillaforums.org/docs/managecategories#add'), '</li>';
   echo '</ul>';
   ?>
</div>
<h1><?php echo T('Manage Categories'); ?></h1>
<div class="Info">
   <?php echo T('Categories are used to help organize the gallery.', 'Categories are used to help organize the gallery. Click on a category to edit it.'); ?>
</div>
<div class="FilterMenu"><?php
      echo Anchor(T('Add Category'), 'vanilla/settings/addcategory', 'SmallButton');
?></div>
<?php
if (C('Vanilla.Categories.Use')) {
   ?>
   <div class="Help Aside">
      <?php
      echo '<h2>', T('Did You Know?'), '</h2>';
      echo '<ul>';
      echo '<li>', sprintf(T('You can make the categories page your homepage.', 'You can make your categories page your homepage <a href="%s">here</a>.'), Url('/dashboard/settings/homepage')), '</li>';
      echo '<li>', sprintf(T('Make sure you click View Page', 'Make sure you click <a href="%s">View Page</a> to see what your categories page looks like after saving.'), Url('/categories/all')), '</li>';
      echo '<li>', T('Drag and drop the categories below to sort and nest them.'), '</li>';
      echo '</ul>';
      ?>
   </div>
   <h1><?php
      echo T('Existing Categories');
   ?></h1>
   <?php
   echo $this->Form->Open();
   echo $this->Form->Errors();
   echo '<ol class="Sortable ui-sortable">';
   $CategoryData = $this->CategoryData;
   foreach ($CategoryData as $ClassKey => $Class) {
	   $ClassLabel = $Class['ClassLabel'];
	   if ($Class['Visible'] == '0') {
		   $CSS = 'Secret';
	   } else {
		   $CSS = 'Visible';
	   }
	   echo '<li><table class="Heading"><tr class="Heading"><td class ="'.$CSS.'"><h2>'.$ClassLabel.' ('.T($ClassLabel).')</h2></td></tr>';
	   echo '<tr><td>';
	   echo Anchor(T('Edit Class'), 'galleries/settings/editclass/'.$ClassLabel, 'Button');
	   echo '<blockquote>'.$Label.'</blockquote>';
	   echo '</td></tr></table><ol class="Category"><table class="Category">';
	   foreach($Class['Categories'] as $CategoryKey => $CategoryLabel) {
		   echo '<tr class="Category"><td class="Category">'.$CategoryLabel.' ('.T($CategoryLabel).')';
		   echo Anchor(T('Edit Category'), '/galleries/settings/editcategory/'.$ClassLabel.DS.$CategoryLabel, 'Button');
			echo '</td></tr>';
	   }
	   //echo '<li class="ClearFix"></li>';
		echo '</table></ol></li>';

   }
   echo '</ol>';

}