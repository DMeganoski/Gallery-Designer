<?php if (!defined('APPLICATION')) exit();
 
class UserListModule extends Gdn_Module {
   
   public function __construct(&$Sender = '') {
      parent::__construct($Sender);
   }
   
   public function GetData() {
   	 $SQL = Gdn::SQL();
     $Session = Gdn::Session();
     $Random = Gdn::Config('UserList.Random');
     $Limit = Gdn::Config('UserList.Limit');
     $NoPhoto = Gdn::Config('UserList.NoPhoto');
     
     $Select='u.UserID, u.Name, u.Photo';
     $From='User u';
     $Where=array('u.Deleted'=>0,'r.RoleID>'=>7);
     
     if($Random && $NoPhoto) {
        	$Order='RAND()';
     } else if((!$NoPhoto) && ($Random)) {
        	$Where['u.Photo >']=0;
	       	$Order='RAND()';
     } else if(!$NoPhoto) {
        	$Where['u.Photo >']=0;
        	$Order='u.UserID';
     } else {
        	$Order='u.UserID';
     }
     
     $this->Users = $SQL
    	    ->Select($Select)
        	->From($From)
        	->Join('UserRole r','u.UserID = r.UserID')
        	->Where($Where)
        	->Get();
     
     $this->All_Users = $SQL
    	    ->Select('u.UserID')
        	->From('User u')
        	->Join('UserRole r', 'u.UserID = r.UserID')
        	->Where(array('u.Deleted'=>0,'r.RoleID>'=>7))
        	->Get();

   }

   public function AssetTarget() {
      return 'Panel';
   }

   public function ToString() {
      $String = '';
      $Session = Gdn::Session();
	  $permissions=$Session->User->Permissions;
	  $admin=preg_match('/Garden.Settings.Manage/',$permissions);
	  
      ob_start();
      $Limit = Gdn::Config('UserList.Limit');
      $Photo = Gdn::Config('UserList.Photo');
      $Title = Gdn::Config('UserList.Title');
      $ShowNumUsers = Gdn::Config('UserList.ShowNumUsers');
      
      if(empty($Title)) {
      	$Title="Members";
      }
      
      if($Photo) {
      ?>
      <style type="text/css">
#UserList ul.PanelInfo li {
	border: 0!important;
	float: left;
	width: 25%;
	padding: 5px 0 0 0;
}
#UserList img {
	height: 48px;
	width: 48px;
}
#UserList ul.PanelInfo li {
	text-align: center!important;
}
</style>
<?php } ?>
<style type="text/css">
#UserList ul.PanelInfo li a {
	float: none;
}
#UserList ul.PanelInfo li {
	text-align: left;
}
</style>
      <div id="UserList" class="Box">
         <h4><?php echo $Title;
         if($ShowNumUsers) echo " (".$this->All_Users->NumRows().")"; ?></h4>
         <ul class="PanelInfo">
            <?php
			if($this->Users->NumRows() > 0) { 
               foreach($this->Users->Result() as $User) {
                  ?>
                  <li>
                     <?php
                     if($Photo) {
	                     if(!empty($User->Photo)) {
		                    echo "<a href=\"".Url('/profile/'.$User->Name)."\"><img src=\"".Url('uploads/'.ChangeBasename($User->Photo, 'p%s'))."\" alt=\"".$User->Name."\" title=\"".$User->Name."\" /></a><br />";
	    	             } else {
	        	         	echo "<a href=\"".Url('/profile/'.$User->Name)."\"><img src=\"".Url('plugins/UserList/user.png')."\" alt=\"".$User->Name."\" title=\"".$User->Name."\" /></a><br />";
	            	     }
	            	 } else {
	            	 	echo UserAnchor($User);
	            	 }
                     ?>
                  </li>
                  <?php
                  }
               } else {
	               if($admin) {
	               	 echo "<p>No users to display. Try <a href=\"".Url('plugin/userlist')."\">changing your settings</a>.</p>";
	               } else {
	                 echo "<p>No users to display.</p>";
	               }
               }
            ?>
         </ul>
         <div style="clear:both;"></div>
      </div>
      <?php
      $String = ob_get_contents();
      @ob_end_clean();
      return $String;
   }
}
