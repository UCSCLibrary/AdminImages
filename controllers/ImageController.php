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

    public function deleteAction()
    {
        $this->_validatePost();
        if( ! $id = $this->getParam('id') )
            die('error');
        $image = get_record_by_id("AdminImage",$id);
        $image->delete();
        $this->_helper->FlashMessenger->addMessage('Image deleted successfully','success');
        $this->_helper->redirector('browse');
    }

    public function browseAction()
    {
        if(version_compare(OMEKA_VERSION,'2.2.1') >= 0)
            $this->view->csrf = new Omeka_Form_SessionCsrf;
        else
            $this->view->csrv = '';
        $this->view->images = get_db()->getTable('AdminImage')->findAll();
    }

    public function addAction() 
    {
        $flashMessenger = $this->_helper->FlashMessenger;
        $successMessage = false;
        require_once(dirname(dirname(__FILE__))."/forms/AddFileForm.php");
        require_once(dirname(dirname(__FILE__))."/models/AdminImage.php");
        $this->view->form = new Admin_Images_Add_Form();
        try{
            if ($this->getRequest()->isPost()){
                if($this->view->form->isValid($this->getRequest()->getPost())){
                    $successMessage = AdminImage::CreateImage();
                    $this->_helper->redirector('browse');
                }else{ 
                    $flashMessenger->addMessage('Unfortunately there was an error importing your image. ','error');
                } 
            }
        } catch (Exception $e){
            $flashMessenger->addMessage($e->getMessage(),'error');
        }
        if($successMessage)
            $flashMessenger->addMessage($successMessage,'success');
    }

    public function editAction() 
    {
        if(version_compare(OMEKA_VERSION,'2.2.1') >= 0)
            $this->view->csrf = new Omeka_Form_SessionCsrf;
        else
            $this->view->csrv = '';
        

        if(!$image = get_record_by_id("AdminImage",$this->_getParam('id')))
            die("ERROR");
        $flashMessenger = $this->_helper->FlashMessenger;
        $successMessage = false;
        require_once(dirname(dirname(__FILE__))."/forms/EditFileForm.php");
 //       require_once(dirname(dirname(__FILE__))."/models/AdminImage.php");
        
        $this->view->image = $image;
        $this->view->form = new Admin_Images_Edit_Form();
        $this->view->form->addElement('hidden','image_id',$image->id);
        $this->view->form->populate(array('title'=>$image->title,
                                          'alt'=>$image->alt,
                                          'href'=>$image->href,
                                          'image_id',$image->id));
        try{
            if ($this->getRequest()->isPost()){
                if($this->view->form->isValid($this->getRequest()->getPost())){
                    $successMessage = AdminImage::EditImage($image->id);
                    $this->_helper->redirector('browse');
                }else{ 
                    $flashMessenger->addMessage('Unfortunately there was an error editing your image. ','error');
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
        require_once(dirname(dirname(__FILE__))."/classes/AdminImages_".$_POST['ingesttype'].".php");
        
        $ingesterClass = 'AdminImages_'.$_POST['ingesttype'].'_Ingester';
        $ingester = new $ingesterClass;
        $this->_addIngestValidators($ingester);
        $fileinfo = $_POST['ingesttype']==='Url' ? $_POST['url'] : null;
        $files = $ingester->itemlessIngest($fileinfo);
        foreach($files as $file) {
            $file->item_id=0;
            $file->save();
        }
        return ("File processed successfully");
    }
    
    
    private function _validatePost(){
        if(version_compare(OMEKA_VERSION,'2.2.1') >= 0)
            return true;
        $csrf = new Omeka_Form_SessionCsrf;
        if (!$csrf->isValid($_POST))
            die('ERROR');
        return $csrf;
    }

}
