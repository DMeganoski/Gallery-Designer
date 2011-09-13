<?php if (!defined("APPLICATION")) exit(); 
/*
 *  Nillablog vanilla plugin.
 *  Copyright (C) 2011 ddumont@gmail.com
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>. 
 */?>
<h1> <?php echo $this->Data("Title");?> </h1>
<?php
	echo $this->Form->Open();
	echo $this->Form->Errors();
?>
<div class="Info"><?php echo T("You must, at the very least, fill in which category ID you want to turn into a blog."); ?></div>
<table class="AltRows">
	<tbody>
		<tr>
			<th><?php echo T("Category ID"); ?></th>
			<td class="Alt"><?php echo $this->Form->TextBox("Plugins.NillaBlog.CategoryID"); ?></td>
		</tr>
	</tbody>
</table>
<br>

<?php 
   echo $this->Form->Close('Save');

