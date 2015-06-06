jQuery(document).ready(function(){
	//	console.log(jQuery("#file-label").parent().find(':file'));
        jQuery("#file-label").parent().find(':file').attr('name','file[0]');
	jQuery("#file-label").parent().hide();
	jQuery("#url-label").parent().hide();
    /*    if(jQuery('input[name=ingesttype]:checked').val() == 'upload')
	    jQuery("#upload-label").parent().show();
    else
	    jQuery("#url-label").parent().show();
    */
    jQuery('input[name=ingesttype]').click(function(){
	if(jQuery(this).val() == 'Upload'){
	    jQuery("#file-label").parent().show();
	    jQuery("#url-label").parent().hide();
	 
	}else if(jQuery(this).val() == 'Url'){
	    jQuery("#url-label").parent().show();
	    jQuery("#file-label").parent().hide();
	}
    });
});
