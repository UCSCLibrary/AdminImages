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
class Admin_Images_Edit_Form extends Omeka_Form
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
        $this->addElement('text','title',array(
            'label' => 'Image Title',
            'description' => 'This image title will show up when users mouse over the image, and to identify it in the admin interface',
            'order' => 1
        ));

        $this->addElement('text','alt',array(
            'label' => 'Alt Text',
            'description' => 'This text will display when for any reason the image has not loaded on a user\'s browser',
            'order' => 2
        ));

        $this->addElement('text','href',array(
            'label' => 'Link',
            'description' => 'This optional text will automatically link the image to a given URL. You should only fill this in if the image is always associated with a single page, exhibit, or item.',
            'order' => 3
        ));

        if(version_compare(OMEKA_VERSION,'2.2.1') >= 0)
            $this->addElement('hash','csrf_token');

        // Submit:
        $this->addElement('submit', 'submit', array(
	    'label' => __('Save'),
	    'order' => 8
        ));
    }
}
