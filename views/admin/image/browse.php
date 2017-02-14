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
  <h3>Instructions</h3>
  <h4>Shortcodes</h4>
  <p>If you are creating a simple page or otherwise entering HTML into omeka through your browser, you can use the shortcode "[admin_image id=999,size='fullsize']" to display your admin image.</p>
  <h4>View Helpers</h4>
  <p>If you are creating a new view for your theme or plugin, you can use the view helper to display an Admin Image tag as follows: <code> echo $view->adminImageTag($image_id,$size="fullsize"); </code> .</p>
  <h3>Images</h3>
  <p></p>
  <ul id="admin-images-list">
    <?php foreach($images as $image) : ?>
      <li class="admin-image">
        <table>
          <tr>
            <td>Original:</td>
            <td><a href="<?php echo $image->getUrl('original');?>"><?php echo $image->getUrl('original');?></a></td>
          </tr><tr>
            <td>Fullsize:</td>
            <td><a href="<?php echo $image->getUrl('fullsize');?>"><?php echo $image->getUrl('fullsize');?></a></td>
          </tr><tr>
            <td>Square Thumbnail:</td>
            <td><a href="<?php echo $image->getUrl('square_thumbnail');?>"><?php echo $image->getUrl('square_thumbnail');?></a></td>
          </tr><tr>
            <td>Thumbnail:</td>
            <td><a href="<?php echo $image->getUrl('thumbnail');?>"><?php echo $image->getUrl('thumbnail');?></a></td>
          </tr>
        </table>

        <div class="admin-image-thumb">
          <?php echo $image->linkToImage('fullsize');?>
        
          <div class="admin-image-meta">
            Image ID:
            <?php echo $image->id; ?>
          </div>

        
          <div class="admin-image-meta">
            Title:
            <?php echo $image->title; ?>
          </div>

          <div class="admin-image-meta">
            Alt text:
            <?php echo $image->alt; ?>
          </div>

          <div class="admin-image-meta">
            Image default link:
            <?php echo $image->href; ?>
          </div>

          <a class="edit" href="<?php echo(admin_url('admin-images/image/edit/id/').$image->id); ?>"><button>Edit</button></a>
          <form method="post"  action="<?php echo admin_url('admin-images/image/delete/id/').$image->id; ?>">
            <?php echo $this->csrf;?>
            <input type="hidden" name="image_id" value="<?php echo $image->id;?>" />
            <input value="Delete"  type="submit" class="admin-image-delete" name="delete-button" id="admin-image-delete-<?php echo $image->id; ?>">
          </form>

        </div>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif;?>
<?php
echo foot(); 
?>
