<?php if (!defined('APPLICATION')) exit(); // Make sure this file can't get accessed directly
// Use this file to do any database changes for your application.

if (!isset($Drop))
   $Drop = FALSE; // Safe default - Set to TRUE to drop the table if it already exists.

if (!isset($Explicit))
   $Explicit = FALSE; // Safe default - Set to TRUE to remove all other columns from table.

$Database = Gdn::Database();
$SQL = Gdn::SQL(); // To run queries.
$Construct = Gdn::Structure(); // To modify and add database tables.
$Validation = new Gdn_Validation(); // To validate permissions (if necessary).

// Add your tables or new columns under here (see example below).

$Construct->Table('Project')
		->PrimaryKey('ProjectKey')
        ->Column('UserID', 'int', TRUE)
        ->Column('ProjectName', 'varchar(64)', TRUE)
		->Column('Selected', 'varchar(255)', TRUE)
		->Column('BackgroundPosition', 'varchar(50)', TRUE)
		->Column('Included', 'varchar(255)', TRUE)
		->Column('TopPositions', 'varchar(255)', TRUE)
		->Column('LeftPositions', 'varchar(255)', TRUE)
		->Column('Message', 'varchar(255)', TRUE)
		->Column('MessageStyle', 'varchar(50)', TRUE)
		->Column('Order', 'varchar(100)', TRUE)
		->Column('CurrentProject', 'tinyint(1)', TRUE)
		->Column('ProjectStage', 'tinyint(9)', 0)
        ->Set(FALSE, FALSE);

// Example: New table construction.
/*
$Construct->Table('ExampleTable')
	->PrimaryKey('ExampleTableID')
   ->Column('ExampleUserID', 'int', TRUE)
   ->Column('Field1', 'varchar(50)')
   ->Set($Explicit, $Drop); // If you omit $Explicit and $Drop they default to false.
*/

// Example: Add column to existing table.

$Construct->Table('User')
   ->Column('CurrentProjectID', 'varchar(255)', TRUE) // Always allow for NULLs unless it's truly required.
   ->Set();

	$Database = Gdn::Database();

	$PermissionModel = Gdn::PermissionModel();

	$PermissionModel->Database = $Database;

	$PermissionModel->SQL = $SQL;

	// Define some global permissions.
	$PermissionModel->Define(array(
	'Projects.Projects.Manage'
	));

   // Set the intial member permissions.
   $PermissionModel->Save(array(
      'RoleID' => 8,

      ));

        // Set the initial administrator permissions.
	$PermissionModel->Save(array(
		'RoleID' => 16,
	'Projects.Projects.Manage' => 1
         //'Gallery.Comments.Manage' => 1
         ));


/**
 * Column() has the following arguments:
 *
 * @param string $Name Name of the column to create.
 * @param string $Type Data type of the column. Length may be specified in parenthesis.
 *    If an array is provided, the type will be set as "enum" and the array's values will be assigned as the column's enum values.
 * @param string $NullOrDefault Default is FALSE. Whether or not nulls are allowed, if not a default can be specified.
 *    TRUE: Nulls allowed. FALSE: Nulls not allowed. Any other value will be used as the default (with nulls disallowed).
 * @param string $KeyType Default is FALSE. Type of key to make this column. Options are: primary, key, or FALSE (not a key).
 *
 * @see /library/database/class.generic.structure.php
 */
