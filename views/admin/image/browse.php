<?php

$head = array('bodyclass' => 'admin-images primary', 
              'title' => html_escape(__('Admin Images | Browse')));
echo head($head);
echo flash(); 
?>
<h2>Browse Admin Images</h2>
<a href="<?php echo admin_url('admin-images/image/add'); ?>"><button>Add New Image</button></a> 
<?php if(empty($images)) : ?>
<h3>No admin images have been uploaded yet.</h3>
<?php else:?>
<ul id="admin-images-list">
<?php foreach($images as $image) : ?>
  <?php $file = get_record_by_id('File',$image->file_id); ?>
<li class="admin-image">
  <label><?php echo $image->title; ?></label>
<?php echo link_to_file_show(file_image('thumbnail',array(),$file)); ?>
  <p class="admin-image-url">
  <?php echo $file->getWebPath();?>
</p>
  <button class="admin-image-delete" id="admin-image-delete-<?php echo $file->id; ?>">
  Delete
  </button>
</li>
<?php endforeach; ?>
</ul>
<?php endif;?>
<?php
echo foot(); 
?>