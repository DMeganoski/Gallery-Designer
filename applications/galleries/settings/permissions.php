<?php if (!defined('APPLICATION'))
	exit();

	$SQL = Gdn::SQL();
	$Structure = Gdn::Structure();

	$Database = Gdn::Database();

	$PermissionModel = Gdn::PermissionModel();

	$PermissionModel->Database = $Database;

	$PermissionModel->SQL = $SQL;

	// Define some global permissions.
	$PermissionModel->Define(array(
	'Gallery.Manage',
	'Gallery.Items.Upload',
	'Gallery.Items.Download',
	'Gallery.Items.Manage',
	'Gallery.Docs.Upload',
	'Gallery.Docs.Download',
	'Gallery.Docs.Manage'
	//'Gallery.Comments.Manage'
	));

   if (isset($PermissionTableExists) && $PermissionTableExists) {
   // Set the intial member permissions.
   $PermissionModel->Save(array(
      'RoleID' => 8,
      'Gallery.Items.Upload' => 1,
      'Gallery.Docs.Download' => 1
      ));

        // Set the initial administrator permissions.
	$PermissionModel->Save(array(
		'RoleID' => 16,
        'Gallery.Items.Upload' => 1,
		'Gallery.Items.Download' => 1,
        'Gallery.Items.Manage' => 1,
		'Gallery.Docs.Upload' => 1,
		'Gallery.Docs.Download' => 1,
		'Gallery.Docs.Manage' => 1,
        'Gallery.Manage' => 1,
         //'Gallery.Comments.Manage' => 1
         ));
   }