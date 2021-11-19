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
class Admin_Images_Add_Form extends Omeka_Form_Admin
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
        $this->addElementToEditGroup('text', 'title', array(
            'label'         => __('Title'),
            'description'   => __('The title of the image. Will be used as tooltip on mouse-over, and to identify the image in the Admin interface.'),
            'order'         => 1,
            'required'      => true
        ));

        $this->addElementToEditGroup('text', 'alt', array(
            'label'         => __('Alt Text'),
            'description'   => __('The alternative text displayed when the image does not get loaded on a user\'s browser.'),
            'order'         => 2,
            'required'      => true
        ));

        $this->addElementToEditGroup('text', 'href', array(
            'label'         => __('Permalink'),
            'description'   => __('The URL to which the image is linking to. To be used only if the image is permanently associated with a Simple Page, Item, Collection or Exhibit.'),
            'order'         => 3
        ));

        //Upload or URL:
        $this->addElementToEditGroup('select', 'ingest-type', array(
            'label'         => __('Ingest Type'),
            'description'   => __('Choose whether to upload the image from a local source or import it from the internet by its URL.'),
            'order'         => 4,
            'multiOptions'  => array(
                'Upload'    => __('Upload from local source'),
                'Url'       => __('Import from the internet')
            )
        ));

        //Upload:
        $this->addElementToEditGroup('file', 'file', array(
            'label'         => __('Upload Image:'),
            'destination'   => sys_get_temp_dir(),
            'order'         => 5,
            'validators'    => array(
                array('count', true, array(
                    'min'   => 1,
                    'max'   => 1,
                    'messages'  => array(
                        Zend_Validate_File_Count::TOO_FEW =>
                            __('You must upload an image file'),
                        Zend_Validate_File_Count::TOO_MANY =>
                            __('You can only upload one image file'))
                    )
                ),
                array('extension', true, array(
                    'extension' => 'jpg,jpeg,png,gif,bmp,tif',
                    'messages'  => array(
                        Zend_Validate_File_Extension::NOT_FOUND =>
                            __('The file has an invalid extension (only jpg, jpeg, png, gif, bmp and tif allowed)'),
                        Zend_Validate_File_Extension::FALSE_EXTENSION =>
                            __('The file has an invalid extension (only jpg, jpeg, png, gif, bmp and tif allowed)'))
                    )
                )
            )
        ));

        // URL
        $this->addElementToEditGroup('text', 'url', array(
            'label'         => __('Image URL'),
            'description'   => __('The URL of the image to ingest'),
            'order'         => 6
        ));

        if (version_compare(OMEKA_VERSION, '2.2.1') >= 0) {
            $this->addElement('hash', 'csrf_token');
        }
    }
}
