<?php if (!defined('APPLICATION'))
	exit();

$CurrentProject = $this->CurrentProject;
			if ($this->CurrentProject === FALSE) {
				echo 'No Project Currently Selected. Would you like to start a new one or select an existing one?';
			}