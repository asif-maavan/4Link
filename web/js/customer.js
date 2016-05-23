//$(document).ready(function () {
//            $('.form-div').focusout(function (e){
//                $(this).addClass('hidden');
//            });
//});

$('body').click(function (e) {
    if (!$(e.target).parents('.form-div').length && e.target.tagName != 'a' && $(e.target).parents('a').length == 0) {
        $('.form-div').addClass('hidden');
        $('.data').removeClass('hidden');
    }
});

$(document).on('beforeSubmit', 'form', function (e) {
    var form = $(this);
    var id = $('#' + form.attr('id') + ' #customerform-_id').val();
    if (id) {
        $.ajax({
            type: "POST",
            url: baseUrl + "customer/update",
            data: form.serialize(),
            dataType: "json",
            success: function (data) {
                if (data.msgType == 'SUC') {
                    $('#' + id + 'D #customer_acc').text($('#' + id + 'E #customerform-customer_acc').val());
                    $('#' + id + 'D #first_name').text($('#' + id + 'E #customerform-first_name').val());
                    $('#' + id + 'D #account_no').text($('#' + id + 'E #customerform-account_no').val());
                    $('#' + id + 'D #address').text($('#' + id + 'E #customerform-address').val());
                    $('#' + id + 'D #phone').text($('#' + id + 'E #customerform-phone').val());
                    $('#' + id + 'D #sales_agent').text($('#' + id + 'E #customerform-sales_agent option:selected').text());
                    $('#' + id + 'D #agent_phone').text($('#' + id + 'E #customerform-agent_phone').val());
                    $('#' + id + 'E').addClass('hidden');
                    $('#' + id + 'D').removeClass('hidden');
                    alert('Customer successfuly updated');
                }

            }
        });
        return false;
    }
});
