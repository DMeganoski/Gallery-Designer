<?php if (!defined('APPLICATION')) exit();
$ActiveClass = GalleryController::$Class;
$ClassDirectory = PATH_UPLOADS.DS.'item'.DS.$ActiveClass;
$ActiveCategory = GalleryController::$Category;
// overridden in galleryside.php, still needed?
$Classes = $this->GetClasses();
$PublicDir = GalleryController::$PublicDir;
$Limit = GalleryController::$Limit;
$Page = GalleryController::$Page;
$Offset = (($Page - 1) * $Limit);
//$LimitedFiles = array_slice($AllFiles, $Offset, $Limit, TRUE);
$ShortCat = substr($ActiveCategory, 0, 3);
$CapsCat = strtoupper($ShortCat);

$AllFiles = $this->GalleryItemModel->Get($Offset, $Limit, array('CategoryCAPS' => $CapsCat));
$NextPage = ($Page + 1);
$PreviousPage = ($Page -1);
$OffsetLess = $Offset - $Limit;
$OffsetMore = $Offset + $Limit;
$Count = $this->Count;
$PageMax = ceil($Count / $Limit);