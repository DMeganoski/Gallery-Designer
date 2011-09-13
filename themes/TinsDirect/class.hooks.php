<?php if (!defined('APPLICATION')) exit();
/*
Copyright 2008, 2009 Vanilla Forums Inc.
This file is part of Garden.
Garden is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Garden is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Garden.  If not, see <http://www.gnu.org/licenses/>.
Contact Vanilla Forums Inc. at support [at] vanillaforums [dot] com
*/
class TinsDirectHooks implements Gdn_IPlugin {


    public function Base_Render_Before(&$Sender) {
      if ($Sender->Menu)
        $Sender->Menu->AddLink('Home', T('Home'), '/');

		include_once('class.tinfootermodule.php');
		$TinFooterModule = new TinFooterModule();
		$Sender->AddModule($TinFooterModule);

	}
}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
