<?php if (!defined('APPLICATION')) exit();
// This is the home page for the Templates class
include_once(PATH_APPLICATIONS.DS.'galleries/views/helper/helper.php');
?>
<div id="Custom">
    <?php
    if ($ActiveCategory == 'home') {
    ?><div class ="Heading">
        <h1>Design Your Own Artwork!</h1>
        <h2>Templates are available for download in the several formats and sizes</h2>
	<p>For more information on tin sizes, please visit <a href="/gallery/tins">The Tins Section</a>.</p>

	   <div class="Format"><h2 class="Format">Adobe Photoshop  (.psd)</h2>

	   <ul class="SubList PSD">
		  <li class="File"><a href="/download/PSD/Tins_Direct_Art_Template_1S.psd">1-S</a></li>
		  <li class="File"><a href="/download/PSD/Tins_Direct_Art_Template_2C.psd">2-C</a></li>
		  <li class="File"><a href="/download/PSD/Tins_Direct_Art_Template_3C.psd">3-C</a></li>
		  <li class="File"><a href="/download/PSD/Tins_Direct_Art_Template_5C.psd">5-C</a></li>
	    </ul>
	   </div>

	    <div class="Format"><h2 class="Format">Adobe Illustrator  (.ai)</h2>

	    <ul class="SubList AI">
		  <li class="File"><a href="/download/Illustrator/Tins_Direct_Art_Template_1S.ai">1-S</a></li>
		  <li class="File"><a href="/download/Illustrator/Tins_Direct_Art_Template_2C.ai">2-C</a></li>
		  <li class="File"><a href="/download/Illustrator/Tins_Direct_Art_Template_3C.ai">3-C</a></li>
		  <li class="File"><a href="/download/Illustrator/Tins_Direct_Art_Template_5C.ai">5-C</a></li>
	    </ul>
		</div>
            <div class="Format"><h2 class="Format">Adobe Illustrator  (.eps)</h2>

	     <ul class="SubList EPS">
		  <li class="File"><a href="/download/EPS/Tins_Direct_Art_Template_1S.eps">1-S</a></li>
		  <li class="File"><a href="/download/EPS/Tins_Direct_Art_Template_2C.eps">2-C</a></li>
		  <li class="File"><a href="/download/EPS/Tins_Direct_Art_Template_3C.eps">3-C</a></li>
		  <li class="File"><a href="/download/EPS/Tins_Direct_Art_Template_5C.eps">5-C</a></li>
		  </ul>
			</div>

            <div class="Format"><h2 class="Format">Corel CorelDraw  (.cdr)</h2>

	    <ul class="SubList CDR">
		  <li class="File"><a href="/download/CorelDraw/Tins_Direct_Art_Template_1S.cdr">1-S</a></li>
		  <li class="File"><a href="/download/CorelDraw/Tins_Direct_Art_Template_2C.cdr">2-C</a></li>
		  <li class="File"><a href="/download/CorelDraw/Tins_Direct_Art_Template_3C.cdr">3-C</a></li>
		  <li class="File"><a href="/download/CorelDraw/Tins_Direct_Art_Template_5C.cdr">5-C</a></li>
	       </ul>
			</div>

    </div>
<?php
} else {
    echo 'Error, page not found';
}
?></div>





