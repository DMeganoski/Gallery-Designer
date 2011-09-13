<?php if (!defined('APPLICATION'))
   exit();

?>
<div id="Custom">

   <div class="Heading">
    <?php
      echo $this->Form->Open();
      echo $this->Form->Errors();
      ?>
   <h1>Scan Server For Items</h1>
      <p>This scans the /uploads/item and subdirectories for items to add to the database.
		  The information entered into the database is dependant on the name of the file.</p>
	  <p>For instructions on how to name files and use this feature, please see the Galleries Documentation.</p>
         <?php
	 echo $this->Form->Close('Update Database');
	 ?>
   </div>

   <?php
   ?>

</div>