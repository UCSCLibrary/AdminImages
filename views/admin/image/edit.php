<?php
$head = array('bodyclass' => 'admin-images primary', 
              'title' => html_escape(__('Admin Images | Edit')));
echo head($head);
echo flash(); ?>

<h2>Edit Admin Image</h2><p>

  <?php echo $this->adminImageTag($image->id); ?>

  <form method="post" action="<?php echo admin_url('admin-images/image/delete/id/').$image->id; ?>">
    <?php echo $this->csrf;?>
    <input type="hidden" name="image_id" value="<?php echo $image->id;?>" />
    <input value="Delete Image"  type="submit" class="admin-image-delete" name="delete-button" id="admin-image-delete-<?php echo $image->id; ?>">
  </form>
  <?php echo $form;?>
  <?php echo foot(); ?>
