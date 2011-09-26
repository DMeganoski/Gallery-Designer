<?php if (!defined('APPLICATION')) exit(); ?>


<script type="text/javascript">
function doFormSubmit() {
	// GRAB FIELDS VALUES AND SEND TO uploadify.php, YOU CAN DO WHATEVER YOU WANT WITH THEM IN uploadify.php FILE
	$('#gallery').uploadifySettings('postData', {'numImgs':$('#numImgs').val(),'submitting':$('#submitting').val(),'UserID':$('#UserID').val(),'TransientKey':$('#TransientKey').val(),'Description':$('#Description').val()});

	// UPLOAD IMAGES
	$('#gallery').uploadifyUpload();
}

$(document).ready(function() {
	// Verify if Flash Player is Installed and if Flash Player version is 9 or higher
	if (!FlashDetect.versionAtLeast(9)) {
		// You can have an invisible DIV that contains an alternative form input box to upload files without uploadify, when Flash Detect Fails, you set it to visible and handle things the way you want, you can use this error control to do whatever you want if user has no Flash Player Inslalled.
		$("#gallery").html('You do not have Flash Player installed or your Flash Player is too old!<br>Please install Flash Player 9 or higher.');
	} else {
		var img = 0;
		$("#gallery").uploadify({
			// Required Settings
			langFile : '/applications/galleries/js/uploadifyLang_en.js',
			swf : '/applications/galleries/views/item/jquery.uploadify-v3.0.0/uploadify.swf',
			uploader : '/item/uploadify',

			// Options - HERE ARE ALL USEFUL OPTIONS, DON'T USE ANYTHING THAT ISN'T LISTED HERE
			'debug'           : false, // DON'T SET THIS TO TRUE UNLESS YOU NEED TO SEE IF THERE IS ANY ERROR IN YOUR SCRIPT, IN YOUR SITE, JUST DON'T USE THIS OPTION AT ALL
			'auto'            : false,
			'buttonText'      : 'Select Images',
			'width'           : 150,
			'height'          : 30,
			'cancelImage'     : '/applications/galleries/design/images/uploadify-cancel.png',
			'checkExisting'   : '/item/uploadifycheckexists',
			'fileSizeLimit'   : 1*1024*1024, // 1MB
			'fileTypeDesc'    : 'Image Files',
			'fileTypeExts'    : '*.gif;*.jpg;*.png',
			'method'          : 'post',
			'multi'           : true,
			'queueID'         : 'fileQueue',
			'queueSizeLimit'  : 999,
			'removeCompleted' : true,
			'postData'        : {},
			'progressData'    : 'all',

			onUploadSuccess : function(file,data,response) {
				$("#uploadForm").append("<input type='hidden' id='img"+img+"' name='img"+img+"' value='"+data+"' />"); // INSERT IMAGE FILENAME IN A HIDDEN FORM FIELD
				img++;
			},

			onQueueComplete: function (stats) {
				$("#uploadForm").append("<input type='hidden' id='numImgs' name='numImgs' value='" + img + "' />");// INSERT NUMBER OF IMAGES UPLOADED IN A HIDDEN FORM FIELD
				$("#uploadForm").append("<input type='hidden' id='submitting' name='submitting' value='yes' />");
				$("#uploadForm").append("<input type='hidden' id='UserID' name='UserID' value='<?php echo $this->UserID; ?>'/>");
				$("#uploadForm").append("<input type='hidden' id='TransientKey' name='TransientKey' value='<?php echo $this->TransientKey; ?>'/>");
				$('#uploadForm').submit(); // THIS IS AN EXAMPLE, YOU CAN SUBMIT YOUR INFOS WITH AJAX IF YOU WANT
			}
		});
	}
});
</script>

</head>

<body>

<?
// THIS IS AN EXAMPLE, YOU CAN GRAB THIS INFOS VIA AJAX TO INSERT IN YOUR DATABASE
$submitting = $_REQUEST['submitting'];
if ($submitting == 'yes') {
	// REQUEST POST INFOS
	$UserID = $_REQUEST['UserID'];
	$TransientKey = $_REQUEST['TransientKey'];
	$Description = $_REQUEST['Description'];
	$numImgs = $_REQUEST['numImgs'];
	for ($i = 0; $i <= $numImgs; $i++) {
		$img[$i] = $_REQUEST['img'.$i];
	}

	// DISPLAY POST INFOS - YOU CAN DO WHATEVER YOU WANT WITH THIS
	echo 'UserID: '.$UserID.'<br>';
	echo 'TransientKey: '.$TransientKey.'<br>';
	echo 'Description: '.$Description.'<br>';

	for ($i = 0; $i <= $numImgs; $i++) {
		if ((isset($img[$i])) && ($img[$i] != '')) {
			echo 'image'.$i.': '. $img[$i].'<br>';
		}
	}
	echo '<br>';
}
?>
<h1>This is where you can upload your images for use in the tin design</h1>
<p>This can either be an image to use in your own design here on the site, or a complete template ready to be printed.</p>
<div class="HelpWrapper">
	<div class="Help Aside">
		<h2>File Types</h2>
		<li class="Info">Accepted file types are:</li>
		<li class="Info"> .jpg, .png, .psd, .ai, .eps, and .cdr.</li>
	</div>
	<div class="Help Aside">
		<h2>File Size</h2>
		<li class="Info">The <? echo T('bases') ?> are printed in high resolution. Images to be used in the project should be as high-resolution as possible.</li>
	</div>
	<div class="Help Aside">
		<h2 class="Info">Choose the Files to Upload</h2>
		<li class="Info">Choose as many files as you would like to upload, and click submit.</li>
	</div>
	<div class="Help Aside">
		<h2 class="Info">Add a Note</h2>
		<li class="Info">The description will be applied to all the files chosen.</li>
		<li class="Info">This can be used for personal identification, or as a note for our staff.</li>
	</div>
</div>
<form name='uploadForm' id='uploadForm' method='post' action='/item/uploadifysavepost'>
<input type='hidden' id='submitting' name='submitting' value='yes' />
<input type='hidden' id='UserID' name='UserID' value='<?php echo $this->UserID; ?>'/>
<input type='hidden' id='TransientKey' name='TransientKey' value='<?php echo $this->TransientKey; ?>'/>
<table width='400' border='0' rules='none' cellspacing='0' cellpadding='5' align='center'>
<tr><td align='right'>Description:</td><td align='left'><input type='text' style='width: 300px;' name='Description' id='Description' value=''></td></tr>
</table>

<table width='400' border='0' cellspacing='0' cellpadding='2' align='center'><tr>

</tr></table>
<center><div id='gallery'>You've got a problem with your JavaScript</div></center>



<h2>File Queue</h2>
<center><div id='fileQueue'></div></center>


<br>

<center><button type="button" name="btSubmit" id="btSubmit" onclick="doFormSubmit()" class="Button">Upload and Submit</button>
	<button onclick="jQuery('#gallery').uploadifyCancel('*'); return false;" class="Button">Clear File Queue</button></center>

<br>
</form>
