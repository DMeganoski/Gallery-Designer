<?php if (!defined('APPLICATION'))
	exit();
/*
 * This is the table that is displayed on single-item pages, containing
 * more information about that particular item.
 *
 * You may want to modify this if you have modified the data as well.
 *
 * The variable that stores the item data is known as $FileInfo here
 */
?>
<table id="ItemInfo">
		<h2>More Information</h2>
		<tr><?php
		$ActiveClass = GalleryController::$Class;
		$ActiveCategory = GalleryController::$Category;

		$Licence = $FileInfo->License;

		if ($FileInfo->ClassLabel == 'covers') {
			echo '<td>Artist:</td>';
			echo'<td>'.$FileInfo->Artist.'</td>';
			echo '</tr><tr>';
			echo '<td>License:</td>';
			echo '<td>'.$FileInfo->License.'</td>';
			echo '</tr><tr>';
		} else if ($FileInfo->ClassLabel == 'tins') {
			echo '<td>Width:</td>';
			echo '<td>'.$FileInfo->Width.'</td>';
			echo '</tr><tr>';
			echo '<td>Height:</td>';
			echo '<td>'.$FileInfo->Height.'</td>';
			echo '</tr><tr>';
			echo '<td>Depth:</td>';
			echo '<td>'.$FileInfo->Depth.'</td>';
			echo '</tr><tr>';
			echo '<td>Volume:</td>';
			echo '<td>'.$FileInfo->Volume.'</td>';
			echo '</tr><tr>';
		}

		echo '<td>Base Price:</td>';
		echo '<td>$ '.$FileInfo->PriceLabel.'</td>';
		?></tr>
</table>