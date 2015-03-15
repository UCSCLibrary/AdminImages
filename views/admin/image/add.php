<?php

$head = array('bodyclass' => 'admin-images primary', 
              'title' => html_escape(__('Admin Images | Add')));
echo head($head);
echo flash(); ?>
<h2>Add New Admin Image</h2>

<?php echo $form;?>

<?php echo foot(); ?>