<?php if (!defined('APPLICATION')) exit();
include(PATH_PLUGINS.DS.'Galleries/views/helper/helper.php');
?>
<div id="Custom">
    <?php
    if ($ActiveCategory == 'home') {
    ?>
    <div id="AnimationWrapper">
    <div id="Animation">

    </div>
    <div Class="Welcome">
        <H1>Welcome to Tins Direct!</h1>
        <p>Your source for custom gift or packaging tins for your business, organization, and family!</p>
    </div>
</div>
<div id="Custom">
</div>
    <h1>Welcome to the Interactive Tin Builder</h1>
    <h2>Sorry about the mess!</h2>
    <p>We unfortunately had technical difficulties with our previous web site beyond repair, though the Tin Bots have been in the process of
	building a new and improved web site!</p>
    <p>You can still browse through our collection of beautiful stock art, though many of the new features will not be available immediately.</p>
    <p>If you would prefer, you can also view and download our catalog <a href="">*here*</a>.</p>
    <p>More information on the Tin Builder can be found <a href="">*here*</a>.</p>
<?php
} else if ($ActiveCategory == 'howitworks') {?>
    <div class ="Heading">
        <h1>Design Your Own Tin!</h1>
        <h2>Designing your own tin is easy</h2>
        <ul class="HowTo">
            <li>Choose a Size and Color Tin</li>
            <li>Choose a background image, or upload your own</li>
            <li>Add your logo and position it</li>
            <li>Add your message and position it</li>
        </ul>
        <p>You can use your own background, logo, or other graphics on your tin; or choose from the beautiful stock photos provided.</p>
        <p>If you would like to download a template to design the entire laminate, click <a href="">*here*</a>.</p>
        <p><span>Coming Soon!</span> Upload your custom images directly to the web site and design your laminate in our interactive Tin Builder!</p>
    </div>

<?php
} else if ($ActiveCategory == 'pricing') {?>
    <div class ="Heading">
        <h1>2011 Pricing Guide</h1>
    </div>
<?php
} else if ($ActiveCategory == 'contactus') {?>
    <div class="Contact">
        <h1>Were here to help! Choose one of the many ways below to contact us.</h1>
    </div>
    <div class="Contact Info">
        <h2>Phone</h2>
        <p>1 (412) 586-7342</p>
        <h2>Email</h2>
        <p><a href="mailto:info@tinsdirect.com">info@tinsdirect.com</a></p>
    </div>
<?php
} else {
    echo 'Error, page not found';
}
?></div>




