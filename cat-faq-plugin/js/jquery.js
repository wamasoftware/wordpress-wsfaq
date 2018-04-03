jQuery(document).ready(function () {
    jQuery('.accordion').on('click', function () {
        jQuery(this).next().slideToggle().siblings('.answer').slideUp();
    });

    jQuery('#selectdrp').change(function () {
        var getValue = jQuery(this).val();

        jQuery('.cat_all').hide();
        jQuery('.cat_' + getValue).show();
    });

    jQuery('#formid #searchid').change(function () {
        jQuery('.cat_all').hide();
        jQuery('.searchresult').show();
    });

    jQuery('body').delegate('.cat_2.cat_all', 'click', function () {
        jQuery('.cat_2.cat_all').removeClass('faq_active');
        jQuery(this).addClass('faq_active');
    });
    
});