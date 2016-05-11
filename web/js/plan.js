/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {

});

$(document).on('beforeSubmit', 'form', function (e) {
    var form = $(this);
    var id = $('#' + form.attr('id') + ' #planform-_id').val();

    if (id) {
        $.ajax({
            type: "POST",
            url: baseUrl + "settings/plans/update",
            data: form.serialize(),
            dataType: "json",
            success: function (data) {
                if (data.msgType == 'SUC') {
                    $('#' + id + 'D #name').text($('#' + id + 'E #planform-name').val());
                    $('#' + id + 'D #plan_group').text($('#' + id + 'E #planform-plan_group').val());
                    $('#' + id + 'D #plan_type').text($('#' + id + 'E #planform-plan_type').val());
                    $('#' + id + 'D #contract_period').text($('#' + id + 'E #planform-contract_period').val());
                    $('#' + id + 'D #mrc').text($('#' + id + 'E #planform-mrc').val());
                    $('#' + id + 'D #fourlink_points').text($('#' + id + 'E #planform-fourlink_points').val());
                    $('#' + id + 'E').addClass('hidden');
                    $('#' + id + 'D').removeClass('hidden');
                    alert('user successfuly updated');
                }

            }
        });
        return false;
    }
});

function removePlan(id) {
    if (confirm('Are you sure you want to delete this Plan')) {
        var data = {'_id': id};
        $.ajax({
            type: "POST",
            url: baseUrl + "settings/plans/delete",
            data: data,
            dataType: "json",
            success: function (data) {
                if (data.msgType == 'SUC') {
                    $('#' + id + 'E').remove();
                    $('#' + id + 'D').remove();
                    //alert('Plan Successfuly Deleted');
                }

            }
        });
    }
} // end function 

