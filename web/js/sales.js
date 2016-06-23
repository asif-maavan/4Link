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
                    var submitted_to_at = ($('#' + id + 'E #' + id + '-submitted_to_at').val()) ? $('#' + id + 'E #' + id + '-submitted_to_at').val() : '-';
                    $('#' + id + 'D #submitted_to_AT').text(submitted_to_at);
                    $('#' + id + 'D #sale_no').text($('#' + id + 'E #salesform-sale_no').val());
                    $('#' + id + 'D #order_state').text($('#' + id + 'E #salesform-order_state option:selected').text());
                    $('#' + id + 'E').addClass('hidden');
                    $('#' + id + 'D').removeClass('hidden');

                    formReset(id + 'E');
                    toastr.success('Sale successfuly updated');
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
    if (type == '1') {
        if ($('#' + formid + ' #customer select').length)
            customerList = select = $('#' + formid + ' #customer select');
        $('#' + formid + ' #customer select').remove();
        $('#' + formid + ' #customer').prepend(textInput);
        $('#' + formid + ' #salesform-customer_acc_no').attr('readonly', false);

    } else {
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
    var type = $(target).val();
    var formid = $(target).closest('form').attr('id');
    var id = $('#' + formid + ' #salesform-_id').val();//alert($('#' + formid + ' #' + id + '-submitted_to_AT').val());
    if (!id) {
        id = 'salesform';
    }
    if (type == '1') {
        $('#' + formid + ' #' + id + '-submitted_to_at').attr('readonly', false);
        $('#' + formid + ' #' + id + '-submitted_to_at').datepicker({dateFormat: 'dd/mm/yy'});
    } else {
        $('#' + formid + ' #' + id + '-submitted_to_at').val('');
        $('#' + formid + ' #' + id + '-submitted_to_at').attr('readonly', true);
        $('#' + formid + ' #' + id + '-submitted_to_at').datepicker("destroy");
    }

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
                    if($('#salesform-qty').val()){
                        
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
