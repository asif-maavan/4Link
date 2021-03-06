$(document).ready(function () {
    $(".datepicker").datepicker({dateFormat: 'dd/mm/yy'});
});

$('body').click(function (e) {
    if (!$(e.target).parents('.form-div').length && e.target.tagName != 'a' && $(e.target).parents('a').length == 0) {
        $('.form-div').addClass('hidden');
        $('.data').removeClass('hidden');
    }
});

$(document).on('beforeSubmit', 'form', function (e) {
    var form = $(this);
    var id = $('#' + form.attr('id') + ' #salesform-_id').val();
    if (id && form.attr('id') !== 'detailForm') {
        $.ajax({
            type: "POST",
            url: baseUrl + "sales/update",
            data: form.serialize(),
            dataType: "json",
            success: function (data) {
                if (data.msgType == 'SUC') {
                    $('#' + id + 'D #index_no').text($('#' + id + 'E #salesform-index_no').val());
                    $('#' + id + 'D #sale_executive').text($('#' + id + 'E #salesform-sale_executive option:selected').text());
                    $('#' + id + 'D #customer_type').text($('#' + id + 'E #salesform-customer_type option:selected').text());
                    $('#' + id + 'D #order_type').text($('#' + id + 'E #salesform-order_type option:selected').text());
                    $('#' + id + 'D #customer_acc_no').text($('#' + id + 'E #salesform-customer_acc_no').val());
                    $('#' + id + 'D #customer_name').text($('#' + id + 'E #salesform-customer_name option:selected').text());
                    $('#' + id + 'D #plan').text($('#' + id + 'E #salesform-plan option:selected').text());
                    $('#' + id + 'D #siebel_activity_no').text($('#' + id + 'E #salesform-siebel_activity_no').val());
                    $('#' + id + 'D #require_finance').text($('#' + id + 'E #salesform-require_finance option:selected').text());
                    $('#' + id + 'D #require_account_transfer').text($('#' + id + 'E #salesform-require_account_transfer option:selected').text());
                    if ($('#' + id + 'E #salesform-require_account_transfer').val() == '1') {
                        html = '<a id="' + id + '-submitted_to_finance" href="javascript:;" class="btn btn-primary submit-date-btn" onclick="submitTo(\'AT\', \'' + id + '\');"> <b>Submit</b> </a>';
                        $('#' + id + 'E #submitted_to_AT').html(html);
                    } else {
                        $('#' + id + 'E #submitted_to_AT').html('-');
                        $('#' + id + 'D #submitted_to_AT').html('-');
                    }
                    $('#' + id + 'D #so_no').text($('#' + id + 'E #salesform-so_no').val());
                    $('#' + id + 'D #order_state').text($('#' + id + 'E #salesform-order_state option:selected').text());
                    $('#' + id + 'E').addClass('hidden');
                    $('#' + id + 'D').removeClass('hidden');

                    formReset(id + 'E');
                    toastr.success('Sale successfully updated');
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
                $('#' + formid + ' #team_leader').val(data.agent.report_to);
            }

        }
    });
}

var customerList;
function switchC(target) {
    var type = $(target).val();
    var textInput = '<input type="text" id="salesform-customer_name" class="form-control" name="SalesForm[customer_name]">';
    var formid = $(target).closest('form').attr('id');
    if ($('#' + formid + ' #customer select').length)
            customerList = select = $('#' + formid + ' #customer select');
    if (type == '1') {
        $('#' + formid + ' #customer select').remove();
        $('#' + formid + ' #customer').prepend(textInput);
        $('#' + formid + ' #salesform-customer_acc_no').val('');
        $('#' + formid + ' #salesform-customer_acc_no').attr('readonly', false);

    } else {
        $('#' + formid + ' #customer select').remove();
        $('#' + formid + ' #customer input').remove();
        if (customerList)
            $('#' + formid + ' #customer').prepend(customerList);
        $('#' + formid + ' #salesform-customer_acc_no').attr('readonly', true);
    }
}

function getC(target) {
    id = $(target).val();
    formid = $(target).closest('form').attr('id');
    if (id) {
        $.ajax({
            type: "POST",
            url: baseUrl + "ajax/customer",
            data: {id: id},
            dataType: "json",
            success: function (data) {
                if (data.msgType == 'SUC') {
                    $('#' + formid + ' #salesform-customer_acc_no').val(data.acc);
                    if (id == newCustomer) {
                        $('#customer_type').val('New');
                    } else
                        $('#customer_type').val('Existing');
                }

            }
        });
    } else {
        $('#' + formid + ' #salesform-customer_acc_no').val('');
    }
}

function switchSAT(target) {
//    var type = $(target).val();
//    var formid = $(target).closest('form').attr('id');
//    var id = $('#' + formid + ' #salesform-_id').val();
//    if (!id) {
//        id = 'salesform';
//    }
//    if (type == '1') {
//        html = '<a id="' + id + '-submitted_to_finance" href="javascript:;" class="btn btn-primary submit-date-btn" onclick="submitTo(\'AT\', \'' + id + '\');"> <b>Submit</b> </a>';
//        $('#' + formid + ' #submitted_to_AT').html(html);
//    } else {
//        $('#' + formid + ' #submitted_to_AT').html('-');
//        $('#' + id + 'D #submitted_to_AT').html('-');
//    }

}
//............................................ plan
function getPlan() {
    id = $('#salesform-plan').val();
    if (id) {
        $.ajax({
            type: "POST",
            url: baseUrl + "ajax/plan",
            data: {id: id},
            dataType: "json",
            success: function (data) {
                if (data.msgType == 'SUC') {
                    $('#plan_group').val(data.plan.plan_group);
                    $('#plan_type').val(data.plan.plan_type);
                    $('#mrc').val(data.plan.mrc);
                    $('#contract_period').val(data.plan.contract_period);
                    $('#fourlink_points').val(data.plan.fourlink_points);
                    if ($('#salesform-qty').val()) {

                    }
                }

            }
        });
    } else {
        $('#plan_group').val('-');
        $('#plan_type').val('-');
        $('#mrc').val('-');
        $('#contract_period').val('-');
        $('#fourlink_points').val('-');
    }
}
// UI tweeks for Detail page
function toggleFIN(target) {
    selected = $(target).val();
    if (selected == '1') {
        $('#salesform-f_indicator').val('');
        $('#salesform-submitted_to_finance').val('');
        $('#salesform-f_response').val('');

        $('#salesform-f_indicator').attr('disabled', false);
        $('#salesform-f_state').attr('disabled', false);
        $('#salesform-submitted_to_finance').attr('disabled', false);
        $('#salesform-f_response').attr('disabled', false);

        $('#salesform-f_indicator').removeClass('input_dis');
        $('#salesform-f_state').removeClass('input_dis');
        //$('#salesform-submitted_to_finance').removeClass('input_dis');
        $('#salesform-f_response').removeClass('input_dis');

    } else {
        $('#salesform-f_indicator').attr('disabled', true);
        $('#salesform-f_state').attr('disabled', true);
        $('#salesform-submitted_to_finance').attr('disabled', true);
        $('#salesform-f_response').attr('disabled', true);

        $('#salesform-f_indicator').val('-');
        $('#salesform-f_state').val('');
        //$('#salesform-submitted_to_finance').val('-');
        $('#salesform-f_response').val('-');
        $('#f_difference').val('-');

        $('#salesform-f_indicator').addClass('input_dis');
        $('#salesform-f_state').addClass('input_dis');
        //$('#salesform-submitted_to_finance').addClass('input_dis');
        $('#salesform-f_response').addClass('input_dis');
    }
}

function toggleAT(target) {
    selected = $(target).val();
    if (selected == '1') {
        $('#salesform-at_indicator').val('');
        $('#salesform-submitted_to_at').val('');
        $('#salesform-at_response').val('');

        $('#salesform-at_indicator').attr('disabled', false);
        $('#salesform-at_state').attr('disabled', false);
        $('#salesform-submitted_to_at').attr('disabled', false);
        $('#salesform-at_response').attr('disabled', false);

        $('#salesform-at_indicator').removeClass('input_dis');
        $('#salesform-at_state').removeClass('input_dis');
        $('#salesform-submitted_to_at').removeClass('input_dis');
        $('#salesform-at_response').removeClass('input_dis');

    } else {
        $('#salesform-at_indicator').attr('disabled', true);
        $('#salesform-at_state').attr('disabled', true);
        $('#salesform-submitted_to_at').attr('disabled', true);
        $('#salesform-at_response').attr('disabled', true);

        $('#salesform-at_indicator').val('-');
        $('#salesform-at_state').val('');
        $('#salesform-submitted_to_at').val('-');
        $('#salesform-at_response').val('-');
        $('#at_difference').val('-');

        $('#salesform-at_indicator').addClass('input_dis');
        $('#salesform-at_state').addClass('input_dis');
        $('#salesform-submitted_to_at').addClass('input_dis');
        $('#salesform-at_response').addClass('input_dis');
    }
}

function toggleLD(target) {
    selected = $(target).val();
    if (selected == '1') {
        $('#salesform-ld_indicator').val('');
        $('#salesform-submitted_to_ld').val('');
        $('#salesform-ld_response').val('');

        $('#salesform-ld_indicator').attr('disabled', false);
        $('#salesform-ld_state').attr('disabled', false);
        $('#salesform-submitted_to_ld').attr('disabled', false);
        $('#salesform-ld_response').attr('disabled', false);

        $('#salesform-ld_indicator').removeClass('input_dis');
        $('#salesform-ld_state').removeClass('input_dis');
        $('#salesform-submitted_to_ld').removeClass('input_dis');
        $('#salesform-ld_response').removeClass('input_dis');

    } else {
        $('#salesform-ld_indicator').attr('disabled', true);
        $('#salesform-ld_state').attr('disabled', true);
        $('#salesform-submitted_to_ld').attr('disabled', true);
        $('#salesform-ld_response').attr('disabled', true);

        $('#salesform-ld_indicator').val('-');
        $('#salesform-ld_state').val('');
        $('#salesform-submitted_to_ld').val('-');
        $('#salesform-ld_response').val('-');
        $('#LD_difference').val('-');

        $('#salesform-ld_indicator').addClass('input_dis');
        $('#salesform-ld_state').addClass('input_dis');
        $('#salesform-submitted_to_ld').addClass('input_dis');
        $('#salesform-ld_response').addClass('input_dis');
    }
}

function toggleRG(target) {
    selected = $(target).val();
    if (selected == '1') {
        $('#salesform-rg_indicator').val('');
        $('#salesform-submitted_to_rg').val('');
        $('#salesform-rg_response').val('');
        $('#salesform-rg_state').val('');

        $('#salesform-rg_indicator').attr('disabled', false);
        $('#salesform-submitted_to_rg').attr('disabled', false);
        $('#salesform-rg_response').attr('disabled', false);
        $('#salesform-rg_state').attr('disabled', false);

        $('#salesform-rg_indicator').removeClass('input_dis');
        $('#salesform-submitted_to_rg').removeClass('input_dis');
        $('#salesform-rg_response').removeClass('input_dis');
        $('#salesform-rg_state').removeClass('input_dis');

    } else {
        $('#salesform-rg_indicator').attr('disabled', true);
        $('#salesform-submitted_to_rg').attr('disabled', true);
        $('#salesform-rg_response').attr('disabled', true);
        $('#salesform-rg_state').attr('disabled', true);

        $('#salesform-rg_indicator').val('-');
        $('#salesform-submitted_to_rg').val('');
        $('#salesform-rg_response').val('-');
        $('#salesform-rg_state').val('');
        $('#RG_difference').val('-');

        $('#salesform-rg_indicator').addClass('input_dis');
        $('#salesform-submitted_to_rg').addClass('input_dis');
        $('#salesform-rg_response').addClass('input_dis');
        $('#salesform-rg_state').addClass('input_dis');
    }
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

function submitTo(dep, sid) {
    var target = $(event.target);
    var eid = event.target.id ? event.target.id : target.parent().attr('id');
    var formid = target.closest('form').attr('id');
    if ($('#' + eid).html().trim() == '<b>Submit</b>') {

        id = (sid) ? sid : $('#salesform-_id').val();
        $.ajax({
            type: "POST",
            url: baseUrl + "ajax/sales-submit-to",
            data: {id: id, submitTo: dep},
            dataType: "json",
            success: function (data) {
                if (data.msgType == 'SUC') {
                    $('#' + eid).attr('disabled', true);
                    $('#' + eid).unbind('click');
                    $('#' + eid).html('<b>Submitted</b>');
                    if (data.status) {
                        if (sid) {
                            $('#' + formid + ' #salesform-order_state').val(data.status);
                            $('#' + sid + 'D #order_state').html(data.status);
                            $('#' + sid + 'D #submitted_to_AT').html(data.date);
                            formReset(id + 'E');
                        } else
                            $(".ost").val(data.status);
                    }
                    toastr.success('Submitted to ' + dep + ' successfully');
                }

            }
        });
    } else {
        return false;
    }
}

function soAssigned(sid) {
    var target = $(event.target);
    id = sid ? sid : $('#salesform-_id').val();
    so = sid ? target.val() : $('#salesform-so_no').val();
    var formid = target.closest('form').attr('id');
    $.ajax({
        type: "POST",
        url: baseUrl + "ajax/sales-so-assigned",
        data: {id: id, so: so},
        dataType: "json",
        success: function (data) {
            if (data.msgType == 'SUC') {
                if (data.status != '') {
                    if (sid) {
                        $('#' + formid + ' #salesform-order_state').val(data.status);
                        $('#' + sid + 'D #order_state').html(data.status);
                        $('#' + sid + 'D #so_no').html(so);
                        formReset(id + 'E');
                    } else
                        $(".ost").val(data.status);
                }
                toastr.success('SO is changed successfully.');
            }
        }
    });
}
