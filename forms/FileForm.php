<?php
/**
 * Admin Images Add form 
 *
 * @package     AdminImages
 * @copyright   2014 UCSC Library Digital Initiatives
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * AdminImages file form class
 */
class Admin_Images_Add_Form extends Omeka_Form
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

        //Upload or url:
        $this->addElement('radio', 'ingest-type', array(
            'label'         => __('Ingest Type'),
            'description'   => __('Please indicate whether you wish to upload an image from your computer or import an image from the internet by its URL.'),
//            'value'         => 'upload',
	    'order'         => 5,
	    'multiOptions'       => array(
					  'Upload'=>'Upload',
					  'Url'=>'Url'
					  )
							   )
			  );

        //Upload:
    $this->addElement('file', 'file', array(
        'label'         => 'Upload Image:',
        'destination'   => sys_get_temp_dir(),
	'order'         => 6,
        'validators'    => array(
            array('count', true, array(
                'min'   => 0,
                'max'   => 1,
                'messages'  => array(
                    Zend_Validate_File_Count::TOO_FEW =>
                        'You must upload an image file',
                    Zend_Validate_File_Count::TOO_MANY =>
                        'You can only upload one image file'))),
            array('extension', true, array(
                'extention' => 'jpg,jpeg,png,gif,bmp,tif',
                'messages'  => array(
                    Zend_Validate_File_Extension::NOT_FOUND =>
                        'The file has an invalid extention (jpg,jpeg,png,gif,bmp,tif only)',
                    Zend_Validate_File_Extension::FALSE_EXTENSION =>
		    'The file has an invalid extention (jpg,jpeg,png,gif,bmp,tif only)'))))
					      ));

        // Url
        $this->addElement('text', 'url', array(
		'label'         => __('Image url'),
		'description'   => __('The url of the image to ingest'),
		'order'         => 7
					       )
			  );

        if(version_compare(OMEKA_VERSION,'2.2.1') >= 0)
            $this->addElement('hash','csrf_token');

        // Submit:
        $this->addElement('submit', 'submit', array(
	    'label' => __('Import Image'),
	    'order' => 8
        ));
    }
}
