<?php 
    function admin_image_tag_shortcode($args, $view)
    {
        $size = isset($args['size']) ? $args['size'] : 'fullsize';
        return $view->adminImageTag($args['id'],$size);
    }
?>
