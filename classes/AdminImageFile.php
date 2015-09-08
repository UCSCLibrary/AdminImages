<?php
/**
 * An AdminImages Image 
 * 
 * @package AdminImages
 *
 */
class AdminImageFile
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

    public static function FindById($id) {
        $db = get_db();
        $sql = "select * from `".$db->File."` where id = ".$id;
        $result = $db->query($sql);
        $image = false;
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
        $image = new AdminImageFile;
        $image->id = $imageInfo['id'];
        $image->filename=$imageInfo['filename'];
        $image->original_filename=$imageInfo['original_filename'];
        if(strpos($image->original_filename,'http')===0) {
            $paths = explode('/',parse_url($image->original_filename,PHP_URL_PATH));
            $image->original_filename = $paths[count($paths)-1];
        }
        $image->url=public_url('files/original/'.$imageInfo['filename']);
        return $image;
    }
}
?>
