<?php if (!defined('APPLICATION')) exit();

//Set Classes
$SQL->Replace('GalleryClass', array('ClassLabel' => 'default', 'Visible' => '1'),
        array('ClassKey' => 1), TRUE);

$SQL->Replace('GalleryClass', array('ClassLabel' => 'tins', 'Visible' => '1'),
        array('ClassKey' => 2), TRUE);

$SQL->Replace('GalleryClass', array('ClassLabel' => 'covers', 'Visible' => '1'),
        array('ClassKey' => 3), TRUE);

$SQL->Replace('GalleryClass', array('ClassLabel' => 'designer', 'Visible' => '1'),
        array('ClassKey' => 4), TRUE);

$SQL->Replace('GalleryClass', array('ClassLabel' => 'templates', 'Visible' => '0'),
        array('ClassKey' => 5), TRUE);

$SQL->Replace('GalleryClass', array('ClassLabel' => 'completepackages', 'Visible' => '0'),
        array('ClassKey' => 10), TRUE);

// Set Categories
// Class 1 Categories (default)
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'home', 'Visible' => '1', 'ClassKey' => 1),
	array('CategoryKey' => 101), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'howitworks', 'Visible' => '1', 'ClassKey' => 1),
	array('CategoryKey' => 102), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'pricing', 'Visible' => '1', 'ClassKey' => 1),
	array('CategoryKey' => 103), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'contactus', 'Visible' => '1', 'ClassKey' => 1),
	array('CategoryKey' => 104), TRUE);
// Class 2 Categories (tins)
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'home', 'Visible' => '1', 'ClassKey' => 2),
	array('CategoryKey' => 201), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'platinum', 'Visible' => '1', 'ClassKey' => 2),
	array('CategoryKey' => 202), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'black', 'Visible' => '1', 'ClassKey' => 2),
	array('CategoryKey' => 203), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'gold', 'Visible' => '0', 'ClassKey' => 2),
	array('CategoryKey' => 204), TRUE);
// Class 3 Categories (covers)
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'home', 'Visible' => '1', 'ClassKey' => 3),
	array('CategoryKey' => 301), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'abstract', 'Visible' => '1', 'ClassKey' => 3),
	array('CategoryKey' => 302), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'artnouveau', 'Visible' => '1', 'ClassKey' => 3),
	array('CategoryKey' => 303), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'cityscapes', 'Visible' => '1', 'ClassKey' => 3),
	array('CategoryKey' => 304), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'holiday', 'Visible' => '1', 'ClassKey' => 3),
	array('CategoryKey' => 305), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'impressionism', 'Visible' => '1', 'ClassKey' => 3),
	array('CategoryKey' => 306), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'industry', 'Visible' => '1', 'ClassKey' => 3),
	array('CategoryKey' => 307), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'nature', 'Visible' => '1', 'ClassKey' => 3),
	array('CategoryKey' => 308), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'realism', 'Visible' => '1', 'ClassKey' => 3),
	array('CategoryKey' => 309), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'renaissance', 'Visible' => '1', 'ClassKey' => 3),
	array('CategoryKey' => 310), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'romanticism', 'Visible' => '1', 'ClassKey' => 3),
	array('CategoryKey' => 311), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'space', 'Visible' => '1', 'ClassKey' => 3),
	array('CategoryKey' => 312), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'textures', 'Visible' => '1', 'ClassKey' => 3),
	array('CategoryKey' => 313), TRUE);
// Class 4 Categories (builder)
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'home', 'Visible' => '1', 'ClassKey' => 4),
	array('CategoryKey' => 401), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'text', 'Visible' => '1', 'ClassKey' => 4),
	array('CategoryKey' => 402), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'resize', 'Visible' => '1', 'ClassKey' => 4),
	array('CategoryKey' => 403), TRUE);
// Class 5 Categories (templates)
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'home', 'Visible' => '1', 'ClassKey' => 5),
	array('CategoryKey' => 501), TRUE);
$SQL->Replace('GalleryCategory',array ('CategoryLabel' => 'requirements', 'Visible' => '1', 'ClassKey' => 5),
	array('CategoryKey' => 502), TRUE);