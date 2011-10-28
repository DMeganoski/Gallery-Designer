<?php if (!defined('APPLICATION'))exit();

// Basically the view for all custom photo galleries.
// Lists the given images, and creates the necessary links for pages.
// @ $AllFiles, @ $PublicDir

include(PATH_APPLICATIONS.DS.'galleries/views/helper/helper.php');
?>
<script type="text/javascript">
$(document).ready(function() {

	var dragging = false;

	$(".Project").droppable({
			"over": function( event, ui ) {
				$( this )
					.addClass( "ui-state-highlight" );

					},
					"drop": function( event, ui ) {
						var itemType = $(ui.draggable).attr("itemtype");
						var itemSlug = $(ui.draggable).attr("itemslug");
						$(this).doFrameSubmit();
						$(this).doProjectSubmit( itemType, itemSlug );
						//$(this).doFrameSubmit();
						$(this).removeClass( "ui-state-highlight" );
					},
					"out": function() {
						$(this).removeClass( "ui-state-highlight" );
					}

			});

	function browseHelper( src ) {
		return "<img src=\"" + src + "\" class=\"Helper\"></img>";
	}

	$("img.Gallery").draggable({"revert": "invalid", "opacity": "0.5", "cursor": 'move',
			"start": function(event,ui) {
				dragging = true;
				//$('.ProjectBox').show()
				//.updateProjectBox();
			},
			"stop": function() {
				$('img.Helper').hide();
				dragging = false;

			}
	});
	});
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
				echo ' itemslug="'.$Item->Slug.'" itemtype="'.$Item->ClassLabel.'"/>';
                ?></a></li><?php
                }?>
</ul>

</div>
<?php include(PATH_APPLICATIONS.DS.'galleries/views/helper/pager.php');
 if (C('Galleries.ShowFireEvents'))
			$this->DisplayFireEvent('AfterBrowsePager');

	$this->FireEvent('AfterBrowsePager');
	} ?>
