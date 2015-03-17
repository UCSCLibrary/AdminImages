<?php
/**
 * An AdminImages Image 
 * 
 * @package AdminImages
 *
 */
class AdminImage
{
    public $id;
    public $original_filename;
    public $filename;
    public $thumbnail_url;
    public $square_thumbnail_url;
    public $url;

    public function delete(){
        $db = get_db();
        $sql = "delete from `".$db->File."` where id = ".$this->id;
        $result = $db->query($sql);
    }

    public function linkToImage() {
        return '<a href="'.$this->url.'"><img src="'.$this->thumbnail_url.'"></a>';
    }

    public static function findById($id) {
        $db = get_db();
        $sql = "select * from `".$db->File."` where id = ".$id;
        $result = $db->query($sql);
        while ($imageInfo = $result->fetch()) {
            $image = self::_CreateImage($imageInfo);
        }
        return $image;
    }

    public static function FindAll() {
        $db = get_db();
        $sql = "select * from `".$db->File."` where item_id = 0";
        $result = $db->query($sql);
        $images = array();
        while ($imageInfo = $result->fetch()) {
            $image = self::_CreateImage($imageInfo);
            $images[] =$image; 
        }
        return $images;
    }

    private static function _CreateImage($imageInfo) {
        $image = new AdminImage;
        $image->id = $imageInfo['id'];
        $image->filename=$imageInfo['filename'];
        $image->original_filename=$imageInfo['original_filename'];
        if(strpos($image->original_filename,'http')===0) {
            $paths = explode('/',parse_url($image->original_filename,PHP_URL_PATH));
            $image->original_filename = $paths[count($paths)-1];
        }
        $image->thumbnail_url=public_url('files/thumbnails/'.$imageInfo['filename']);
        $image->square_thumbnail_url=public_url('files/square_thumbnails/'.$imageInfo['filename']);
        $image->url=public_url('files/original/'.$imageInfo['filename']);
        return $image;
    }
}
?>