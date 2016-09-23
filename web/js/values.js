//$(document).ready(function () {
//            $('.form-div').focusout(function (e){
//                $(this).addClass('hidden');
//            });
//});

$('body').click(function (e) {
    //alert($(e.target).parents('a').length);
    //alert(parent.attr('id')); parent.hasClass('form-div')
    if (!$(e.target).parents('.form-div').length && e.target.tagName != 'a' && $(e.target).parents('a').length == 0) {
        $('.form-div').addClass('hidden');
        $('.data').removeClass('hidden');
    }
});

$(document).on('beforeSubmit', 'form', function (e) {
    var form = $(this); //alert(form.hasClass('acctype'));
    if (form.hasClass('acctype')) {
        var id = $('#' + form.attr('id') + ' #accounttypeform-_id').val();
        if (id) {
            $.ajax({
                type: "POST",
                url: baseUrl + "settings/values/account-type",
                data: form.serialize(),
                dataType: "json",
                success: function (data) {
                    if (data.msgType == 'SUC') {
                        $('#' + id + 'D #type_name').text($('#' + id + 'E #accounttypeform-type_name').val());

                        $('#' + id + 'E').addClass('hidden');
                        $('#' + id + 'D').removeClass('hidden');
                        toastr.success('Account Type successfully updated');
                    }

                }
            });
            return false;
        }
    }else if (form.hasClass('ordtype')) { 
        var id = $('#' + form.attr('id') + ' #ordertypeform-_id').val();
        if (id) {
            $.ajax({
                type: "POST",
                url: baseUrl + "settings/values/order-type",
                data: form.serialize(),
                dataType: "json",
                success: function (data) {
                    if (data.msgType == 'SUC') {
                        $('#' + id + 'D #type_name').text($('#' + id + 'E #ordertypeform-type_name').val());
                        $('#' + id + 'E').addClass('hidden');
                        $('#' + id + 'D').removeClass('hidden');
                        toastr.success('Order Type successfully updated');
                    }

                }
            });
            return false;
        }
    }
    
});

function removeAccType(id) {
    if (confirm('Are you sure you want to delete this Plan')) {
        var data = {'_id': id};
        $.ajax({
            type: "POST",
            url: baseUrl + "settings/values/delete-account-type",
            data: data,
            dataType: "json",
            success: function (data) {
                if (data.msgType == 'SUC') {
                    $('#' + id + 'E').remove();
                    $('#' + id + 'D').remove();
                    toastr.success('Account Type successfully Removed');
                }

            }
        });
    }
} // end function 

function removeOrdType(id) {
    if (confirm('Are you sure you want to delete this Plan')) {
        var data = {'_id': id};
        $.ajax({
            type: "POST",
            url: baseUrl + "settings/values/delete-order-type",
            data: data,
            dataType: "json",
            success: function (data) {
                if (data.msgType == 'SUC') {
                    $('#' + id + 'E').remove();
                    $('#' + id + 'D').remove();
                    toastr.success('Order Type successfully Removed');
                }

            }
        });
    }
} // end function 

function edit(id) {
    $('#' + id + 'E').trigger("reset");
    $('.form-div').addClass('hidden');
    $('.data').removeClass('hidden');
    $('#' + id + 'E').removeClass('hidden');
    $('#' + id + 'D').addClass('hidden');
}

