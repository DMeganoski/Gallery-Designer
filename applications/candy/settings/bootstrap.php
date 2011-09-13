<?php if (!defined('APPLICATION')) exit();

Gdn::FactoryInstall('SectionModel', 'SectionModel', 
	PATH_APPLICATIONS.'/candy/models/class.sectionmodel.php', Gdn::FactorySingleton);
	
if (!function_exists('ValidateUrlPath')) {
	function ValidateUrlPath($Value, $Field = '') {
		return ValidateRegex($Value, '/^([\/\d\w\-]+)?$/');
	}
}

if (!function_exists('IsContentOwner')) {
	function IsContentOwner($Object, $HasAccessPermission = False) {
		$Session = Gdn::Session();
		if (is_string($HasAccessPermission)) {
			$HasAccessPermission = $Session->CheckPermission($HasAccessPermission);
		}
		return $HasAccessPermission || ($Session->UserID > 0 && GetValue('InsertUserID', $Object) == $Session->UserID);
	}
}


if (!function_exists('BuildNode')) {
	/**
	* BuildNode($Object, 'Section')
	* 
	*/
	function BuildNode($Object, $Prefix) {
		$Node = new StdClass();
		$Node->TreeLeft = $Object->{$Prefix.'TreeLeft'};
		$Node->TreeRight = $Object->{$Prefix.'TreeRight'};
		$Node->Depth = $Object->{$Prefix.'Depth'};
		$Node->{$Prefix.'ID'} = $Object->{$Prefix.'ID'};
		$Node->ParentID = property_exists($Object, $Prefix.'ParentID') ? $Object->{$Prefix.'ParentID'} : Null;
		return $Node;
	}
}

if (!function_exists('SectionAnchor')) {
	function SectionAnchor($Node) {
		$Url = GetValue('Url', $Node);
		if (!$Url) {
			$Url = GetValue('URI', $Node);
			if (!$Url) GetValue('RequestUri', $Node);
		}
		$Name = ($Url) ? Anchor($Node->Name, $Url) : $Node->Name;
		return $Name;
	}
}



if (!function_exists('Chunk')) {
	function Chunk($Identify, $Type = 'Textarea') {
		static $ChunkModel; if (is_null($ChunkModel)) $ChunkModel = new ChunkModel();
		static $PermissionChunksEdit; if (is_null($PermissionChunksEdit)) $PermissionChunksEdit = CheckPermission('Candy.Chunks.Edit');
		$Data = $ChunkModel->GetID($Identify);
		if ($Data != False) {
			$String = Gdn_Format::To($Data->Body, $Data->Format);
			if ($Type) {
				$Class = ($PermissionChunksEdit) ? ('Editable Editable'.$Type) : '';
				$String = Wrap($String, 'div', array('class' => $Class, 'id' => 'Chunk'.$Data->ChunkID));
			}
			return $String;
		}
	}
}






