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
      $this->addElement('text', 'title', array(
						    'label'         => __('Title'),
						    'description'   => __('Enter the title of the image to add'),		    
						    'order'         => 1,
						    'required'      => true,
						    
						    )
			);
      
        //Upload or url:
        $this->addElement('radio', 'ingest-type', array(
            'label'         => __('Ingest Type'),
            'description'   => __('Please indicate whether you wish to upload an image from your computer or import an image from the internet by its URL.'),
            'value'         => 'upload',
	    'order'         => 2,
	    'multiOptions'       => array(
					  'upload'=>'Upload',
					  'url'=>'Url'
					  )
							   )
			  );

        //Upload:
    $this->addElement('file', 'upload', array(
        'label'         => 'Upload Image:',
        'destination'   => sys_get_temp_dir(),
	'order'         => 3,
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
		'order'         => 4
					       )
			  );




        // Submit:
        $this->addElement('submit', 'submit', array(
	    'label' => __('Import Image'),
	    'order' => 6
        ));
	

    }


}
