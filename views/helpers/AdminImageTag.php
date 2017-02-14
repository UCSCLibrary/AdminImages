<?php 
class AdminImages_View_Helper_AdminImageTag extends Zend_View_Helper_Abstract
{
    public function adminImageTag($id,$size="fullsize")
    {
        $image = get_record_by_id("AdminImage",$id);
        $markup = '       
       <img class="admin-image" 
            id="'.$image->id.'"
            src="'.$image->getUrl($size).'"
            alt="'.$image->alt.'"
            title="'. $image->title.'"/>';
        return $markup;
    }
}
?>


