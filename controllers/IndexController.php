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
    const RECORDS_PER_PAGE_SETTING = 'records_per_page_setting';

    /**
     * The number of records to browse per page.
     * 
     * If this is left null, then results will not paginate. This is partially 
     * because not every controller will want to paginate records and also to 
     * avoid BC breaks for plugins.
     *
     * Setting this to self::RECORDS_PER_PAGE_SETTING will cause the
     * admin-configured page limits to be used (which is often what you want).
     *
     * @var string
     */
    protected $_browseRecordsPerPage = self::RECORDS_PER_PAGE_SETTING;

    public function init()
    {
        // Set the model class so this controller can perform some functions, 
        // such as $this->findById()
        $this->_helper->db->setDefaultModelName('AdminImage');
    }

    public function indexAction()
    {
        // Always go to browse.
        $this->_helper->redirector('browse');
        return;
    }

    public function browseAction()
    {
        // Respect only GET parameters when browsing.
        $this->getRequest()->setParamSources(array('_GET'));

        // Apply controller-provided default sort parameters
        if (!$this->_getParam('sort_field')) {
            $defaultSort = apply_filters("files_browse_default_sort",
                $this->_getBrowseDefaultSort(),
                array('params' => $this->getAllParams())
            );
            if (is_array($defaultSort) && isset($defaultSort[0])) {
                $this->setParam('sort_field', $defaultSort[0]);

                if (isset($defaultSort[1])) {
                    $this->setParam('sort_dir', $defaultSort[1]);
                }
            }
        }

        $params = $this->getAllParams();
        $recordsPerPage = $this->_getBrowseRecordsPerPage('files');
        $currentPage = $this->getParam('page', 1);

        // Get the records filtered to Omeka_Db_Table::applySearchFilters().
        $records = $this->_helper->db->findBy($params, $recordsPerPage, $currentPage);
        $totalRecords = $this->_helper->db->count($params);

        // Add pagination data to the registry. Used by pagination_links().
        if ($recordsPerPage) {
            Zend_Registry::set('pagination', array(
                'page' => $currentPage,
                'per_page' => $recordsPerPage,
                'total_results' => $totalRecords,
            ));
        }

        $this->view->assign(array('images' => $records, 'total_results' => $totalRecords));
    }

    /**
     * Return the number of records to display per page.
     *
     * By default this will read from the _browseRecordsPerPage property, which
     * in turn defaults to null, disabling pagination. This can be 
     * overridden in subclasses by redefining the property or this method.
     *
     * Setting the property to self::RECORDS_PER_PAGE_SETTING will enable
     * pagination using the admin-configued page limits.
     *
     * @param string|null $pluralName
     * @return int|null
     */
    protected function _getBrowseRecordsPerPage($pluralName = null)
    {
        $perPage = $this->_browseRecordsPerPage;

        // Use the user-configured page
        if ($perPage === self::RECORDS_PER_PAGE_SETTING) {
            $options = $this->getFrontController()->getParam('bootstrap')
                ->getResource('Options');

            if (is_admin_theme()) {
                $perPage = (int) $options['per_page_admin'];
            } else {
                $perPage = (int) $options['per_page_public'];
            }
        }

        // If users are allowed to modify the # of items displayed per page,
        // then they can pass the 'per_page' query parameter to change that.
        if ($this->_helper->acl->isAllowed('modifyPerPage')
            && ($queryPerPage = $this->getRequest()->get('per_page'))
        ) {
            $perPage = (int) $queryPerPage;
        }

        // Any integer zero or below disables pagination.
        if ($perPage < 1) {
            $perPage = null;
        }

        if ($pluralName) {
            $perPage = apply_filters("{$pluralName}_browse_per_page", $perPage,
                array('controller' => $this));
        }
        return $perPage;
    }

    /**
     * Return the default sorting parameters to use when none are specified.
     *
     * @return array|null Array of parameters, with the first element being the
     *  sort_field parameter, and the second (optionally) the sort_dir.
     */
    protected function _getBrowseDefaultSort()
    {
        return null;
    }

    public function addAction() 
    {
        $flashMessenger = $this->_helper->FlashMessenger;
        $successMessage = false;
        require_once(dirname(dirname(__FILE__)) . "/forms/AddFileForm.php");
        require_once(dirname(dirname(__FILE__)) . "/models/AdminImage.php");
        $this->view->form = new Admin_Images_Add_Form(array('type' => 'admin_images_edit'));
        try {
            if ($this->getRequest()->isPost()) {
                if ($this->view->form->isValid($this->getRequest()->getPost())) {
                    $successMessage = AdminImage::CreateImage();
                    $flashMessenger->addMessage(__('The image was successfully added.'), 'success');
                    $this->_helper->redirector('browse');
                } else { 
                    $flashMessenger->addMessage(__('One or more errors occurred while adding the image. Please correct them and try again.'), 'error');
                } 
            }
        } catch (Exception $e){
            $flashMessenger->addMessage($e->getMessage(), 'error');
        }
        if ($successMessage) $flashMessenger->addMessage($successMessage, 'success');
    }

    public function editAction() 
    {
        $flashMessenger = $this->_helper->FlashMessenger;
        if (version_compare(OMEKA_VERSION, '2.2.1') >= 0) {
            $this->view->csrf = new Omeka_Form_SessionCsrf;
        } else {
            $this->view->csrv = '';
        }

        if (!$image = get_record_by_id('AdminImage', $this->_getParam('id'))) {
            $flashMessenger->addMessage(__('The image was not found in the repository.'), 'error');
            $this->_helper->redirector('browse');
        }
        $successMessage = false;
        require_once(dirname(dirname(__FILE__)) . "/forms/EditFileForm.php");

        $this->view->image = $image;
        $this->view->form = new Admin_Images_Edit_Form(array('type' => 'admin_images_edit'));
        $this->view->form->addElement('hidden', 'image_id', $image->id);
        $this->view->form->addElementToEditGroup('text', 'foo', array(
            'style' => 'visibility: hidden',
            'decorators' => array(
                array(
                    array('img' => 'HtmlTag'), 
                    array(
                        'tag' => 'img',
                        'openOnly' => true,
                        'src' => $image->getUrl('thumbnail'),
                        'style' => 'margin-bottom: 20px'
                    )
                )
            )
        ));
        
        $this->view->form->addElementToSaveGroup('note', 'delete', array(
            'value'    => '<a href="' . admin_url('admin-images/index/delete-confirm/id/'). $image->id . '" class="delete-confirm big red button">' . __('Delete Image') . '</a>',
            'order'    => 6
        ));

        $url = $image->getUrl('original');
        $details = '';
        if ($size = getimagesize($url)) $details = '<span class="explanation">' . __('Width') . ': <b>' . $size[0] . "px</b> | " . __('Height') . ': <b>' . $size[1] . "px</b> | ". __('MIME Type') . ': <b>' . $size['mime'] . '</b></span>';
        $this->view->form->populate(array('title' => $image->title,
                                          'alt' => $image->alt,
                                          'href' => $image->href,
                                          'details' => $details
        ));

        try {
            if ($this->getRequest()->isPost()) {
                if ($this->view->form->isValid($this->getRequest()->getPost())) {
                    $successMessage = AdminImage::EditImage($image->id);
                    $flashMessenger->addMessage(__('The image #%s was successfully edited.', $image->id), 'success');
                    $this->_helper->redirector('browse');
                } else { 
                    $flashMessenger->addMessage(__('One or more errors occurred while editing the image. Please correct them and try again.'), 'error');
                } 
            }
        } catch (Exception $e) {
            $flashMessenger->addMessage($e->getMessage(), 'error');
        }
        if ($successMessage) $flashMessenger->addMessage($successMessage, 'success');
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id');
        // delete vocabulary
        if ($image = get_record_by_id('AdminImage', $id)) {
            $image->delete();
            $this->_helper->FlashMessenger->addMessage(__('The image #%s was successfully deleted.', $id), 'success');
        } else {
            $this->_helper->FlashMessenger->addMessage(__('The image was not found in the repository.'), 'error');
        }
        $this->_helper->redirector('browse');
    }

    protected function _getDeleteConfirmMessage($record)
    {
        return __('This will permanently delete this image, but not the shortcodes and code that might have been added.');
    }

    private function _validatePost() {
        if (version_compare(OMEKA_VERSION, '2.2.1') >= 0) {
            return true;
        }
        $csrf = new Omeka_Form_SessionCsrf;
        if (!$csrf->isValid($_POST)) {
            die(__('An error occurred.'));
        }
        return $csrf;
    }
}
