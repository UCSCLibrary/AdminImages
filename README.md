# Admin Images

## Description

Plugin for Omeka Classic. Once installed, allows administrators to upload images not attached to items, for use in carousels and pages. One may need to embed static images in the html of their Omeka site; this plugin allows hosting those images on a Omeka installation without creating empty Item records to contain the static image files.

## Installation
Uncompress files and rename plugin folder "AdminImages".

Then install it like any other Omeka plugin.

## Usage
From the main browse page of the plugin, accessible via the Admin Images link in the main Admin navigation left bar, admins can add, edit and delete Admin Images.

An image can be uploaded either from a local or from an online source. Once ingested into Omeka, derivative images including thumbnails will be created. After the image has been uploaded, the AdminImages plugin will provide a URL to the uploaded image. One may use this URL directly in any html page you are creating, including SimplePages, custom homepage templates, etc.

This plugin also builds in a shortcode to be used inside pages and a view helper which can be used inside themes. 

When manually entering HTML into the omeka browser interface, one can add a shortcode of the form `[admin_image id=999,size=fullsize]` to embed an Admin Image in a page. When creating a custom view for a theme, one can embed an Admin Image using the code `<?php echo $this->adminImageTag($image_id,'fullsize');?>`. 
The ID of each admin image must be inserted in the code above, as visible in the main browse plugin page.

## Feedback
If you use this plugin, please take a moment to submit feedback about your experience, so we can keep making Omeka better: [User Survey](https://docs.google.com/forms/d/1sOFIOM7SqT9PjKiY0m-xbo3Gxm_Fzr6eg_fduGMEfzE/viewform?usp=send_form "User Survey")

## Warning
Use it at your own risk.

It’s always recommended to backup your files and your databases and to check your archives regularly so you can roll back if needed.

## Troubleshooting
See online issues on the <a href="https://github.com/UCSCLibrary/AdminImages/issues" target="_blank">plugin issues</a> page on GitHub.

## License
This plugin is published under the <a href="https://www.gnu.org/licenses/gpl-2.0.html" target="_blank">GNU/GPL</a>.

## Copyright
Copyright UC Santa Cruz University Library, 2014-2021

Copyright Daniele Binaghi, 2021
