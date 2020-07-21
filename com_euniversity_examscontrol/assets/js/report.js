jQuery('.selectReport').live('click', function (e) {
    e.preventDefault();
    jQuery('input[name=layout]').remove();
    jQuery('input[name^=jfiltr]').attr('disabled',true);
    jQuery(this).parents('form:first').append('<input type="hidden" name="layout" value="' + jQuery(this).attr('name') + '"/>');
    jQuery('input[type=hidden]',jQuery(this).parents('form:first table')).attr('disabled',true);
    jQuery(this).parent().children('input[type=hidden]').attr('disabled',false);
    jQuery(this).parents('form:first').submit();
});
jQuery('#checkall').live('change', function (e) {
    jQuery(this).parents('form:first').find(':checkbox').attr('checked',this.checked);
});


