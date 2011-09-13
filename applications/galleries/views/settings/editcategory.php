<?php if (!defined('APPLICATION')) exit();

echo $this->Form->Open();
echo $this->Form->Errors();
?>
<h1><?php echo T('Edit Category'); ?></h1>
<ul>
   <li>
      <?php
         echo $this->Form->Label('Category Label', 'CategoryLabel');
         echo $this->Form->TextBox('CategoryLabel');
      ?>
   </li>
</ul>
<?php echo $this->Form->Close('Save'); ?>