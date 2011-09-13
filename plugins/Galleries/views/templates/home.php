<?php if (!defined('APPLICATION')) exit();
include_once(PATH_PLUGINS.DS.'Galleries/views/helper/helper.php');
?>
<div id="Custom">
    <?php
    if ($ActiveCategory == 'home') {
    ?><div class ="Heading">
        <h1>Design Your Own Artwork!</h1>
        <h2>Templates are available for download in the several formats and sizes</h2>
	<p>For more information on tin sizes, please visit <a href="/tinsdirect/plugin/gallery/tins">The Tins Section</a>.</p>
	<p>The downloads are quite large image f</p>
        <ul class="HowTo">
	   <li class="Format"><a href="">Adobe Photoshop  (.psd)</a></li>
	    <li class="Sizes">Sizes:</li>
	    <li><a href="">1S</a></li>
            <li><a href="">Adobe Illustrator  (.ai)</a></li>
            <li><a href="">Adobe Illustrator  (.eps)</a></li>
            <li>Corel CorelDraw  (.cdr)</a></li>
	    <li class="Sizes">Sizes:</li>
	    <li class="File"><a href="downloaddoc?download_file=CoreDraw_Template_TIN_SIZE_1S.cdr">1S</a></li>
        </ul>


    </div>

<?php
} else if ($ActiveCategory == 'pricing') {?>
    <div class ="Heading">
        <h1>2011 Pricing Guide</h1>
    </div>

<?php
} else if ($ActiveCategory == 'requirements') {
   ?><div class="Heading">
   <h1>Custom Design Template Submission Requirements</h1>
      <h2>Vector Art:</h2>
      <p>We accept vector art files in the following formats:</p>
      <ul>
	 <li>Adobe Illustrator (.ai)</li>
	 <li>Corel Draw (.cdr)</li>
	 <li>Adobe Illustrator (.EPS</li>
	 <li> and Adobe Acrobat (.PDF)<li>
	 <p>Make sure all fonts are converted to outline/curves before sending.</p>
      </ul>
	 <h2>Pixel-Based Art:</h2>
	 <p>We accept pixel-based art in the following template formats:</p>
	 <ul>
	    <li>Adobe Photoshop (.PSD)</li>
	 </ul>
	 <p><span>Other formats:</span> Other file formats such as JPGs and TIFs can also be submitted. However, a service charge
            may be applied if our art department is asked to prepare non-template art.</p>
	 <p>If you would like assistance with graphic services please <a href="/tinsdirect/contactus">Contact Us</a>.
	 </p>
   </div><?php
} else {
    echo 'Error, page not found';
}
?></div>





