<?php if (!defined('APPLICATION'))
	exit(); ?>
<div id="Custom">
	<div class="Header">
		<h1>Your file is being fetched.</h1>
		<h2>Please be patient while the file is fetched from the system.</h2>
	</div>
	<p>If your download does not start automatically, click <a href="#">*Here*</a></p>
	<?php echo $this->Alert;
	echo '<br/><a href="'.$this->Path.'">'.$this->Path.'</a>';
?></div>