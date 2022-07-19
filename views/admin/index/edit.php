<?php
    $head = array('bodyclass' => 'admin-images primary', 
                  'title' => html_escape(__('Edit Admin Image #%s', $image->id))
    );
    echo head($head);
?>

<?php echo flash(); ?>

<?php echo $form;?>

<?php echo foot(); ?>
