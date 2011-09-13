<?php if (!defined('APPLICATION')) exit();
echo $this->Form->Open();
echo $this->Form->Errors();
?>
<h1><?php echo Gdn::Translate("User List Module"); ?></h1>
      <table class="AltRows">  
         <tbody>
         	  <tr>
               	  <th><?php echo T('Limit'); ?></th><th><?php echo $this->Form->TextBox('UserList.Limit', 'Limit'); ?></th>
              </tr>
              <tr>
               	  <th><?php echo T('Module Title'); ?></th><th><?php echo $this->Form->TextBox('UserList.Title', 'Title'); ?></th>
              </tr>
         	  <tr>
                  <th colspan="2"><?php
                     echo $this->Form->Checkbox('UserList.Random', "Randomize");
                  ?></th>
               </tr>
               <tr>
                  <th colspan="2"><?php
                     echo $this->Form->Checkbox('UserList.Hide', "Hide from guests");
                  ?></th>
               </tr>
               <tr>
                  <th colspan="2"><?php
                     echo $this->Form->Checkbox('UserList.ShowNumUsers', "Show total number of users next to title");
                  ?></th>
               </tr>
         </tbody>
      </table>
      <table class="AltRows">  
         <tbody>
         	  <tr>
                  <th>
                  	<?php echo T('Photo Mode'); ?>
                  </th>
               </tr>
         	   <tr>
                  <th><?php
                     echo $this->Form->Checkbox('UserList.Photo', "Show Photo");
                  ?></th>
               </tr>
               <tr>
                  <th><?php
                     echo $this->Form->Checkbox('UserList.NoPhoto', "Show users that don't have a photo");
                  ?></th>
               </tr>
         </tbody>
      </table>

<?php echo $this->Form->Close('Save');
