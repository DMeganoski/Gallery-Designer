<?php if (!defined('APPLICATION')) exit();
/*
Copyright 2008, 2009 Vanilla Forums Inc.
This file is part of Garden.
Garden is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Garden is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Garden.  If not, see <http://www.gnu.org/licenses/>.
Contact Vanilla Forums Inc. at support [at] vanillaforums [dot] com
*/

// Use this file to construct tables and views necessary for your application.
// There are some examples below to get you started.

if (!isset($Drop))
   $Drop = FALSE;

if (!isset($Explicit))
   $Explicit = TRUE;

$SQL = Gdn::SQL();
$Structure = Gdn::Structure();

	$Structure->Table('GalleryItem')
		->PrimaryKey('ItemKey')
		->Column('ItemID', 'varchar(3)')
		->Column('Slug', 'varchar(7)', TRUE)
		->Column('ClassLabel', 'varchar(20)')
		// Need the first 3 letters of the category to match file names, in caps
		// Need the key to match classes with categories.
		->Column('CategoryKey', 'varchar(4)', TRUE)
		->Column('CategoryCAPS', 'varchar(3)')
		->Column('Name', 'varchar(100)', TRUE)
		// The columns for the different image sizes.
		// There's got to be a better way to do this...
		->Column('Large', 'varchar(50)', TRUE)
		->Column('FileName', 'varchar(50)')
	    // Just need the label of the class
		->Column('PriceLabel', 'varchar(10)', TRUE)
		->Column('BaseLidPrice', 'varchar(10)', TRUE)
		->Column('Width', 'varchar(15)', TRUE)
		->Column('Height', 'varchar(15)', TRUE)
		->Column('Depth', 'varchar(15)', TRUE)
		->Column('Volume', 'varchar(15)', TRUE)
		->Column('License', 'varchar(50)', TRUE)
		->Column('License', 'varchar(50)', TRUE)
		->Column('Artist', 'varchar(50)', TRUE)
		->Column('Visible', 'tinyint(1)', '1')
		->Column('DateInserted', 'datetime', TRUE)
        //->Column('CountComments', 'int', '0')
		->Column('CountUses', 'int', '0')
		->Column('Description', 'text', TRUE)

        ->Set(FALSE, FALSE); // If you omit $Explicit and $Drop they default to false.

	$Structure->Table('GalleryUpload')
			->PrimaryKey('UploadKey')
			->Column('FileName', 'varchar(50)')
			->Column('InsertUserID', 'varchar(50)', TRUE)
			->Column('Description', 'varchar(100)', TRUE)
			->Column('Thumbnail', 'varchar(50)', TRUE)
			->Set(FALSE,FALSE);

   $Structure->Table('GalleryClass')
        ->PrimaryKey('ClassKey')
        ->Column('ClassLabel', 'varchar(50)')
        ->Column('Visible', 'tinyint(1)', '1')
        ->Set(FALSE,FALSE);

   $Structure->Table('GalleryCategory')
        ->PrimaryKey('CategoryKey')
        ->Column('ClassKey', 'varchar(50)')
        ->Column('CategoryLabel', 'varchar(50)')
        ->Column('Visible', 'tinyint(1)', '1')
        ->Set(FALSE, FALSE);

// Now include the custom classes and their respective categories.

include_once(PATH_APPLICATIONS.DS.'galleries/customfiles/categoryconfig.php');

	$Database = Gdn::Database();

	$PermissionModel = Gdn::PermissionModel();

	$PermissionModel->Database = $Database;

	$PermissionModel->SQL = $SQL;

	// Define some global permissions.
	$PermissionModel->Define(array(
	'Gallery.Items.Manage',
	'Gallery.Items.Upload',
	'Gallery.Docs.Download',
	'Gallery.Docs.Manage'
	//'Gallery.Comments.Manage'
	));

   // Set the intial member permissions.
   $PermissionModel->Save(array(
      'RoleID' => 8,
	'Gallery.Items.Upload' => 1,
	'Gallery.Docs.Download' => 1,
      ));

        // Set the initial administrator permissions.
	$PermissionModel->Save(array(
		'RoleID' => 16,
	'Gallery.Items.Upload' => 1,
	'Gallery.Items.Manage' => 1,
	'Gallery.Docs.Download' => 1,
	'Gallery.Docs.Manage' => 1
         //'Gallery.Comments.Manage' => 1
         ));

// Make sure that User.Permissions is blank so new permissions for users get applied.
//$SQL->Update('User', array('Permissions' => ''))->Put(); // done in PermissionModel::Save()

// Insert some activity types
///  %1 = ActivityName
///  %2 = ActivityName Possessive
///  %3 = RegardingName
///  %4 = RegardingName Possessive
///  %5 = Link to RegardingName's Wall
///  %6 = his/her
///  %7 = he/she
///  %8 = RouteCode & Route

// X added an addon
if ($SQL->GetWhere('ActivityType', array('Name' => 'UploadItem'))->NumRows() == 0)
   $SQL->Insert('ActivityType', array('AllowComments' => '0', 'Name' => 'UploadItem', 'FullHeadline' => '%1$s uploaded a new %8$s.', 'ProfileHeadline' => '%1$s uploaded a new %8$s.', 'RouteCode' => 'item', 'Public' => '0'));

// X edited an addon
if ($SQL->GetWhere('ActivityType', array('Name' => 'EditItem'))->NumRows() == 0)
   $SQL->Insert('ActivityType', array('AllowComments' => '0', 'Name' => 'EditItem', 'FullHeadline' => '%1$s edited an %8$s.', 'ProfileHeadline' => '%1$s edited an %8$s.', 'RouteCode' => 'item', 'Public' => '1'));