jQuery(document).ready(function(){
    jQuery("#upload-label").parent().hide();
    jQuery("#url-label").parent().hide();
    if(jQuery('input[name=ingesttype]:checked').val() == 'upload')
	    jQuery("#upload-label").parent().show();
    else
	    jQuery("#url-label").parent().show();

    jQuery('input[name=ingesttype]').click(function(){
	if(jQuery(this).val() == 'upload'){
	    jQuery("#upload-label").parent().show();
	    jQuery("#url-label").parent().hide();
	 
	}else{
	    jQuery("#url-label").parent().show();
	    jQuery("#upload-label").parent().hide();
	}
    });
//    jQuery('input[name=ingesttype]:checked').parents;
});
