<?php if (!defined('APPLICATION')) exit();

if (!isset($Drop)) $Drop = False;
if (!isset($Explicit)) $Explicit = True;

$Version = C('Candy.Version');

$Database = Gdn::Database();
$Px = $Database->DatabasePrefix;
$SQL = $Database->SQL(); // To run queries.
$Construct = $Database->Structure(); // To modify and add database tables.
$Validation = new Gdn_Validation(); // To validate permissions (if necessary).

Gdn::Structure()
	->Table('Chunk')
	->PrimaryKey('ChunkID', 'usmallint')
	->Column('Name', 'varchar(80)')
	->Column('Body', 'text', True)
	->Column('Format', 'varchar(20)', True)
	->Column('Url', 'varchar(80)', True)
	->Column('InsertUserID', 'int', False)
	->Column('DateInserted', 'datetime')
	->Column('UpdateUserID', 'int', True)
	->Column('DateUpdated', 'datetime', True)
	->Engine('MyISAM')
	->Set($Explicit, $Drop);
	
Gdn::Structure()
	->Table('Route')
	->Column('URI', 'char(80)', False, 'primary')
	->Column('RequestUri', 'char(120)')
	->Engine('MyISAM')
	->Set($Explicit, $Drop);
	
Gdn::Structure()
	->Table('Section')
	->PrimaryKey('SectionID', 'usmallint')
	->Column('TreeLeft', 'usmallint', 0)
	->Column('TreeRight', 'usmallint', 0)
	->Column('Depth', 'utinyint', 0)
	->Column('ParentID', 'usmallint', 0)
	->Column('Name', 'varchar(120)')
	->Column('Url', 'varchar(80)', True) // backup for URI (for subdomains, etc.)
	->Column('URI', 'varchar(80)', True)
	->Column('RequestUri', 'char(120)', True)
	->Engine('InnoDB')
	->Set($Explicit, $Drop);

$HasRoot = False;
try {
	$HasRoot = ($SQL->GetCount('Section', array('SectionID' => 1)) > 0);
} catch (Exception $Ex) {
}
if (!$HasRoot) $SQL->Insert('Section', array('SectionID' => 1, 'TreeLeft' => 1, 'TreeRight' => 2, 'Depth' => 0, 'Name' => T('Home')));

Gdn::Structure()
	->Table('Page')
	->PrimaryKey('PageID', 'usmallint')
	->Column('SectionID', 'usmallint', True, 'index')
	->Column('Title', 'varchar(200)')
	->Column('Body', 'text', True)
	->Column('Format', 'varchar(20)', 'xHtml')
	->Column('Visible', 'tinyint(1)', 0)
	->Column('URI', 'varchar(80)', True) // copy of Route.URI
	//->Column('Url', 'varchar(80)', True) // backup for URI (for subdomains, etc.)
	->Column('Tags', 'varchar(250)', True)
	->Column('MasterView', 'varchar(30)', True)
	->Column('View', 'varchar(30)', True)
	->Column('Sort', 'smallint', 0)
	->Column('InsertUserID', 'int')
	->Column('DateInserted', 'datetime')
	->Column('UpdateUserID', 'int', True)
	->Column('DateUpdated', 'datetime', True)
	->Column('MetaDescription', 'varchar(500)', True)
	->Column('MetaKeywords', 'varchar(250)', True)
	->Column('MetaRobots', 'varchar(150)', True)
	->Engine('MyISAM')
	->Set($Explicit, $Drop);
	
// Set route
if (!Gdn::Router()->GetRoute('map')) Gdn::Router()->SetRoute('map', 'candy/content/map', 'Internal');

$PermissionModel = Gdn::PermissionModel();

$PermissionModel->Define(array(
	//'Candy.Settings.Manage',
	'Candy.Settings.View',

	'Candy.Sections.Edit',
	'Candy.Sections.Add',
	'Candy.Sections.Delete',
	'Candy.Sections.Move',
	'Candy.Sections.Swap',

	'Candy.Pages.Add',
	'Candy.Pages.Edit',
	'Candy.Pages.Delete',


	'Candy.Chunks.Edit',
	'Candy.Chunks.Delete',
	
	'Candy.Routes.Manage',

));


$PermissionModel->Save(array(
	'RoleID' => 16,
	//'Candy.Settings.Manage' => 1,
	'Candy.Settings.View' => 1,
));



/*$PermissionModel->Define(array(
),
	'tinyint',
	'Section',
	'SectionID'
);*/



















