<?php
/**
 * A History Log entry
 * 
 * @package Historylog
 *
 */
class AdminImage extends Omeka_Record_AbstractRecord
{
    /*
     *@var int The record ID
     */
    public $id; 

    /*
     *@var string The title of the image
     */
    public $title; 

    /*
     *@var string The alt text for the image
     */
    public $alt;

    /*
     *@var string The alt text for the image
     */
    public $href;

    /*
     *@var int The id of the file associate with the image
     */
    public $file_id;

    /*
     *@var int The id of the user who created the image
     */
    public $creator_id;

    private $_file;

    function __construct() {
        parent::__construct();
        $this->_retrieveFile();
    }

    private function _retrieveFile(){
        require_once(dirname(dirname(__FILE__)).'/classes/AdminImageFile.php');
        if(!is_object($this->_file) && $this->file_id)
            $this->_file = AdminImageFile::FindById($this->file_id);
    }

    public function getFilename() {
        $this->_retrieveFile();
        return $this->_file->filename;
    }

    public function getOriginalFilename() {
        $this->_retrieveFile();
        $filename = $this->_file->original_filename;
        if(strpos($filename,'http')===0) {
            $paths = explode('/',parse_url($filename,PHP_URL_PATH));
            $filename = $paths[count($paths)-1];
        }
        return $filename;
    }

    public function getUrl($size="thumbnail") {
        $this->_retrieveFile();
        $filename = pathinfo($this->_file->filename, PATHINFO_FILENAME);
        $ext = pathinfo($this->_file->filename, PATHINFO_EXTENSION);
        switch($size) {
            case ("thumbnail"):
              return str_replace("/admin","",absolute_url('files/thumbnails/'.$filename.".jpg"));
            case ("fullsize"):
                return str_replace("/admin","",absolute_url('files/fullsize/'.$filename.'.jpg'));
            case ("square_thumbnail"):
                return str_replace("/admin","",absolute_url('files/square_thumbnails/'.$filename.'.jpg'));
            case ("original"):
                 return str_replace("/admin","",absolute_url('files/original/'.$this->_file->filename));
        }
    }

    public function linkToImage($size="thumbnail") {
        $href = empty($this->href) ? $this->url : $this->href;
        $title = empty($this->title) ? "" : ' title="'.$this->title.'"';
        $alt = empty($this->alt) ? ' alt="Admin Image"' : ' alt="'.$this->alt.'"';
        return '<a href="'.$href.'"><img'.$title.$alt.' src="'.$this->getUrl($size).'"></a>';
    }    
    
    protected function _delete() {
        $this->_retrieveFile();
        if(is_object($this->_file))
            $this->_file->delete();
    }

    protected static function _AddIngestValidators(Omeka_File_Ingest_AbstractIngest $ingester)
    {    
        $validators = get_option(File::DISABLE_DEFAULT_VALIDATION_OPTION) 
                    ? array()
                    : array(
                        'extension whitelist'=> new Omeka_Validate_File_Extension,
                        'MIME type whitelist'=> new Omeka_Validate_File_MimeType);
        
        $validators = apply_filters('file_ingest_validators', $validators);
        // Build the default validators.
        foreach ($validators as $validator) {
            $ingester->addValidator($validator);
        }
    }

    public static function CreateImage(){
        $image = new AdminImage();
        $image->title = isset($_POST['title']) ? $_POST['title']: "";
        $image->alt = isset($_POST['alt']) ? $_POST['alt']: "";
        $image->href = isset($_POST['href']) ? $_POST['href']: "";
        $image->creator_id = current_user()->id;

        require_once(dirname(dirname(__FILE__))
                    ."/classes/AdminImages_".$_POST['ingesttype']."_Ingester.php");
      
        $ingesterClass = 'AdminImages_'.$_POST['ingesttype'].'_Ingester';
        $ingester = new $ingesterClass;
        self::_AddIngestValidators($ingester);
        $url = $_POST['ingesttype']==='Url' ? $_POST['url'] : null;
        $files = $ingester->itemlessIngest($url);
        foreach($files as $file) {
            $file->item_id=0;
            $file->save();
            $image->file_id = $file->id;
        }
         $image->save();
         return ("File processed successfully");
    }   

    public static function EditImage($image_id){
        $image = get_record_by_id("AdminImage",$image_id);
        $image->title = isset($_POST['title']) ? $_POST['title']: $image->title;
        $image->alt = isset($_POST['alt']) ? $_POST['alt']:  $image->alt;
        $image->href = isset($_POST['href']) ? $_POST['href']:  $image->href;
         $image->save();
         return ("File processed successfully");
    }   
}
?>
