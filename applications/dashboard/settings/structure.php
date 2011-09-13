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
$Construct = Gdn::Structure();
$Px = $Construct->DatabasePrefix();

$Construct->Table('AddonType')
   ->PrimaryKey('AddonTypeID')
   ->Column('Label', 'varchar(50)')
   ->Column('Visible', 'tinyint(1)', '1')
   ->Set($Explicit, $Drop);

$SQL->Replace('AddonType', array('Label' => 'Plugin', 'Visible' => '1'), array('AddonTypeID' => 1), TRUE);
$SQL->Replace('AddonType', array('Label' => 'Theme', 'Visible' => '1'), array('AddonTypeID' => 2), TRUE);
$SQL->Replace('AddonType', array('Label' => 'Style', 'Visible' => '0'), array('AddonTypeID' => 3), TRUE);
$SQL->Replace('AddonType', array('Label' => 'Locale', 'Visible' => '1'), array('AddonTypeID' => 4), TRUE);
$SQL->Replace('AddonType', array('Label' => 'Application', 'Visible' => '1'), array('AddonTypeID' => 5), TRUE);
$SQL->Replace('AddonType', array('Label' => 'Core', 'Visible' => '1'), array('AddonTypeID' => 10), TRUE);

$Construct->Table('Addon');
$Description2Exists = $Construct->ColumnExists('Description2');

$Construct->PrimaryKey('AddonID')
   ->Column('CurrentAddonVersionID', 'int', TRUE, 'key')
   ->Column('AddonKey', 'varchar(50)', NULL, 'index')
   ->Column('AddonTypeID', 'int', FALSE, 'key')
   ->Column('InsertUserID', 'int', FALSE, 'key')
   ->Column('UpdateUserID', 'int', TRUE)
   ->Column('Name', 'varchar(100)')
   ->Column('Icon', 'varchar(200)', TRUE)
   ->Column('Description', 'text', TRUE)
   ->Column('Description2', 'text', NULL)
   ->Column('Requirements', 'text', TRUE)
   ->Column('CountComments', 'int', '0')
   ->Column('CountDownloads', 'int', '0')
   ->Column('Visible', 'tinyint(1)', '1')
   ->Column('Vanilla2', 'tinyint(1)', '1')
   ->Column('DateInserted', 'datetime')
   ->Column('DateUpdated', 'datetime', TRUE)
   ->Column('Checked', 'tinyint(1)', '0')
   ->Set($Explicit, $Drop);

if (!$Description2Exists) {
   $Construct->Query("update {$Px}Addon set Description2 = Description where Checked = 0");
}

/*
$Construct->Table('AddonComment')
   ->PrimaryKey('AddonCommentID')
   ->Column('AddonID', 'int', FALSE, 'key')
   ->Column('InsertUserID', 'int', FALSE, 'key')
   ->Column('Body', 'text')
   ->Column('Format', 'varchar(20)', TRUE)
   ->Column('DateInserted', 'datetime')
   ->Set($Explicit, $Drop);
*/

$Construct->Table('AddonVersion')
   ->PrimaryKey('AddonVersionID')
   ->Column('AddonID', 'int', FALSE, 'key')
   ->Column('File', 'varchar(200)', TRUE)
   ->Column('Version', 'varchar(20)')
   ->Column('TestedWith', 'text', NULL)
   ->Column('FileSize', 'int', NULL)
   ->Column('MD5', 'varchar(32)')
   ->Column('Notes', 'text', NULL)
   ->Column('Format', 'varchar(10)', 'Html')
   ->Column('InsertUserID', 'int', FALSE, 'key')
   ->Column('DateInserted', 'datetime')
   ->Column('DateReviewed', 'datetime', TRUE)
   ->Column('Checked', 'tinyint(1)', '0')
   ->Column('Deleted', 'tinyint(1)', '0')
   ->Set($Explicit, $Drop);

$Construct->Table('AddonPicture')
   ->PrimaryKey('AddonPictureID')
   ->Column('AddonID', 'int', FALSE, 'key')
   ->Column('File', 'varchar(200)')
   ->Column('DateInserted', 'datetime')
   ->Set($Explicit, $Drop);

$Construct->Table('Download')
   ->PrimaryKey('DownloadID')
   ->Column('AddonID', 'int', FALSE, 'key')
   ->Column('DateInserted', 'datetime')
   ->Column('RemoteIp', 'varchar(50)', TRUE)
   ->Set($Explicit, $Drop);

$Construct->Table('UpdateCheckSource')
   ->PrimaryKey('SourceID')
   ->Column('Location', 'varchar(255)', TRUE)
   ->Column('DateInserted', 'datetime', TRUE)
   ->Column('RemoteIp', 'varchar(50)', TRUE)
   ->Set($Explicit, $Drop);

$Construct->Table('UpdateCheck')
   ->PrimaryKey('UpdateCheckID')
   ->Column('SourceID', 'int', FALSE, 'key')
   ->Column('CountUsers', 'int', '0')
   ->Column('CountDiscussions', 'int', '0')
   ->Column('CountComments', 'int', '0')
   ->Column('CountConversations', 'int', '0')
   ->Column('CountConversationMessages', 'int', '0')
   ->Column('DateInserted', 'datetime')
   ->Column('RemoteIp', 'varchar(50)', TRUE)
   ->Set($Explicit, $Drop);

// Need to use this table instead of linking directly with the Addon table
// because we might not have all of the addons being checked for.
$Construct->Table('UpdateAddon')
   ->PrimaryKey('UpdateAddonID')
   ->Column('AddonID', 'int', FALSE, 'key')
   ->Column('Name', 'varchar(255)', TRUE)
   ->Column('Type', 'varchar(255)', TRUE)
   ->Column('Version', 'varchar(255)', TRUE)
   ->Set($Explicit, $Drop);

$Construct->Table('UpdateCheckAddon')
   ->Column('UpdateCheckID', 'int', FALSE, 'key')
   ->Column('UpdateAddonID', 'int', FALSE, 'key')
   ->Set($Explicit, $Drop);

$PermissionModel = Gdn::PermissionModel();
$PermissionModel->Database = $Database;
$PermissionModel->SQL = $SQL;

// Define some global addon permissions.
$PermissionModel->Define(array(
   'Addons.Addon.Add',
   'Addons.Addon.Manage',
   'Addons.Comments.Manage'
   ));

if (isset($$PermissionTableExists) && $PermissionTableExists) {
   // Set the intial member permissions.
   $PermissionModel->Save(array(
      'RoleID' => 8,
      'Addons.Addon.Add' => 1
      ));

   // Set the initial administrator permissions.
   $PermissionModel->Save(array(
      'RoleID' => 16,
      'Addons.Addon.Add' => 1,
      'Addons.Addon.Manage' => 1,
      'Addons.Comments.Manage' => 1
      ));
}

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
if ($SQL->GetWhere('ActivityType', array('Name' => 'AddAddon'))->NumRows() == 0)
   $SQL->Insert('ActivityType', array('AllowComments' => '0', 'Name' => 'AddAddon', 'FullHeadline' => '%1$s uploaded a new %8$s.', 'ProfileHeadline' => '%1$s uploaded a new %8$s.', 'RouteCode' => 'addon', 'Public' => '1'));

// X edited an addon
if ($SQL->GetWhere('ActivityType', array('Name' => 'EditAddon'))->NumRows() == 0)
   $SQL->Insert('ActivityType', array('AllowComments' => '0', 'Name' => 'EditAddon', 'FullHeadline' => '%1$s edited an %8$s.', 'ProfileHeadline' => '%1$s edited an %8$s.', 'RouteCode' => 'addon', 'Public' => '1'));

/*
// People's comments on addons
if ($SQL->GetWhere('ActivityType', array('Name' => 'AddonComment'))->NumRows() == 0)
   $SQL->Insert('ActivityType', array('AllowComments' => '0', 'Name' => 'AddonComment', 'FullHeadline' => '%1$s commented on %4$s %8$s.', 'ProfileHeadline' => '%1$s commented on %4$s %8$s.', 'RouteCode' => 'addon', 'Notify' => '1', 'Public' => '1'));

// People mentioning others in addon comments
if ($SQL->GetWhere('ActivityType', array('Name' => 'AddonCommentMention'))->NumRows() == 0)
   $SQL->Insert('ActivityType', array('AllowComments' => '0', 'Name' => 'AddonCommentMention', 'FullHeadline' => '%1$s mentioned %3$s in a %8$s.', 'ProfileHeadline' => '%1$s mentioned %3$s in a %8$s.', 'RouteCode' => 'comment', 'Notify' => '1', 'Public' => '0'));
*/

// People adding new language definitions
if ($SQL->GetWhere('ActivityType', array('Name' => 'AddUserLanguage'))->NumRows() == 0)
   $SQL->Insert('ActivityType', array('AllowComments' => '0', 'Name' => 'AddUserLanguage', 'FullHeadline' => '%1$s added a new %8$s.', 'ProfileHeadline' => '%1$s added a new %8$s.', 'RouteCode' => 'language', 'Notify' => '0', 'Public' => '1'));

// People editing language definitions
if ($SQL->GetWhere('ActivityType', array('Name' => 'EditUserLanguage'))->NumRows() == 0)
   $SQL->Insert('ActivityType', array('AllowComments' => '0', 'Name' => 'EditUserLanguage', 'FullHeadline' => '%1$s edited a %8$s.', 'ProfileHeadline' => '%1$s edited a %8$s.', 'RouteCode' => 'language', 'Notify' => '0', 'Public' => '1'));

// Contains list of available languages for translating
$Construct->Table('Language')
   ->PrimaryKey('LanguageID')
   ->Column('Name', 'varchar(255)')
   ->Column('Code', 'varchar(10)')
   ->Column('InsertUserID', 'int', FALSE, 'key')
   ->Column('DateInserted', 'datetime')
   ->Column('UpdateUserID', 'int', TRUE)
   ->Column('DateUpdated', 'datetime', TRUE)
   ->Set($Explicit, $Drop);

// Contains relationships of who owns translations and who can edit translations (owner decides who can edit)
$Construct->Table('UserLanguage')
   ->PrimaryKey('UserLanguageID')
   ->Column('UserID', 'int', FALSE, 'key')
   ->Column('LanguageID', 'int', FALSE, 'key')
   ->Column('Owner', 'tinyint(1)', '0')
   ->Column('CountTranslations', 'int', '0') // The number of translations this UserLanguage contains
   ->Column('CountDownloads', 'int', '0')
   ->Column('CountLikes', 'int', '0')
   ->Set($Explicit, $Drop);

// Contains individual translations as well as source codes
$Construct->Table('Translation')
   ->PrimaryKey('TranslationID')
   ->Column('UserLanguageID', 'int', FALSE, 'key')
   ->Column('SourceTranslationID', 'int', TRUE, 'key') // This is the related TranslationID where LanguageID = 1 (the source codes for translations)
   ->Column('Application', 'varchar(100)', TRUE)
   ->Column('Value', 'text')
   ->Column('InsertUserID', 'int', FALSE, 'key')
   ->Column('DateInserted', 'datetime')
   ->Column('UpdateUserID', 'int', TRUE)
   ->Column('DateUpdated', 'datetime', TRUE)
   ->Set($Explicit, $Drop);

// Contains records of when actions were performed on userlanguages (ie. it is
// downloaded or "liked"). These values are aggregated in
// UserLanguage.CountLikes and UserLanguage.CountDownloads for faster querying,
// but saved here for reporting.
$Construct->Table('UserLanguageAction')
   ->PrimaryKey('UserLanguageActionID')
   ->Column('UserLanguageID', 'int', FALSE, 'key')
   ->Column('Action', 'varchar(20)') // The action being performed (ie. "download" or "like")
   ->Column('InsertUserID', 'int', TRUE, 'key') // Allows nulls because you do not need to be authenticated to download a userlanguage
   ->Column('DateInserted', 'datetime')
   ->Set($Explicit, $Drop);

// Make sure the default "source" translation exists
if ($SQL->GetWhere('Language', array('LanguageID' => 1))->NumRows() == 0)
   $SQL->Insert('Language', array('Name' => 'Source Codes', 'Code' => 'SOURCE', 'InsertUserID' => 1, 'DateInserted' => '2009-10-19 12:00:00'));

// Mark (UserID 1) owns the source translation
if ($SQL->GetWhere('UserLanguage', array('LanguageID' => 1, 'UserID' => 1))->NumRows() == 0)
   $SQL->Insert('UserLanguage', array('LanguageID' => 1, 'UserID' => 1, 'Owner' => '1'));


/*
   Apr 26th, 2010
   Changed all "enum" fields representing "bool" (0 or 1) to be tinyint.
   For some reason mysql makes 0's "2" during this change. Change them back to "0".
*/
if (!$Construct->CaptureOnly) {
	$SQL->Query("update GDN_AddonType set Visible = '0' where Visible = '2'");

	$SQL->Query("update GDN_Addon set Visible = '0' where Visible = '2'");
	$SQL->Query("update GDN_Addon set Vanilla2 = '0' where Vanilla2 = '2'");

	$SQL->Query("update GDN_UserLanguage set Owner = '0' where Owner = '2'");
}


// Add AddonID column to discussion table for allowing discussions on addons.
$Construct->Table('Discussion')
   ->Column('AddonID', 'int', NULL)
   ->Set();

// Insert all of the existing comments into a new discussion for each addon
$Construct->Table('AddonComment');
$AddonCommentExists = $Construct->TableExists();
$Construct->Reset();

if ($AddonCommentExists) {
   if ($SQL->Query('select AddonCommentID from GDN_AddonComment')->NumRows() > 0) {
      // Create discussions for addons with comments
      $SQL->Query("insert into GDN_Discussion
      (AddonID, InsertUserID, UpdateUserID, LastCommentID, Name, Body, Format,
      CountComments, DateInserted, DateUpdated, DateLastComment, LastCommentUserID)
      select distinct a.AddonID, a.InsertUserID, a.UpdateuserID, 0, a.Name, a.Name,
      ac.Format, a.CountComments, a.DateInserted, a.DateUpdated, a.DateUpdated, 0
      from GDN_Addon a join GDN_AddonComment ac on a.AddonID = ac.AddonID");

      // Copy the comments across to the comment table
      $SQL->Query("insert into GDN_Comment
      (DiscussionID, InsertUserID, Body, Format, DateInserted)
      select d.DiscussionID, ac.InsertUserID, ac.Body, ac.Format, ac.DateInserted
      from GDN_Discussion d join GDN_AddonComment ac on d.AddonID = ac.AddonID");

      // Update the LastCommentID
      $SQL->Query("update GDN_Discussion d
         join (
           select DiscussionID, max(CommentID) as LastCommentID
           from GDN_Comment
           group by DiscussionID
         ) c
           on d.DiscussionID = c.DiscussionID
         set d.LastCommentID = c.LastCommentID");

      // Update the LastCommentUserID
      $SQL->Query("update GDN_Discussion d
         join GDN_Comment c on d.LastCommentID = c.CommentID
         set d.LastCommentUserID = c.InsertUserID");


      // Delete the comments from the addon comments table
      $SQL->Query('truncate table GDN_AddonComment');
   }
}
