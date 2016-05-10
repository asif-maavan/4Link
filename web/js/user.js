$(document).on('beforeSubmit', 'form',function(e) {
   var form = $(this);
   var id = $('#'+form.attr('id')+ ' #userform-_id').val();
   var data = form.serialize();
   $.ajax({
        type: "POST",
        url: baseUrl + "user/update",
        data: form.serialize(),
        dataType: "json",
        success: function (data) {
            if(data.msgType=='SUC'){
                $('#'+id+'D #first_name').text($('#'+id+'E #userform-first_name').val());
                $('#'+id+'D #last_name').text($('#'+id+'E #userform-last_name').val());
                $('#'+id+'D #address').text($('#'+id+'E #userform-address').val());
                $('#'+id+'D #email').text($('#'+id+'E #userform-email').val());
                $('#'+id+'D #phone').text($('#'+id+'E #userform-phone').val());
                $('#'+id+'D #user_role').text($('#'+id+'E #userform-user_role').val());
                $('#'+id+'D #report_to').text($('#'+id+'E #userform-report_to').val());
//                $('#'+id+'D #password').text($('#'+id+'E #userform-password').val());
//                $('#'+id+'D #confirm_password').text($('#'+id+'E #userform-confirm_password').val());
                $('#'+id+'E').addClass('hidden');
                $('#'+id+'D').removeClass('hidden');
                alert('user updated  --  '+id);
            }
            
        }
    });
   return false;
});

// user update
function userUpdate(formId) {
    var form = formId+'E';
    $('#'+form).submit();
//    var param = {id: formId};
//    $.ajax({
//        type: "POST",
//        url: baseUrl + "user/update",
//        data: form.serialize(),
//        dataType: "json",
//        success: function (data) {
//            alert('user updated');
//        }
//    });
//    return false;
}