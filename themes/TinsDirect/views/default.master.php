<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-ca">
<head>
   <?php $this->RenderAsset('Head');?>

</head>
<body id="<?php echo $BodyIdentifier; ?>" class="<?php echo $this->CssClass; ?>">
<div id="Head">
    <div id="HeadWrapper">
	<div class="Logo">
	    <h1><a class="Title" href="<?php echo Url('/'); ?>"><span><?php echo Gdn_Theme::Logo(); ?></span></a></h1>
	</div>

	<div class="Menu">
	<?php
	$Session = Gdn::Session();
	if ($this->Menu) {
	    $HomeLink =  T('Home');
	    $this->Menu->AddLink('Home', $HomeLink , '/', FALSE, array('class' => 'Home'));
    	    //$this->Menu->AddLink('Dashboard', T('Dashboard'), '/dashboard/settings', array('Garden.Settings.Manage'));
	    // $this->Menu->AddLink('Dashboard', T('Users'), '/user/browse', array('Garden.Users.Add', 'Garden.Users.Edit', 'Garden.Users.Delete'));
	    $this->Menu->AddLink('ContactUs', T('Contact Us'), '/gallery/default/contactus', FALSE, array('class' => 'Contact'));
    	    $Authenticator = Gdn::Authenticator();
		$this->Menu->RemoveLinks('Messages');
	    $Location = array_search("Home", $this->Menu->Sort);
	    array_splice($this->Menu->Sort, $Location, 0 , "Home");
	    echo $this->Menu->ToString();
	}
	$ProjectModel = new ProjectModel();
	$CurrentProject = $ProjectModel->GetCurrent($Session->UserID);
	?>
	</div>
<script type="text/javascript">
	$(function() {
		$("td.Background").droppable( {
			drop: function( event, ui ) {
				$(this).addClass( "ui-state-highlight" )
				.find( "p" )
				.html( "Dropped" );
			}
		});
	});
</script>
	<div class="Account" userid="<? echo $Session->UserID ?>" projectid="<? echo $CurrentProject->ProjectKey ?>" transientkey="<? echo $Session->TransientKey(); ?>"><?php



	       $Authenticator = Gdn::Authenticator();
			if ($Session->IsValid()) {
				// Set Variables
					$Inbox = T('Messages');
					$Notifications = T('Notifications');
					$Name = $Session->User->Name;
					$ProfileSlug = $Session->UserID.'/'.urlencode($Session->User->Name);
					$CountNotifications = $Session->User->CountNotifications;
					$CountUnreadConversations = $Session->User->CountUnreadConversations;
					$Admin = $Session->CheckPermission('Garden.Settings.Manage');

				?><div class="Profile"><? // Beginning Profile Box
					if (is_numeric($CountNotifications) && $CountNotifications > 0)
						$Notifications .= ' <span>'.$CountNotifications.'</span>';
					echo '<a href="/dashboard/'.Gdn::Authenticator()->SignOutUrl('gallery').'" class="SignOut Icon" title="'.T('Sign Out').'"><span clas="Icon"></span></a>';

					if (!C('Garden.SignIn.Allow')) {
						echo '<a href="/profile/aboutme/'.$ProfileSlug.'" class="Text Profile"><span clas="Icon"></span>'.$Name.'</a><strong>signed in as</strong>';
					}



			?><div class="ClearFix"></div></div><? // End profile Box

			?><div class="Notifications"><?php // Beginning Notifications Box
				if ($Admin)
					echo '<a href="/dashboard/settings" class="Icon Dashboard" title="Dashboard"><span clas="Icon"></span></a>';

				if (is_numeric($CountUnreadConversations) && $CountUnreadConversations > 0)
					$Inbox .= ' <span>'.$CountUnreadConversations.'</span>';

				echo '<a href="/messages/all" class="Text Messages"><span clas="Icon"></span>'.$Inbox.'</a>';
				echo '<a href="/profile/'.$ProfileSlug.'" class="Text Notif"><span clas="Icon"></span>'.$Notifications.'</a>';


			?><div class="ClearFix"></div></div><? // End Notifications Box
	} else {
					$Attribs = array();
					if (SignInPopup() && strpos(Gdn::Request()->Url(), 'entry') === FALSE)
						$Attribs['class'] = 'SignInPopup';

					echo '<div class="Profile"><a href="/dashboard/'.Gdn::Authenticator()->SignInUrl().'" class="Text SignIn Popup"><span clas="Icon"></span>'.T('Sign In').'</a></div>';
				}
	?>
	</div><? // End Account Box
	 ?>

	<div class="Search"><?php
	    $Form = Gdn::Factory('Form');
	    $Form->InputPrefix = '';
	    echo
		$Form->Open(array('action' => Url('/search'), 'method' => 'get')),
		$Form->TextBox('Search'),
		$Form->Button('Go', array('Name' => '')),
		$Form->Close();
	?></div>

    </div>
</div>
<div id="Body">
	<div id="Panel"><?php $this->RenderAsset('Panel'); ?></div>
	 <div id="Content"><?php $this->RenderAsset('Content'); ?></div>
	</div>
	<div id="Foot">
	    <?php
	    $this->RenderAsset('Foot');
		include('themes/TinsDirect/views/footerinfo.php');
	    ?>
	</div>
    <?php $this->FireEvent('AfterBody'); ?>
</body>
</html>
