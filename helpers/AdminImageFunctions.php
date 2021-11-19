<?php 
    function admin_image_tag_shortcode($args, $view)
    {
        if (! $id = $args['id']) return null;
        $size = isset($args['size']) ? $args['size'] : 'fullsize';
        $class = isset($args['class']) ? $args['class'] : '';
        return $view->adminImageTag($id, $size, $class);
    }
?>
