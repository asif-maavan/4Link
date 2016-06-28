$(document).ready(function () {
    $(".datepicker").datepicker({dateFormat: 'dd/mm/yy'});
    
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": true,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1200",
        "timeOut": "3000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

});


function imagePreview(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#prifile-image').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

function edit(id) {
    $('#' + id + 'E').trigger("reset");
    $('.form-div').addClass('hidden');
    $('.data').removeClass('hidden');
    $('#' + id + 'E').removeClass('hidden');
    $('#' + id + 'D').addClass('hidden');
}

function Msg(msg, type) {
    if (type == 'ERR')
        toastr.error(msg);
    else
        toastr.success(msg);
}
