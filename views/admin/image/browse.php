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
<li class="admin-image">
  <label><?php echo $image->original_filename; ?></label>

<table>
<tr>
<td>Original:</td>
<td><?php echo absolute_url('file/original/'.$image->filename);?></td>
</tr><tr>
<td>Fullsize:</td>
<td><?php echo absolute_url('file/fullsize/'.$image->filename);?></td>
</tr><tr>
<td>Square Thumbnail:</td>
<td><?php echo absolute_url('file/square_thumbnails/'.$image->filename);?></td>
</tr><tr>
<td>Thumbnail:</td>
<td><?php echo absolute_url('file/thumbnails/'.$image->filename);?></td>
</tr>
</table>

<div class="admin-image-thumb">
<?php echo $image->linkToImage();?>
</div>

<!--<form action="<?php echo admin_url('admin-images/image/delete/image_id/'.$image->id);?>">-->
<form method="post">
<?php echo $this->csrf;?>
    <input type="hidden" name="image_id" value="<?php echo $image->id;?>" />
  <input value="Delete"  type="submit" class="admin-image-delete" name="delete-button" id="admin-image-delete-<?php echo $image->id; ?>">
</form>
</li>
<?php endforeach; ?>
</ul>
<?php endif;?>
<?php
echo foot(); 
?>
