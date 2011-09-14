<?php if (!defined('APPLICATION')) exit(); ?>


<script type="text/javascript">
function doFormSubmit() {
	// GRAB FIELDS VALUES AND SEND TO uploadify.php, YOU CAN DO WHATEVER YOU WANT WITH THEM IN uploadify.php FILE
	$('#gallery').uploadifySettings('postData', {'field1':$('#field1').val(),'field2':$('#field2').val(),'field3':$('#field3').val(),'field4':$('#field4').val(),'field5':$('#field5').val()});

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
				$("#myForm").append("<input type='hidden' id='img"+img+"' name='img"+img+"' value='"+data+"' />"); // INSERT IMAGE FILENAME IN A HIDDEN FORM FIELD
				img++;
			},

			onQueueComplete: function (stats) {
				$("#myForm").append("<input type='hidden' id='numImgs' name='numImgs' value='" + img + "' />");// INSERT NUMBER OF IMAGES UPLOADED IN A HIDDEN FORM FIELD
				$("#myForm").append("<input type='hidden' id='submitting' name='submitting' value='yes' />");
				$("#myForm").append("<input type='hidden' id='UserID' name='UserID' value='<?php echo $this->UserID; ?>'/>");
				$("#myForm").append("<input type='hidden' id='TransientKey' name='TransientKey' value='<?php echo $this->TransientKey; ?>'/>");
				$('#myForm').submit(); // THIS IS AN EXAMPLE, YOU CAN SUBMIT YOUR INFOS WITH AJAX IF YOU WANT
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
<p>These files will not be available to the public, and can be later found under your profile.</p>
<p>Choose the files you would like to upload, then fill out the information and click submit.</p>
<p>If you are submitting this file for inspection by our staff, please add a brief description.</p>
<form name='myForm' id='myForm' method='post' action='/item/uploadifysavepost'>
<input type='hidden' id='submitting' name='submitting' value='yes' />
<input type='hidden' id='UserID' name='UserID' value='<?php echo $this->UserID; ?>'/>
<input type='hidden' id='TransientKey' name='TransientKey' value='<?php echo $this->TransientKey; ?>'/>
<br></br>
<table width='600' border='0' rules='none' cellspacing='0' cellpadding='5' align='center'>
<tr><td align='right'>Description:</td><td align='left'><input type='text' style='width: 500px;' name='Description' id='Description' value=''></td></tr>
</table>

<br>

<table width='600' border='0' cellspacing='0' cellpadding='2' align='center'><tr>

</tr></table>
<center><div id='gallery'>You've got a problem with your JavaScript</div></center>



<h2>File Queue</h2>
<center><div id='fileQueue'></div></center>


<br>

<center><button type="button" name="btSubmit" id="btSubmit" onclick="doFormSubmit()" class="Button">Upload and Submit</button>
	<a href='#' onclick="jQuery('#gallery').uploadifyCancel('*'); return false;" class="Button">Clear File Queue</a></center>

<br>
</form>
