jQuery(document).ready(function(){
    jQuery("#file-label").parent().find(':file').attr('name','file[0]');
    // jQuery("#file-label").parent().hide();
    jQuery("#url-label").parent().hide();
    jQuery('select[name=ingesttype]').change(function(){
        if (jQuery(this).val() == 'Upload') {
            jQuery("#file-label").parent().show();
            jQuery("#url-label").parent().hide();
        } else if(jQuery(this).val() == 'Url') {
            jQuery("#url-label").parent().show();
            jQuery("#file-label").parent().hide();
        }
    });
});

if (!Omeka) {
    var Omeka = {};
}

Omeka.AdminImages = {};

Omeka.AdminImages.setupDetails = function (detailsText, showDetailsText, hideDetailsText) {
    jQuery('.details').hide();
    jQuery('.action-links').prepend('<li><a href="#" class="details-link">' + detailsText + '</a></li>');

    jQuery('tr.admin-image-tr').each(function() {
        var imageDetails = jQuery(this).find('.details');
        if (jQuery.trim(imageDetails.html()) != '') {
            jQuery(this).find('.details-link').click(function(e) {
                e.preventDefault();
                imageDetails.slideToggle('fast');
            });
        }
    });

    var toggleList = '<a href="#" class="toggle-all-details full-width-mobile blue button">' + showDetailsText + '</a>';

    jQuery('.add-image').after(toggleList);

    // Toggle image details.
    var detailsShown = false;
    jQuery('.toggle-all-details').click(function (e) {
        e.preventDefault();
        if (detailsShown) {
            jQuery('.toggle-all-details').text(showDetailsText);
            jQuery('.details').slideUp('fast');
        } else {
            jQuery('.toggle-all-details').text(hideDetailsText);
            jQuery('.details').slideDown('fast');
        }
        detailsShown = !detailsShown;
    });
};
