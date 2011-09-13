<?php if (!defined('APPLICATION')) exit();
echo $this->Form->Open();
echo $this->Form->Errors();
?>
<h1><?php echo T('Advanced'); ?></h1>
<ul>
   <li>
      <?php
         $Options = array('4' => '4', '8' => '8', '12' => '12', '16' => '16', '20' => '20', '24' => '24', '28' => '28');
         $Fields = array('TextField' => 'Code', 'ValueField' => 'Code');
         echo $this->Form->Label('Images per Page', 'Galleries.Items.PerPage');
         echo $this->Form->DropDown('Galleries.Items.PerPage', $Options, $Fields);
      ?>
   </li><?php /* ?>
   <li>
      <?php
         $Options = array('0' => T('Authors cannot edit their posts'),
                        '350' => T('Authors can edit for 5 minutes after posting'),
                        '900' => T('Authors can edit for 15 minutes after posting'),
                       '1800' => T('Authors can edit for 30 minutes after posting'),
                      '86400' => T('Authors can edit for 1 day after posting'),
                     '604800' => T('Authors can edit for 1 week after posting'),
                    '2592000' => T('Authors can edit for 1 month after posting'),
                         '-1' => T('Authors can always edit their posts'));
         $Fields = array('TextField' => 'Text', 'ValueField' => 'Code');
         echo $this->Form->Label('Discussion & Comment Editing', 'Garden.EditContentTimeout');
         echo $this->Form->DropDown('Garden.EditContentTimeout', $Options, $Fields);
			echo Wrap(T('EditContentTimeout.Notes', 'Note: If a user is in a role that has permission to edit content, those permissions will override any value selected here.'), 'div', array('class' => 'Info'));
      ?>
   </li>
   <li>
      <?php
         $Options2 = array('0' => T('Don\'t Refresh'),
                           '5' => T('Every 5 seconds'),
                          '10' => T('Every 10 seconds'),
                          '30' => T('Every 30 seconds'),
                          '60' => T('Every 1 minute'),
                         '300' => T('Every 5 minutes'));
         echo $this->Form->Label('Refresh Comments', 'Vanilla.Comments.AutoRefresh');
         echo $this->Form->DropDown('Vanilla.Comments.AutoRefresh', $Options2, $Fields);
      ?>
   </li>
   <li>
      <?php
         echo $this->Form->Label('Archive Discussions', 'Vanilla.Archive.Date');
			echo '<div class="Info">',
				T('Vanilla.Archive.Description', 'You can choose to archive forum discussions older than a certain date. Archived discussions are effectively closed, allowing no new posts.'),
				'</div>';
         echo $this->Form->Calendar('Vanilla.Archive.Date');
			echo ' '.T('(YYYY-mm-dd)');
      ?>
   </li><?php */ ?>
	<li>
      <?php
         echo $this->Form->CheckBox('Galleries.FireEvents.Show', 'Show Locations of fire events in the galleries application');
      ?>
   </li>
</ul>
<?php echo $this->Form->Close('Save');