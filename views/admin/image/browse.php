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
        <label><?php echo $image->title ? $image->title : $image->getOriginalFilename(); ?></label>

        <table>
          <tr>
            <td>Original:</td>
            <td><?php echo $image->getUrl('original');?></td>
          </tr><tr>
            <td>Fullsize:</td>
            <td><?php echo $image->getUrl('fullsize');?></td>
          </tr><tr>
            <td>Square Thumbnail:</td>
            <td><?php echo $image->getUrl('square_thumbnail');?></td>
          </tr><tr>
            <td>Thumbnail:</td>
            <td><?php echo $image->getUrl('thumbnail');?></td>
          </tr>
        </table>

        <div class="admin-image-thumb">
          <?php echo $image->linkToImage();?>
        </div>

        <div class="admin-image-meta">
          <label>Alt text:</label>
          <?php echo $image->alt; ?>
        </div>

        <div class="admin-image-meta">
          <label>Image default link:</label>
          <?php echo $image->href; ?>
        </div>

          <a href="<?php echo(admin_url('admin-images/image/edit/id/').$image->id); ?>"><button>Edit</button></a>
        <form method="post"  action="<?php echo admin_url('admin-images/image/delete/id/').$image->id; ?>">
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
