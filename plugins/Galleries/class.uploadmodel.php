<?php if (!defined('APPLICATION')) exit();
/*
Copyright 2008, 2009 Vanilla Forums Inc.
This file is part of Garden.
Garden is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Garden is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Garden.  If not, see <http://www.gnu.org/licenses/>.
Contact Vanilla Forums Inc. at support [at] vanillaforums [dot] com
*/

class UploadModel extends VanillaModel {
   
   public function __construct() {
      parent::__construct('Upload');
   }

   public function GetSlug($ItemSlug) {
      $this->FireEvent('BeforeGetSlug');
      $Data = $this->SQL
         ->Select('gi.*')
         //->Select('iu.*')
         ->From('GalleryItems gi')
         //->Join('User iu', 'm.InsertUserID = iu.UserID', 'left') // Insert user
         ->Where('gi.Slug', $ItemSlug)
         ->Get()
         ->FirstRow();

		return $Data;
   }

   public static function GetImageSize($Path) {
      if (!in_array(strtolower(pathinfo($Path, PATHINFO_EXTENSION)), array('bmp', 'gif', 'jpg', 'jpeg', 'png')))
         return array(0, 0);

      $ImageSize = @getimagesize($Path);
      if (is_array($ImageSize))
         return array($ImageSize[0], $ImageSize[1]);
      return array(0, 0);
   }

   /*
    * Not Needed, for adding file data to discussions
    */
   public function PreloadDiscussionMedia($DiscussionID, $CommentIDList) {
      $this->FireEvent('BeforePreloadDiscussionMedia');

      $StartT = microtime(true);
      $Data = $this->SQL
         ->Select('m.*')
         ->From('Media m')
         ->BeginWhereGroup()
            ->Where('m.ForeignID', $DiscussionID)
            ->Where('m.ForeignTable', 'discussion')
         ->EndWhereGroup()
         ->OrOp()
         ->BeginWhereGroup()
            ->WhereIn('m.ForeignID', $CommentIDList)
            ->Where('m.ForeignTable', 'comment')
         ->EndWhereGroup()
         ->Get();

      // Assign image heights/widths where necessary.
      $Data2 = $Data->Result();
      foreach ($Data2 as &$Row) {
         if ($Row->ImageHeight === NULL || $Row->ImageWidth === NULL) {
            list($Row->ImageWidth, $Row->ImageHeight) = self::GetImageSize(MediaModel::PathUploads().'/'.ltrim($Row->Path, '/'));
            $this->SQL->Put('Media', array('ImageWidth' => $Row->ImageWidth, 'ImageHeight' => $Row->ImageHeight), array('MediaID' => $Row->MediaID));
         }
      }
/*
      $DiscussionData = $this->SQL
         ->Select('m.*')
         ->From('Media m')
         ->Where('m.ForeignID', $DiscussionID)
         ->Where('m.ForeignTable', 'discussion')
         ->Get()->Result(DATASET_TYPE_ARRAY);

      $CommentData = $this->SQL
         ->Select('m.*')
         ->From('Media m')
         ->WhereIn('m.ForeignID', $CommentIDList)
         ->Where('m.ForeignTable', 'comment')
         ->Get()->Result(DATASET_TYPE_ARRAY);

      $Data = array_merge($DiscussionData, $CommentData);
*/

		return $Data;
   }

   public function Delete($Item, $DeleteFile = TRUE) {
      $ItemKey = FALSE;
      if (is_a($Item, 'stdClass'))
         $Item = (array)$Item;

      if (is_numeric($Item))
         $ItemKey = $Item;
      elseif (array_key_exists('ItemKey', $Item))
         $ItemKey = $Item['ItemKey'];

      if ($ItemKey) {
         $Item = $this->GetID($ItemKey);
         $this->SQL->Delete($this->Name, array('ItemKey' => $ItemKey), FALSE);

         if ($DeleteFile) {
            $DirectPath = UploadModel::PathUploads().DS.GetValue('Path',$Item);
            if (file_exists($DirectPath))
               @unlink($DirectPath);
         }
      } else {
         $this->SQL->Delete($this->Name, $Item, FALSE);
      }
   }

   public function DeleteParent($ParentTable, $ParentID) {
      $UploadItems = $this->SQL->Select('*')
         ->From($this->Name)
         ->Where('ForeignTable', strtolower($ParentTable))
         ->Where('ForeignID', $ParentID)
         ->Get()->Result(DATASET_TYPE_ARRAY);

      foreach ($UploadItems as $Item) {
         $this->Delete(GetValue('ItemKey',$Item));
      }
   }

   public static function PathUploads() {
      if (defined('PATH_LOCAL_UPLOADS'))
         return PATH_LOCAL_UPLOADS;
      else
         return PATH_UPLOADS;
   }

   public static function ThumbnailHeight() {
      static $Height = FALSE;

      if ($Height === FALSE)
         $Height = C('Plugins.Galleries.ThumbnailHeight', 128);
      return $Height;
   }

   public static function ThumbnailWidth() {
      static $Width = FALSE;

      if ($Width === FALSE)
         $Width = C('Plugins.Galleries.ThumbnailWidth', 256);
      return $Width;
   }

   public static function ThumbnailUrl($Item) {
      $Width = GetValue('ImageWidth', $Item);
      $Height = GetValue('ImageHeight', $Item);

      if (!$Width || !$Height)
         return '/plugins/FileUpload/images/file.png';

      $RequiresThumbnail = FALSE;
      if (self::ThumbnailHeight() && $Height > self::ThumbnailHeight())
         $RequiresThumbnail = TRUE;
      elseif (self::ThumbnailWidth() && $Width > self::ThumbnailWidth())
         $RequiresThumbnail = TRUE;

      $Path = ltrim(GetValue('Path', $Item), '/');
      if ($RequiresThumbnail) {
         $ThumbPath = Upload::PathUploads()."/thumbnails/$Path";
         if (file_exists(UploadModel::PathUploads()."/thumbnails/$Path"))
            $Result = "/uploads/thumbnails/$Path";
         else
            $Result = "/utility/thumbnail/$Path";
      } else {
         $Result = "/uploads/$Path";
      }
      return $Result;
   }

   public static function Url($Item) {
      static $UseDownloadUrl = NULL;
      if ($UseDownloadUrl === NULL)
         $UseDownloadUrl = C('Plugins.FileUpload.UseDownloadUrl');

      if (is_string($Item)) {
         $SubPath = $Item;
         if (method_exists('Gdn_Upload', 'Url'))
            $Url = Gdn_Upload::Url("/$SubPath");
         else
            $Url = "/uploads/$SubPath";
      } elseif ($UseDownloadUrl) {
         $Url = '/discussion/download/'.GetValue('ItemKey', $Item).'/'.rawurlencode(GetValue('Name', $Item));
      } else {
         $SubPath = ltrim(GetValue('Path', $Item), '/');
         if (method_exists('Gdn_Upload', 'Url'))
            $Url = Gdn_Upload::Url("/$SubPath");
         else
            $Url = "/uploads/$SubPath";
      }

      return $Url;
   }

}