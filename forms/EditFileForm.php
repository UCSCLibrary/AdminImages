<?php
/**
 * Admin Images Add form 
 *
 * @package     AdminImages
 * @copyright   2014 UCSC Library Digital Initiatives
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * AdminImages Add form class
 */
class Admin_Images_Edit_Form extends Omeka_Form_Admin
{
    /**
     * Construct the import form.
     *
     *@return void
     */
    public function init()
    {
        parent::init();
        $this->_registerElements();
    }

    /**
     * Define the form elements.
     *
     *@return void
     */
    private function _registerElements()
    { 
        $this->addElementToEditGroup('note', 'details', array(
            'label'         => __('Details'),
            'order'         => 1
        ));
        
        $this->addElementToEditGroup('text', 'title', array(
            'label'         => __('Title'),
            'description'   => __('The title of the image. Will be used as tooltip on mouse-over, and to identify the image in the Admin interface.'),
            'order'         => 2,
            'required'      => true
        ));

        $this->addElementToEditGroup('text', 'alt', array(
            'label'         => __('Alt Text'),
            'description'   => __('The alternative text displayed when the image does not get loaded on a user\'s browser.'),
            'order'         => 3,
            'required'      => true
        ));

        $this->addElementToEditGroup('text', 'href', array(
            'label'         => __('Permalink'),
            'description'   => __('The URL to which the image is linking to. To be used only if the image is permanently associated with a Simple Page, Item, Collection or Exhibit.'),
            'order'         => 4
        ));

        if (version_compare(OMEKA_VERSION, '2.2.1') >= 0) {
            $this->addElement('hash', 'csrf_token');
        }
    }
}
