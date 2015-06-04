<?php
/**
 * AdminImages
 *
 * @package AdminImages
 * @copyright Copyright 2014 UCSC Library Digital Initiatives
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * The AdminImages image controller class.
 *
 * @package AdminImages
 */
class AdminImages_ImageController extends Omeka_Controller_AbstractActionController
{    

  /**
   * 
   * @param void
   * @return void
   */

  public function browseAction()
  {
      require_once(dirname(dirname(__FILE__))."/classes/AdminImage.php");

      if(version_compare('2.2.1',OMEKA_VERSION))
          $this->view->csrv = '';
      else
          $this->view->csrf = new Omeka_Form_SessionCsrf;

      if($this->getRequest()->isPost()){
          $this->_validatePost();
          require_once(dirname(dirname(__FILE__)).'/classes/AdminImage.php');
          if( ! $id = $this->getParam('image_id') )
              die('error');
          $image = AdminImage::findById($id);
          $image->delete();
          $this->_helper->FlashMessenger->addMessage('Image deleted successfully','success');
      }


      $this->view->images = AdminImage::FindAll();
  }

  public function addAction() 
  {
    $flashMessenger = $this->_helper->FlashMessenger;
    $successMessage = false;
    require_once(dirname(dirname(__FILE__))."/forms/AddFileForm.php");
    $this->view->form = new Admin_Images_Add_Form();
    try{
      if ($this->getRequest()->isPost()){
          if($response = $this->view->form->isValid($this->getRequest()->getPost())){
              $successMessage = $this->_processNewFile();
              $this->_helper->redirector('browse');
          }else{ 
              $flashMessenger->addMessage('Unfortunately there was an error importing your image. '.$response,'error');
          } 
      }
    } catch (Exception $e){
      $flashMessenger->addMessage($e->getMessage(),'error');
    }
    if($successMessage)
        $flashMessenger->addMessage($successMessage,'success');
  }

  private function _processNewFile()
  {
      require_once(dirname(dirname(__FILE__))."/classes/AdminImages_Upload_Ingester.php");
      require_once(dirname(dirname(__FILE__))."/classes/AdminImages_Url_Ingester.php");
      
      $fileinfo = $_POST['ingesttype']==='Url' ? $_POST['url'] : null;

      $ingesterClass = 'AdminImages_'.$_POST['ingesttype'].'_Ingester';
      $ingester = new $ingesterClass;
      $this->_addIngestValidators($ingester);
      $files = $ingester->itemlessIngest($fileinfo);
      foreach($files as $file) {
          $file->item_id=0;
          $file->save();
      }
      return ("File processed successfully");
  }

    /**
     * Add the default validators for ingested files.  
     * 
     * The default validators are whitelists for file extensions and MIME types,
     * and those lists can be configured via the admin settings form.
     * 
     * These default validators can be disabled by the 'disable_default_file_validation'
     * flag in the settings panel.
     * 
     * Plugins can add/remove/modify validators via the 'file_ingest_validators'
     * filter.
     * 
     * @param Omeka_File_Ingest_AbstractIngest $ingester
     * @return void
     */
    protected function _addIngestValidators(Omeka_File_Ingest_AbstractIngest $ingester)
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
    
    
  private function _validatePost(){
      if(version_compare('2.2.1',OMEKA_VERSION))
          return true;
    $csrf = new Omeka_Form_SessionCsrf;
    if (!$csrf->isValid($_POST))
      die('ERROR');

    return $csrf;
  }

}
