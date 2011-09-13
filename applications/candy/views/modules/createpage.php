<?php if (!defined('APPLICATION')) exit(); 

//$RequestUri = Gdn::Request()->RequestUri();
$RequestUri = GetValue('REQUEST_URI', $_SERVER);
$Url = Url('candy/page/addnew?URI='.$RequestUri);
?>

<div class="SplashInfo">
<p>
	<?php echo LocalizedMessage("The page you're looking for doesn't exist (yet), do you want to <a href=\"%s\">create one</a>?", $Url);?>
</p>
</div>