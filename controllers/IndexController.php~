<?php
/**
 * NuxeoLink
 *
 * @package NuxeoLink
 * @copyright Copyright 2014 UCSC Library Digital Initiatives
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * The NuxeoLink index controller class.
 *
 * @package NuxeoLink
 */
class NuxeoLink_IndexController extends Omeka_Controller_AbstractActionController
{    

  /**
   * The default action to display the import form and process it.
   *
   * This action runs before loading the main import form. It 
   * processes the form output if there is any, and populates
   * some variables used by the form.
   *
   * @param void
   * @return void
   */

  public function indexAction()
  {
      include_once(dirname(dirname(__FILE__))."/forms/ImportForm.php");
      $form = new Nuxeo_Form_Import();
      
      //initialize flash messenger for success or fail messages
      $flashMessenger = $this->_helper->FlashMessenger;

      try{
          if ($this->getRequest()->isPost()){
              if($form->isValid($this->getRequest()->getPost()))
                  $successMessage = Nuxeo_Form_Import::ProcessPost();
              else 
                  $flashMessenger->addMessage('There was a problem importing your Nuxeo documents. Please check your Nuxeo URL settings.','error');
          } 
      } catch (Exception $e){
          $flashMessenger->addMessage($e->getMessage(),'error');
      }

    $backgroundErrors = unserialize(get_option('nuxeoBackgroundErrors'));
    if(is_array($backgroundErrors))
      foreach($backgroundErrors as $backgroundError)
	{
	  $flashMessenger->addMessage($backgroundError,'error');
	}
    set_option('nuxeoBackgroundErrors',"");

    if(isset($successMessage))
      $flashMessenger->addMessage($successMessage,'success');
      
      $this->view->form = $form;

  }


  public function foldersAction()
  {
      //require the helpers
      require_once(dirname(dirname(__FILE__)).'/helpers/APIfunctions.php');

      $uid = $this->getParam('uid');
      $client = new NuxeoOmekaImportClient(get_option('nuxeoUrl'));
      $session = $client->getSession(get_option('nuxeoUser'),get_option('nuxeoPass'));
      $folders = $session->getChildFolders($uid);

      $this->view->folders = $folders;

  }

  public function documentsAction()
  {
      //require the helpers
      require_once(dirname(dirname(__FILE__)).'/helpers/APIfunctions.php');

      //$uid = $this->getParam('uid');
      $path = $this->getParam('path');
//      echo 'Path: '.$path;
      $client = new NuxeoOmekaImportClient(get_option('nuxeoUrl'));
      $session = $client->getSession(get_option('nuxeoUser'),get_option('nuxeoPass'));
      //$docs = $session->getChildDocuments($uid);
      $docs = $session->getChildDocuments($path);
      $this->view->documents = $docs;

  }

  public function searchAction()
  {

      //require the helpers
      require_once(dirname(dirname(__FILE__)).'/helpers/APIfunctions.php');

      $uid = $this->getParam('uid');
      $searchTerm = $this->getParam('search');
      $client = new NuxeoOmekaImportClient(get_option('nuxeoUrl'));
      $session = $client->getSession(get_option('nuxeoUser'),get_option('nuxeoPass'));
      $docs = $session->fullTextSearch($uid,$searchTerm);
      $this->view->documents = $docs;

  }

}
