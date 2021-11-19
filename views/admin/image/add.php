<?php
    $head = array('bodyclass' => 'admin-images primary', 
                  'title' => html_escape(__('Add Admin Image'))
    );
    echo head($head);

    echo flash(); 
?>

<?php echo $form; ?>

<?php echo foot(); ?>
