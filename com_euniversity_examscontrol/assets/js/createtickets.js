jQuery('#checkAllBtn').live('change', function (e) {
    jQuery('table tbody input[type=checkbox]').not(':disabled').attr('checked', jQuery( this ).is( ':checked' ) ? true : false);
    if(jQuery('input[name*=streams]:enabled:checked').length > 0){
        jQuery('button#createBtn').attr('disabled', false).show();
    }else {
        jQuery('button#createBtn').attr('disabled', true).hide();
    }
});
jQuery('input[name=qradio]').live('change', function (e) {
    jQuery('#newQ').attr('disabled', jQuery('#newQradio').is(':checked') ? false : true);
});

jQuery('input[name*=streams]').live('change', function (e) {
    if(jQuery('input[name*=streams]:enabled:checked').length > 0){
        jQuery('button#createBtn').attr('disabled', false).show();
    }else {
        jQuery('button#createBtn').attr('disabled', true).hide();
    }
    if(jQuery('input[name*=streams]:enabled').length != jQuery('input[name*=streams]:enabled:checked').length){
        jQuery('#checkAllBtn').attr('checked',false);
    }else if(jQuery('input[name*=streams]:enabled').length == jQuery('input[name*=streams]:enabled:checked').length){
        jQuery('#checkAllBtn').attr('checked',true);
    }
});

jQuery('mark.addon').live('click', function (e) {
    e.preventDefault();
    jQuery('#addon input[name=\'test_id\']').val(jQuery(this).attr('data-test'));
    jQuery('#addon input[name=\'students\']').val(jQuery(this).attr('data-students'));
    jQuery(this).attr('data-test','');
    jQuery(this).attr('data-students','');
    jQuery('#addon').submit();
});

jQuery('mark.students').live('click', function (e) {
    jQuery(this).toggleClass('active');
    jQuery(this).closest('tr').find('ul:first').toggleClass('hiddenElement');
});

jQuery('mark.tickets').live('click', function (e) {
    e.preventDefault();
    jQuery('input[name=layout]').attr('disabled',false);
    jQuery('input[name=task]').attr('disabled',true);
    var str = jQuery(this).closest('tr').find('input[name*=streams]').attr('name');
    str = str.replace(/streams\[/g,'');
    str = str.replace(/\]/g,'');
    str = str.split(/--/g);
    jQuery('input[name=spec]').val(str[0]);
    jQuery('input[name=teacher]').val(str[1]);
    jQuery(this).closest('tr').find('input[name=test_id]').attr('disabled',false);
    jQuery(this).closest('tr').find('input[name=students_count]').attr('disabled',false);
    jQuery(this).closest('tr').find('input[name=teacher_name]').attr('disabled',false);
    jQuery(this).closest('tr').find('input[name=stream_key]').attr('disabled',false);
    jQuery(this).closest('tr').find('input[name=spec_name]').attr('disabled',false);
    jQuery(this).closest('tr').find('input[name=students_names]').attr('disabled',false);
    jQuery('form#create').submit();
    jQuery('input[name=layout]').attr('disabled',false);
    jQuery('input[name=spec]').val('');
    jQuery('input[name=teacher]').val('');
    jQuery(this).closest('tr').find('input[name=test_id]').attr('disabled',true);
    jQuery(this).closest('tr').find('input[name=students_count]').attr('disabled',true);
    jQuery(this).closest('tr').find('input[name=teacher_name]').attr('disabled',true);
    jQuery(this).closest('tr').find('input[name=stream_key]').attr('disabled',true);
    jQuery(this).closest('tr').find('input[name=spec_name]').attr('disabled',true);
    jQuery(this).closest('tr').find('input[name=students_names]').attr('disabled',true);
});


jQuery('button#createBtn').live('click', function (e) {
    e.preventDefault();
    jQuery('input[name=layout]').attr('disabled',true);
    jQuery('input[name=spec]').val('');
    jQuery('input[name=teacher]').val('');
    jQuery('input[name=test_id]').attr('disabled',true);
    jQuery('input[name=students_count]').attr('disabled',true);
    jQuery('input[name=teacher_name]').attr('disabled',true);
    jQuery('input[name=stream_key]').attr('disabled',true);
    jQuery('input[name=spec_name]').attr('disabled',true);
    jQuery('input[name=students_names]').attr('disabled',true);
    jQuery('input[name=task]').attr('disabled',false);
    jQuery('form#create').submit();
    jQuery('input[name=task]').attr('disabled',true);
    jQuery('input[name*=streams]').attr('checked',false);
});

//$("#loading").ajaxStart(function () {
//            $(this).show();
//                    });

jQuery('.get_code').live('click', function (e) {
    jQuery('.small_button').removeClass('last').html();
    var this_button = jQuery(this);
    this_button.addClass("loading").html('');
    jQuery.ajax({
        type: 'GET',
        url: 'index.php?option=com_euniversity_examscontrol&task=manage.hasCame&format=json',
        dataType: 'json',
        data: { clear_code: jQuery(this).attr('id')},
        cache : false,
        success: function(response) {
            if(response!=null){
                this_button.removeClass('get_code').removeClass('loading').addClass('del_code').addClass('last').html(response);
            }else{
                alert('Error: empty response');
            }
        },
        error: function (xhr, status) {
            alert('Error: Unknown error ' + status); 
        }
    });
});
jQuery('.del_code').live('click', function (e) {
    jQuery('.small_button').removeClass('last').html();
    var this_button = jQuery(this);
    this_button.addClass("loading").html('');
    jQuery.ajax({
        type: 'GET',
        url: 'index.php?option=com_euniversity_examscontrol&task=manage.notCame&format=json',
        dataType: 'json',
        data: { clear_code: jQuery(this).attr('id')},
        cache : false,
        success: function(response) {
            if(response!=null){
                this_button.removeClass('del_code').removeClass('loading').addClass('get_code').addClass('last').html('Явка');
            }else{
                alert('Error: empty response');
            }
        },
        error: function (xhr, status) {
            alert('Error: Unknown error ' + status); 
        }
    });
});
//    jQuery(this).toggleClass('active');
//    jQuery(this).closest('tr').find('ul:first').toggleClass('hiddenElement');


jQuery('form#create').live('submit',function (e) {
    if(jQuery('input[name=task]',this).is(':enabled')){
        //alert(jQuery('input[name=task]',this).is(':enabled'));
	var specs   = jQuery(this).find('input:checkbox:checked');
	var tickets = jQuery(this).find('input:radio:checked');
	var file    = jQuery(this).find('input#newQ').val();
	if(specs.length < 1 || tickets.length < 1){
	    e.preventDefault();
	    var message = '<dl id="system-message"><dt class="notice">Предупреждение</dt><dd class="notice message"><ul>';
	    if(specs.length < 1)
		message += '<li>Вы не выбрали специальности</li>';
	    if(tickets.length < 1)
		message += '<li>Вы не выбрали воросник</li>';
	    jQuery('#system-message-container').html(message += '</ul></dd></dl>');
	    window.scrollTo(0, document.getElementById('system-message-container').offsetTop);
	}else if(tickets.val() == '0' && file == ''){
	    e.preventDefault();
	    var message = '<dl id="system-message"><dt class="notice">Предупреждение</dt><dd class="notice message"><ul>';
	    message += '<li>Вы не выбрали фаил с вопросами</li>';
	    jQuery('#system-message-container').html(message += '</ul></dd></dl>');
	    window.scrollTo(0, document.getElementById('system-message-container').offsetTop);
	}
    }
});
