<?php if (!defined('APPLICATION'))exit();

// Basically the view for all custom photo galleries.
// Lists the given images, and creates the necessary links for pages.
// @ $AllFiles, @ $PublicDir

include(PATH_APPLICATIONS.DS.'galleries/views/helper/helper.php');
?>
<script type="text/javascript">
	$(document).ready(function() {
		$("#ImageWrapper").draggable({ "snap": ".ProjectBox", "revert": "invalid", "opacity": "0.5", "cursor": 'move', "helper": myHelper,
			"start": function(event,ui) {
				//$('td.Background').html("<img src=\"" + image + "\"></img>");
			},
			"stop": function() {
				$('img.Helper').hide();
			}
	});
	}
</script>
<div class="Custom"><?php
if (!is_a($AllFiles, Gdn_DataSet)) {
      echo '<h1>Sorry, no items found</h1></div>';
    } else {
		if (C('Galleries.ShowFireEvents'))
			$this->DisplayFireEvent('BeforeBrowseView');

	 $this->FireEvent('BeforeBrowseView');
?>
	<div class="Heading">
		<h2>Click an item for more information and options</h2>
		<p>Or simply drag it to your project box</p>
	</div>
<ul class="Gallery">
    <?php
    foreach($AllFiles as $Item){
            ?><li id="ImageWrapper" class="Item Gallery Image">
				<a href="<?php echo '/item'.DS.$Item->Slug; ?>">
                <img src="<?php
                echo $PublicDir.$ActiveClass.DS.$Item->Slug.'M.jpg';
                echo '" class="Gallery Image"';
				echo 'page="/item'.DS.$Item->Slug.'"';
				echo ' itemid="'.$Item->Slug.'" type="'.$Item->ClassLabel.'"/>';
                ?></a></li><?php
                }?>
</ul>

</div>
<?php include(PATH_APPLICATIONS.DS.'galleries/views/helper/pager.php');
 if (C('Galleries.ShowFireEvents'))
			$this->DisplayFireEvent('AfterBrowsePager');

	$this->FireEvent('AfterBrowsePager');
	} ?>
