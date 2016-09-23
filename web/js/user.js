//$(document).ready(function () {
//            $('.form-div').focusout(function (e){
//                $(this).addClass('hidden');
//            });
//});

$('body').click(function (e) {
    //alert($(e.target).parents('a').length);
   //alert(parent.attr('id')); parent.hasClass('form-div')
    if(!$(e.target).parents('.form-div').length && e.target.tagName!= 'a' && $(e.target).parents('a').length==0 ){
                $('.form-div').addClass('hidden');
                $('.data').removeClass('hidden');
    }
});

$(document).on('beforeSubmit', 'form', function (e) {
    var form = $(this);
    var id = $('#' + form.attr('id') + ' #userform-_id').val();
    if (id) {
        $.ajax({
            type: "POST",
            url: baseUrl + "user/update",
            data: form.serialize(),
            dataType: "json",
            success: function (data) {
                if (data.msgType == 'SUC') {
                    $('#' + id + 'D #first_name').text($('#' + id + 'E #userform-first_name').val());
                    $('#' + id + 'D #last_name').text($('#' + id + 'E #userform-last_name').val());
                    $('#' + id + 'D #address').text($('#' + id + 'E #userform-address').val());
                    $('#' + id + 'D #email').text($('#' + id + 'E #userform-email').val());
                    $('#' + id + 'D #phone').text($('#' + id + 'E #userform-phone').val());
                    $('#' + id + 'D #user_role').text($('#' + id + 'E #userform-user_role option:selected').text());
                    $('#' + id + 'D #report_to').text($('#' + id + 'E #userform-report_to option:selected').text());
//                $('#'+id+'D #password').text($('#'+id+'E #userform-password').val());
//                $('#'+id+'D #confirm_password').text($('#'+id+'E #userform-confirm_password').val());
                    $('#' + id + 'E').addClass('hidden');
                    $('#' + id + 'D').removeClass('hidden');
                    toastr.success('User successfully updated');
                }

            }
        });
        return false;
    }
});

function getUsersList(id) {
    if(id == 'new'){
        var formId = 'create-form';
    } else{
        var formId = id+'E';
    }
    var role = $('#' + formId + ' #userform-user_role').val();
    if (role == '') {
        return false;
    }
    $.ajax({
        type: "POST",
        url: baseUrl + "user/get-report-to-list",
        data: {_id: id, role: role},
        dataType: "json",
        success: function (data) {
            if (data.msgType == 'SUC') {

                $('#' + formId + ' #userform-report_to').empty();
                $('#' + formId + ' #userform-report_to').append('<option value="">Select</option>');
                jQuery.each(data.list, function (index, item) {
                    $('#' + formId + ' #userform-report_to').append('<option value="' + index + '">' + item + '</option>');
                });
            }

        }
    });
}

function edit(id){
    $('#'+id+'E').trigger("reset");
    $('.form-div').addClass('hidden');
    $('.data').removeClass('hidden');
    $('#'+id+'E').removeClass('hidden');
    $('#'+id+'D').addClass('hidden');
}

