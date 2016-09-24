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

function ucwords(str) {
    //    input by: James (http://www.james-bell.co.uk/)
    //   example 1: ucwords('kevin van  zonneveld')
    //   returns 1: 'Kevin Van  Zonneveld'
    //   example 2: ucwords('HELLO WORLD')
    //   returns 2: 'HELLO WORLD'

    return (str + '')
            .replace(/^([a-z\u00E0-\u00FC])|\s+([a-z\u00E0-\u00FC])/g, function ($1) {
                return $1.toUpperCase()
            })
}

function formReset(formId) {
    $('#' + formId + ' input').attr('value', function () {
        return this.value
    });
    $('#' + formId + ' textarea').prop('innerHTML', function () {
        return this.value
    });
    $('#' + formId + ' :checked').attr('checked', 'checked');
    $('#' + formId + ' :selected').attr('selected', 'selected');
    $('#' + formId + ' :not(:checked)').removeAttr('checked');
    $('#' + formId + ' :not(:selected)').removeAttr('selected');
}

function GET(param) {
    var vars = {};
    window.location.href.replace(location.hash, '').replace(
            /[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
            function (m, key, value) { // callback
                vars[key] = value !== undefined ? value : '';
            }
    );

    if (param) {
        return vars[param] ? vars[param] : null;
    }
    return vars;
}

function sort(sort) {
    $('#sort').val(sort);
    $('#filterForm').submit();
}

function  filterInputs() {
    var myForm = document.getElementById('filterForm');
    var allInputs = myForm.getElementsByTagName('input');
    var input, i;

    for (i = 0; input = allInputs[i]; i++) {
        if (input.getAttribute('name') && !input.value) {
            input.setAttribute('name', '');
        }
    }
}
