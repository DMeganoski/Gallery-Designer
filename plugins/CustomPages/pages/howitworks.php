<?php if (!defined('APPLICATION')) exit();
?>

<div class="Tabs">
    <ul>
        <?php echo '<li'.($Sender->RequestMethod == '' ? ' class="Home"' : '').'>'
			.Anchor(T('Home'), '/', 'Home')
		.'</li>';
        echo '<li'.($Sender->RequestMethod == '' ? ' class="Active Works"' : '').'>'
			.Anchor(T('How It works'), '/howitworks', 'HowItWorks')
		.'</li>';
        ?>
</div>
<div id="Display">
    
</div>
<div id="Custom">
        <H1>Select a Tin</h1>
        <p>Thousands of tins to choose from; all sizes and colors!</p>
        <h1>Select a Cover Image</h1>
        <p>Hundreds of images to choose from, or use your own background!</p>
        <h1>Select a Product</h1>
        <p>Pick you treat! Choose from a large variety of snacks, nuts and candy</p>
        <h1>Select Your Message</h1>
        <p>Customize your can! Add your company logo, and/or a message for the lucky recipients</p>
        <h1>Commit Your Order</h1>
        <p>Place the order, and one of our representatives will contact you about final touches and payment information</p>
</div>
