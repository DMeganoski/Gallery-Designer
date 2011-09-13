<?php if (!defined('APPLICATION')) exit();
$ActiveClass = GalleriesPlugin::$Class;
$ClassDirectory = PATH_UPLOADS.DS.'item'.DS.$ActiveClass;
$ActiveCategory = GalleriesPlugin::$Category;
// overridden in galleryside.php, still needed?
$Categories = GalleriesModel::GetCategories($ActiveClass);
// used in item.php
$Classes = GalleriesModel::GetClasses();
$PublicDir = GalleriesPLugin::$PublicDir;
$AllFiles = GalleriesModel::GetFilesInfo($ActiveClass, $ActiveCategory);
$Limit = GalleriesPlugin::$Limit;
$Page = GalleriesPlugin::$Page;
$Offset = (($Page - 1) * $Limit);
$LimitedFiles = array_slice($AllFiles, $Offset, $Limit, TRUE);
$NextPage = ($Page + 1);
$PreviousPage = ($Page -1);
$OffsetLess = $Offset - $Limit;
$OffsetMore = $Offset + $Limit;
$Count = count($AllFiles);
$PageMax = ceil($Count / $Limit);
