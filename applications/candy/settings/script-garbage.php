<?php
require_once dirname(__FILE__).'/../../../plugins/UsefulFunctions/bootstrap.console.php';
$SQL = Gdn::SQL();
$Px = $SQL->Database->DatabasePrefix;
$ChunkModel = new ChunkModel();

$Limit = Console::Argument('limit', 5);
if (!is_numeric($Limit) || $Limit <= 0) $Limit = 5;

Gdn::Config()->Set('Plugins.UsefulFunctions.LoremIpsum.Language', 'noIpsum', True, False);


/**
* Examples:
* 
*/

Console::Message("Start.");

for ($i = 0; $i < $Limit; $i++) {
	
	$Format = 'xHtml';
	$Name = LoremIpsum(2);
	$Body = LoremIpsum('p'.rand(2,3));
	$InsertUserID = rand(1, 5);
	$Fields = compact('Format', 'Name', 'Body', 'InsertUserID');

	$ChunkID = $ChunkModel->Save($Fields);
	if (!$ChunkID) {
		Console::Message('^1Error: %s', VarDump($ChunkModel->Validation->Results()));
		return;
	}
	Console::Message("Saved ^3%s (%s)", $ChunkID, $Name);
}