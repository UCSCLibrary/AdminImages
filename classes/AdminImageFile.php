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

    /**
     * File extension for all image derivatives.
     */
    const DERIVATIVE_EXT = 'jpg';

    /**
     * Folder paths for each type of files/derivatives.
     *
     * @var array
     */
    private static $_pathsByType = array(
        'original' => 'original',
        'fullsize' => 'fullsize',
        'thumbnail' => 'thumbnails',
        'square_thumbnail' => 'square_thumbnails'
    );

    public function delete() {
		self::_unlinkFile();
		
        $db = get_db();
        $sql = "DELETE FROM `" . $db->File . "` WHERE id = " . $this->id;
        $result = $db->query($sql);
    }
	
	/**
     * Unlink the file and file derivatives belonging to this record.
     */
    public function _unlinkFile() {
        $storage = $this->getStorage();
        $files = array($this->getStoragePath('original'));
		$types = self::$_pathsByType;
		unset($types['original']);
		foreach ($types as $type => $path) {
			$files[] = $this->getStoragePath($type);
		}
        foreach ($files as $file) {
            $storage->delete($file);
        }
    }

    /**
     * Get the storage object.
     * 
     * @return Omeka_Storage
     */
    public function getStorage()
    {
        if (!$this->_storage) {
            $this->_storage = Zend_Registry::get('storage');
        }
        return $this->_storage;
    }

    /**
     * Get a storage path for the file.
     * 
     * @param string $type
     * @return string
     */
    public function getStoragePath($type = 'original')
    {
        $storage = $this->getStorage();
        if ($type == 'original') {
            $fn = $this->filename;
        } else {
            $fn = $this->getDerivativeFilename();
        }
        if (!isset(self::$_pathsByType[$type])) {
            throw new RuntimeException(__('"%s" is not a valid file derivative.', $type));
        }
        return $storage->getPathByType($fn, self::$_pathsByType[$type]);
    }

    /**
     * Get the filename for this file's derivative images.
     * 
     * @return string
     */
    public function getDerivativeFilename()
    {
        $base = pathinfo($this->filename, PATHINFO_EXTENSION) ? substr($this->filename, 0, strrpos($this->filename, '.')) : $this->filename;
        return $base . '.' . self::DERIVATIVE_EXT;
    }

    public static function FindById($id) {
        $db = get_db();
        $sql = "SELECT * FROM `" . $db->File . "` WHERE id = " . $id;
        $result = $db->query($sql);
        $image = false;
        while ($imageInfo = $result->fetch()) {
            $image = self::_CreateImage($imageInfo);
        }
        return $image;
    }

    public static function FindAll() {
        $db = get_db();
        $sql = "SELECT * FROM `" . $db->File . "` WHERE item_id = 0";
        $result = $db->query($sql);
        $images = array();
        while ($imageInfo = $result->fetch()) {
            $image = self::_CreateImage($imageInfo);
            $images[] = $image;
        }
        return $images;
    }

    private static function _CreateImage($imageInfo) {
        $image = new AdminImageFile;
        $image->id = $imageInfo['id'];
        $image->filename = $imageInfo['filename'];
        $image->original_filename = $imageInfo['original_filename'];
        if (strpos($image->original_filename, 'http') === 0) {
            $paths = explode('/', parse_url($image->original_filename, PHP_URL_PATH));
            $image->original_filename = $paths[count($paths)-1];
        }
        $image->url = public_url('files/original/' . $imageInfo['filename']);
        return $image;
    }
}
