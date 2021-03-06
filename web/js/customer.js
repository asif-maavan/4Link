$(document).ready(function () {
//            $('.form-div').focusout(function (e){
//                $(this).addClass('hidden');
//            });

    $('#uploadform-pdffile').on('change', function () {
        file = $('uploadform-pdffile');
        data = file.data('yiiActiveForm');

        if (true) {

            var formData = new FormData($('form')[0]);
            var id = $('#customerform-_id').val();
            //formData.append('file', $('input[type=file]')[0].files[0]);
            $.ajax({
                type: "POST",
                url: baseUrl + "customers/document-upload?id=" + id,
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                beforeSend: function (XMLHttpRequest) {
                    $("#progressbar").progressbar({value: 0});
                    $("#progressbar").removeClass('hidden');
                },
                xhr: function ()
                {
                    var xhr = new window.XMLHttpRequest();
                    //Upload progress
                    xhr.upload.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            $("#progressbar").progressbar({value: percentComplete * 100});//Do something with upload progress
//                        console.log(percentComplete);
                        }
                    }, false);
                    //Download progress
                    xhr.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            //Do something with download progress
//                        console.log(percentComplete);
                        }
                    }, false);
                    return xhr;
                },
                success: function (data) {
                    $('#uploadform-filename').val('');
                    if (data.msgType == 'SUC') {
                        var d = new Date(data.document.date);
                        var doc = '<div id="doc-' + data.document.id.$id + '" class="row uploaded-files">\
                                <div class="col-sm-1" style="width:60px !important;"><img src="' + baseUrl + 'images/doc-icon1.png" class="uploaded-files-img2" alt=""></div>\
                                <div class="col-sm-5" style="width: 36% !important;">' + data.document.name + '</div>\
                                <div class="col-sm-4 width-fix"><img src="' + baseUrl + 'images/doc-icon2.png" class="uploaded-files-img2" alt="">' + $.datepicker.formatDate('MM dd, yy', d) + '</div>\
                                <div class="col-sm-1 uploaded-files-img"><a href="' + baseUrl + 'uploads/documents/' + data.document.file + '" download><img src="' + baseUrl + 'images/doc-icon3.png" alt=""></a></div>\
                                <div class="col-sm-1 uploaded-files-img"><a href="' + baseUrl + 'uploads/documents/' + data.document.file + '"><img src="' + baseUrl + 'images/doc-icon4.png" width="23" height="27" alt=""></a></div>\
                                <div class="col-sm-1 uploaded-files-img"><a href="javascript:;" onclick="rmvdoc(\'' + data.document.id.$id + '\')"><img src="' + baseUrl + 'images/doc-icon5.png" width="23" height="27" alt=""></a></div>\
                            </div>';
                        $('#documents').prepend(doc);
                        $("#progressbar").progressbar({value: 100});
                        $("#progressbar").addClass('hidden');
                        $('#ndf').remove();
                    }else{
                        $("#progressbar").addClass('hidden');
                    }
                    Msg(data.msg, data.msgType);
                },
                error: function () {
                    Msg("Something went wrong, Please try again later.", "ERR");
                }
            });
        }
    });
});

function upload() {
    var name = $('#uploadform-filename');
    if (name.val()) {
        $('#uploadform-pdffile').click();
    } else {
        toastr.error('Document name can not be blank');
        name.focus();
    }
}

$('body').click(function (e) {
    if (!$(e.target).parents('.form-div').length && e.target.tagName != 'a' && $(e.target).parents('a').length == 0) {
        $('.form-div').addClass('hidden');
        $('.data').removeClass('hidden');
    }
});
$(document).on('beforeSubmit', 'form', function (e) {
    var form = $(this);
    var id = $('#' + form.attr('id') + ' #customerform-_id').val();
    if (id && form.attr('id') !== 'detailForm') {
        $.ajax({
            type: "POST",
            url: baseUrl + "customers/update",
            data: form.serialize(),
            dataType: "json",
            success: function (data) {
                if (data.msgType == 'SUC') {
                    $('#' + id + 'D #customer_acc').text($('#' + id + 'E #customerform-customer_acc').val());
                    $('#' + id + 'D #first_name').text(ucwords($('#' + id + 'E #customerform-first_name').val()));
                    $('#' + id + 'D #account_no').text($('#' + id + 'E #customerform-account_no').val());
                    $('#' + id + 'D #address').text($('#' + id + 'E #customerform-address').val());
                    $('#' + id + 'D #phone').text($('#' + id + 'E #customerform-phone').val());
                    $('#' + id + 'D #sales_agent').text($('#' + id + 'E #customerform-sales_agent option:selected').text());
                    $('#' + id + 'D #agent_phone').text($('#' + id + 'E #customerform-agent_phone').val());
                    $('#' + id + 'E').addClass('hidden');
                    $('#' + id + 'D').removeClass('hidden');
                    
                    formReset(id + 'E');
                    toastr.success('Customer successfully updated');
                }

            }
        });
        return false;
    }
});
function getExecutive(target) {
    id = $(target).val();
    formid = $(target).closest('form').attr('id');
    //alert(formid);
    $.ajax({
        type: "POST",
        url: baseUrl + "ajax/sales-executive",
        data: {id: id},
        dataType: "json",
        success: function (data) {
            if (data.msgType == 'SUC') {
                data.phone;
                $('#' + formid + ' #customerform-agent_phone').val(data.phone);
                $('#' + formid + ' #agent_phone').text(data.phone);
            }

        }
    });
}

function rmvdoc(id) {
    if (confirm('Are you sure you want to remove this Document? ')) {
        var cstmrId = $('#customerform-_id').val();
        $.ajax({
            type: "POST",
            url: baseUrl + "customers/remove-doc",
            data: {customerId: cstmrId, id: id},
            dataType: "json",
            success: function (data) {
                if (data.msgType == 'SUC') {
                    toastr.success('Document is successfully Removed')
                    $('#doc-' + id).remove();
                    if ($('#documents div').length == 0) {
                        $('#documents').append('<h3 id="ndf" class="text-center">No Document Found</h3>');
                    }
                }

            }
        });
    }
}
