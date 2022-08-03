<?php echo head(array('title' => html_escape(__('Browse Admin Images') . ' ' . __('(%s total)', $total_results)))); ?>

<?php echo flash(); ?>

<a class="add-image button green" href="<?php echo admin_url('admin-images/index/add'); ?>"><?php echo __('Add an Image'); ?></a>

<?php if (count($images)): ?>
    <?php echo pagination_links(); ?>
    <div class="table-responsive">
        <table id="admin-images-list">
            <thead>
                <tr>
                    <th class='id'>ID</th>
                    <?php
                        echo browse_sort_links(
                            array(
                                __('Title') => 'title',
                                __('Permalink') => 'href',
                                __('Added by') => 'owner_id'
                            ),
                            array('link_tag' => 'th scope="col"', 'list_tag' => '')
                        );
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php $key = 0; ?>
                <?php foreach ($images as $image): ?>
                    <tr class="admin-image-tr <?php echo (++$key%2 == 1 ? 'odd' : 'even'); ?>">
                        <td class='id'>
                            <?php
                                echo $image->id;
                            ?>
                        </td>
                        <td class="admin-image-info">
                            <?php
                                echo "<img src='" . $image->getUrl('thumbnail') . "'>";
                                echo "<strong>" . $image->title . "</strong>";
                            ?>
                            <ul class="action-links group">
                                <li><a class="edit-image" href="<?php echo admin_url('admin-images/index/edit/id/') . $image->id; ?>"><?php echo __('Edit'); ?></a></li>
                                <li><a class="delete-image delete-confirm" href="<?php echo admin_url('admin-images/index/delete-confirm/id/') . $image->id; ?>"><?php echo __('Delete'); ?></a></li>
                            </ul>
                            <div class="details">
                            <?php
                                $url = $image->getUrl('original');
                                if ($size = getimagesize($url)) {
                                    echo "<p><strong>" . __('Width') . "</strong>: " . $size[0] . "px</p>";
                                    echo "<p><strong>" . __('Height') . "</strong>: " . $size[1] . "px</p>";
                                    echo "<p><strong>" . __('MIME Type') . ":</strong> " . $size['mime'] . "</p>";
                                }
                                $altText = $image->alt;
                                if ($altText != '') echo "<p><strong>" . __('Alt text') . "</strong>: " . $altText . "</p>";
                                echo "<p><strong>" . __('Direct links') . ":</strong> ";
                                echo "<a href='" . $url . "' target='_blank'>" . __('Original') . "</a> ";
                                echo "<a href='" . $image->getUrl('fullsize') . "' target='_blank'>" . __('Fullsize') . "</a> ";
                                echo "<a href='" . $image->getUrl('thumbnail') . "' target='_blank'>" . __('Thumbnail') . "</a> ";
                                echo "<a href='" . $image->getUrl('square_thumbnail') . "' target='_blank'>" . __('Square thumbnail') . "</a></p>";
                            ?>
                        </td>
                        <td>
                            <?php
                                echo $image->href;
                            ?>
                        </td>
                        <td>
                            <?php
                                echo $image->getUsername();
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script type="text/javascript">
        Omeka.addReadyCallback(Omeka.AdminImages.setupDetails, [
            <?php echo js_escape(__('Details')); ?>,
            <?php echo js_escape(__('Show Details')); ?>,
            <?php echo js_escape(__('Hide Details')); ?>
        ]);
    </script>
<?php else: ?>
    <p>
        <?php echo __('There seems to be no Admin Images stored in the repository.') . ' <a class="add-image" href="' . admin_url('admin-images/index/add') . '">' . __('Add an Image') . '</a>.'; ?>
    </p>
<?php endif; ?>

<?php echo foot(); ?>
