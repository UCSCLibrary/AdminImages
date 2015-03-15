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
      $this->view->images = get_db()->getTable('AdminImage')->findAll();
  }

  public function addAction() 
  {
    $flashMessenger = $this->_helper->FlashMessenger;
    include_once(dirname(dirname(__FILE__))."/forms/AddFileForm.php");
    $this->view->form = new Admin_Images_Add_Form();
    try{
      if ($this->getRequest()->isPost()){
	if($response = $this->view->form->isValid($this->getRequest()->getPost()))
	  $successMessage = $this->_processNewFile();
	else 
	  $flashMessenger->addMessage('Unfortunately there was an error importing your image. '.$response,'error');
      } 
    } catch (Exception $e){
      $flashMessenger->addMessage($e->getMessage(),'error');
    }
    $flashMessenger->addMessage($successMessage,'success');
  }

  private function _processNewFile()
  {
    print_r($_FILES) and die('end files');
  }

}
