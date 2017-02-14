<?php
class AdminImages_Upload_Ingester extends Omeka_File_Ingest_Upload
{
    /**
     * Set the item to use as a target when ingesting files.
     * 
     * @param Item $item
     * @return void
     */        
    public function setItem(Item $item=null)
    {
        $this->_item = null;       
    }

    function __construct() {
        $this->setItem();
    }
    
    function itemlessIngest ($fileInfo)
    {
        // Don't catch or suppress parsing errors.
        $fileInfoArray = $this->_parseFileInfo($fileInfo);
        
        // Iterate the files.
        $fileObjs = array();
        foreach ($fileInfoArray as $file) {            
            
            try {                
                // This becomes the file's identifier (stored in the 
                // 'original_filename' column and used to derive the archival filename).
                $originalFileName = $this->_getOriginalFilename($file);

                $fileDestinationPath = $this->_transferFile($file, $originalFileName);

                // Create the file object.
                if ($fileDestinationPath) {
                    $fileMetadata = isset($file['metadata']) 
                        ? $file['metadata'] : array();
                    $fileObjs[] = $this->_createItemlessFile($fileDestinationPath, $originalFileName, $fileMetadata);
                }
            
            } catch (Omeka_File_Ingest_InvalidException $e) {
                if ($this->_ignoreIngestErrors()) {
                    $this->_logException($e);
                    continue;
                } 
                
                // If not suppressed, rethrow it.
                throw $e;
            }
        }
        return $fileObjs;
    }

 private function _createItemlessFile($newFilePath, $oldFilename, $elementMetadata = array())
    {
        // Normally, the MIME type validator sets the type to this class's 
        // static $mimeType property during validation. If that validator has 
        // been disabled (from the admin settings menu, for example), set the 
        // MIME type here.
        if (self::$mimeType) {
            $mimeType = self::$mimeType;
            // Make sure types don't leak between files.
            self::$mimeType = null;
        } else {
            $detect = new Omeka_File_MimeType_Detect($newFilePath);
            $mimeType = $detect->detect();
        }
        $file = new File;
        $file->item_id=0;
        try {
            $file->original_filename = $oldFilename;
            $file->mime_type = $mimeType;
            $file->setDefaults($newFilePath);
            if ($elementMetadata) {
                $file->addElementTextsByArray($elementMetadata);
            }
        } catch(Exception $e) {
            if (!$file->exists()) {
                $file->unlinkFile();
            }
            throw $e;
        }
        return $file;
    }
}



?>
