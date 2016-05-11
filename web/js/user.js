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
                    $('#' + id + 'D #user_role').text($('#' + id + 'E #userform-user_role').val());
                    $('#' + id + 'D #report_to').text($('#' + id + 'E #userform-report_to').val());
//                $('#'+id+'D #password').text($('#'+id+'E #userform-password').val());
//                $('#'+id+'D #confirm_password').text($('#'+id+'E #userform-confirm_password').val());
                    $('#' + id + 'E').addClass('hidden');
                    $('#' + id + 'D').removeClass('hidden');
                    alert('user successfuly updated');
                }

            }
        });
        return false;
    }
});

