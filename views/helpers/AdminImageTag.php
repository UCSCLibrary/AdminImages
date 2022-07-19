<?php 
    class AdminImages_View_Helper_AdminImageTag extends Zend_View_Helper_Abstract
    {
        public function adminImageTag($id, $size="fullsize", $class="")
        {
            if ($id == '') return null;
            
            $image = get_record_by_id("AdminImage", $id);
            $markup = '
            <img  
                id="' . $image->id . '"
                src="' . $image->getUrl($size) . '"
                alt="' . ($image->alt != '' ? $image->alt : 'Admin Image') . '"
                title="' . $image->title . '"
                class="' . $class . '"
            />';
			
            if ($image->href != '') $markup = '<a href="' . $image->href . '" target="_blank">' . $markup . '</a>';
			
            return $markup;
        }
    }
