<?php
/**
 * AdminImages
 *
 * @package AdminImages
 * @copyright Copyright 2014 UCSC Library Digital Initiatives
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * The AdminImages index controller class.
 *
 * @package AdminImages
 */
class AdminImages_IndexController extends Omeka_Controller_AbstractActionController
{    

  /**
   * 
   * @param void
   * @return void
   */

  public function indexAction()
  {
      $this->_helper->redirector('browse','image');
  }

}
