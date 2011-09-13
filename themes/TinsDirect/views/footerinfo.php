<?php if (!defined('APPLICATION'))
	exit(); ?>

<div id="FooterWrapper">
	<ul id="FootMenu">
		<li><a href="/">Home</a></li>
		<li><a href="/gallery">Gallery</a></li>
		<li><a href="/gallery/default/howitworks">How it Works</a></li>
		<li><a href="/gallery/default/pricing">Pricing</a></li>
		<li><a href="/gallery/contactus">Contact Us</a></li>
		<li><a href="/gallery/designer">Tin Designer</a></li>
		<li><a href="/gallery/tins">Browse Tins</a></li>
		<li><a href="/gallery/covers">Browse Stock Art</a></li>
		<li><a href="/gallery/templates">Templates</a></li>

	</ul>
	<div class="Text">
		<h2>Our Commitment</h2>
		<p>Tins Direct is committed to being a fair business, in which every transaction
		is a step forward for both our business and the customer.</p>
		<p>Our friendly staff will work with you from the start of the project until the finish, making sure
		that you receive the product exactly as expected.</p>
	</div>

	<div class="Text">
		<h2>Learn More</h2>
		<p>Learn how our process works, how our tins can be customized,
			and special options for your tin project and order.</p><a href="#">Read More</a>
	</div>

	<div class="Text">
		<h2>Get Started</h2>
		<p>
			Step by step insight and guide into designing your tin, just the way you want it.
		</p>
		<a href="#">Read More</a>
	</div>

	<div class="Text">
		<h2>Legal Stuff</h2>
		<ul class="Normal">
			<li><a href="#">Terms of Use</a></li>
		</ul>
	</div>

<div id="Clear">

</div><div id="Thanks"><?php
		echo Wrap(T('A special Thanks to'), 'h2');
		echo '<ul>';
		echo Wrap(Anchor(T('Vanilla Forums / Garden CMS'), C('Garden.VanillaUrl')), 'li');
		echo Wrap(Anchor(T('JQuery and plugins'), C('Garden.VanillaUrl')), 'li');
		echo '</ul>'
	?><div id="Clear">

	</div>

</div>

</div>