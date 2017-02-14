# Admin Images
This Omeka 2.0+ plugin allows administrators to upload images not attached to items, for use in carousels and pages. You may need to embed static images in the html of your Omeka site; this plugin allows you to host those images on your Omeka installation without creating empty Item records to contain the static image files.

## Usage
After installing AdminImages, you will see an "AdminImages" link on the left side of the Omeka dashboard. This takes you to the AdminImages page, where you can manage your images and add new images.
You may use this page to upload any image from your computer. The image will be ingested into Omeka, and derivative images including thumbnails will be created. After the image has been uploaded, the AdminImages plugin will provide you with a URL to the uploaded image. 
You may use this URL directly in any html page you are creating, including SimplePages, custom homepage templates, etc.
This plugin also builds in a shortcode to be used in your pages and a view helper which can be used in your themes. 
If you are manually entering HTML into the omeka browser interface, you can add shortcode of the form `[admin_image id=999,size=fullsize]` to embed an admin image in your page.
If you are creating a custom view for your theme, you can embed an Admin Image using the code `<?php echo $this->adminImageTag($image_id,'fullsize');?>`. 
The ID of each admin image must be inserted in the code above, and is provided by the AdminImages plugin when you upload the image.

## Feedback
If you use this plugin, please take a moment to submit feedback about your experience, so we can keep making Omeka better: [User Survey] (https://docs.google.com/forms/d/1sOFIOM7SqT9PjKiY0m-xbo3Gxm_Fzr6eg_fduGMEfzE/viewform?usp=send_form "User Survey")
