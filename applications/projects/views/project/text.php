<?php if (!defined('APPLICATION'))
	exit();

// Start Message Display
if (!empty($CurrentProject->Message)) {
	?><table><tr>
		<th class="Selected">Included Message:</th>
	</tr><tr>
		<th>Custom</th>
	</tr>
	<tr><td>
		<? echo $CurrentProject->Message;
		?></td></tr>
		<tr><td><a href="/designer/text" class="Button">Edit</a></td></tr>
		</table><?
} else {
	echo '<a href="/designer/text" class="Button">Add a Message</a>';
}
echo '</div>';