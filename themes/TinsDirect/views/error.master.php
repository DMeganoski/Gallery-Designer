<?php
@ob_end_clean();
echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-ca">
<head>
   <title>Bonk</title>
   <?php
   if ($CssPath !== FALSE)
      echo '<link rel="stylesheet" type="text/css" href="',Asset($CssPath),'" />';
   ?>
</head>
<body>
   <div id="Content">
      <div class="SplashInfo">
         <h1>Bonk</h1>
	 <p>How Did that happen? You shouldn't be here.</p>
         <p>One of the Tin Bots might have hidden a piece of the code again...</p>
	 <p>Click <a href="/">*here*</a> to go home.
      </div>
   </div>
</body>
</html>