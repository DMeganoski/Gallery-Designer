<?php if (!defined('APPLICATION'))
	exit(); ?>

<div id="Custom">
	<div class="Heading">
		<h1>My Personal Projects</h1>
	</div>
		<? foreach ($this->Projects as $Project) { ?>
		<ul id="Projects">
			<li class="Item Project">
				<h2><? echo "Title: $Project->ProjectName" ?></h2>
				<h2><? echo "Project ID: $Project->ProjectKey" ?></h2>
				<a href="/project/delete/<? echo $Project->ProjectKey ?>" class="Button">Delete</a>
				<table>
					<tr class="Heading">
						<td>Selected Tin</td>
						<td>Selected Background</td>
						<td>Selected Logo/artwork</td>
					</tr>
					<? //Start of background box ?>
					<tr>
						<td class="Type Image">
							<? $Tins = unserialize($Project->tins);
							foreach ($Tins as $Tin) { ?>
								<img src="/uploads/item/tins/<? echo $Tin; ?>M.jpg"></img>
							<? } ?>
						</td>
						<td class="Type Image"> <?
							$Backgrounds = unserialize($Project->covers);
							foreach ($Backgrounds as $Background) { ?>
								<img src="/uploads/item/covers/<? echo $Background; ?>M.jpg"></img>
							<? } ?>
						</td>
						<td class="Type Image"> <?
							$Logos = unserialize($Project->artwork);
							foreach ($Logos as $Logo) { ?>
							<img src="/uploads/<? echo $Logo ?>"></img>
							<? } ?>
						</td>
					</tr>
				</table>
				<div class="ClearFix"></div>
			</li>
		<? } ?>
		</ul>
</div>