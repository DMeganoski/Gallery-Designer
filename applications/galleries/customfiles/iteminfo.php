<?php if (!defined('APPLICATION'))
	exit();

/*
 * This is the file containing the html and data that is displayed in
 * the qtip tooltip popups in galleries.
 */

echo '<table><tr>';
echo '<th>Title: </th>';
echo '<td>'.$Item->Name.'</td>';
echo '</tr><tr>';
echo '<th>Code: </th>';
echo '<td>'.$Item->Slug.'</td>';
echo '</tr></table>';