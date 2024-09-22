//customers
function filters() {
    $(".filter").unbind().on("click", function (e) {
        e.preventDefault();
        var page = 1;
        $("#pagenumber").val(page);
        get_datas(page);
    });
    $("#clearFilter").on("click", function (e) {
        e.preventDefault();
        $('#adv-search').find('input.clearAbleFilt,select.clearAbleFilt').val('');
        $('#adv_filter_form').find('input.clearAbleFilt,select.clearAbleFilt').val('');
        $(".filter").click();
    });
    $("#page_result").unbind().on("click", ".pagination a", function (e) {
        e.preventDefault();
        var page = $(this).attr("data-page");
        $("#pagenumber").val(page);
        get_datas(page);
    });
}


if ($("#deliveryAddressTbl").length > 0) {
    assignValueToFilter();
    var pageNumber = (GetURLParameter("perpage"));
    get_datas(pageNumber);
}

function get_datas(page) {
    var url = "admin/delivery/get_addresss_datas";
    ajax_filter(url, page, render_data, false)
}

function render_data(datas) {
    $("#deliveryAddressTbl").html(datas.str);
    filters();
    update_status();
    delete_data();
    add_slider();
}


function add_slider() {
    $(".btn-addData").on("click", function (e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        if (id != '' && id > 0) {
            var modal_title = 'Edit Delivery Address';
        } else {
            var modal_title = 'Add New Delivery Address';
        }
        var mediaDialog = bootbox.dialog({
            title: modal_title,
            size: 'medium',
            message: '<p><i class="fa fa-spin fa-spinner"></i> Loading...</p>'
        });
        //console.log(boxId);
        mediaDialog.init(function () {
            setTimeout(function () {
                $.post(urljs + "admin/delivery/add_address", { 'id': id }, function (data) {
                    if (data.result > 0) {
                        mediaDialog.find('.bootbox-body').html(data.str);
                        mediaDialog.find("div.modal-dialog").addClass("sliderModal");
                        save_add_data();
                    }
                    else {
                        swal('Warning', 'Error occured!', 'warning');
                    }
                }, "json");
            }, 0);
        });
    });
}



function save_add_data() {
    $('#addedit_delivery_form').validate({
        rules: {
            
        },
        messages: {
           
        },
        errorClass: "help-block error",
        highlight: function (e) {
            $(e).closest(".form-group.row").addClass("has-error")
        },
        unhighlight: function (e) {
            $(e).closest(".form-group.row").removeClass("has-error")
        },
        submitHandler: function (e) {

            var form_btn = $('#addedit_delivery_form').find('button[type="submit"]');
            var form_btn_old_msg = form_btn.html();

            //e.preventDefault();
            var formdata = new FormData($('#addedit_delivery_form')[0]);
            $["ajax"]({
                url: urljs + "admin/delivery/save_delivery_address",
                type: "POST",
                dataType: "json",
                data: formdata,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
                },
                success: function (data) {
                    closeajax();
                    get_datas();
                    if (data.status == 'success') {
                        bootbox.hideAll();
                        swal_alert(data.status, data.message, 'success', '');
                    } else {
                        show_toast('warning', data.message);
                    }
                    // if (data.result > 0) {
                    //     $('#addedit_slider_form').find('input,textarea').val('');
                    //     form_btn.prop('disabled', false).html(form_btn_old_msg);
                    //     window.location.reload();
                    //     show_toast('success', data.msg);
                    // }
                    // else {
                    //     show_toast('warning', data.msg);
                    // }
                },
                error: function () {
                    closeajax();
                }
            })
        }
    });
}




function update_status() {
    $('.statusData').unbind().click(function () {
        var id = $(this).attr('data-id');
        var status = $(this).attr('data-status');
        //ajaxloading('Please wait...');
        $.post(urljs + 'admin/sliders/update_status', { 'id': id, 'status': status }, function (data) {
            //closeajax();
            if (data.status == 'success') {
                get_datas();
                show_toast('success', data.message)
            } else {
                swal('Warning', data.message, 'warning')
            }
        }, "json");
    });
}

function delete_data() {
    $('.delData').unbind().click(function () {
        var mappingId = $(this).attr('data-id');
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this data!",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: 'btn-secondary waves-effect',
            confirmButtonClass: 'btn-success',
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                ajaxloading('Please wait...');
                $.post(urljs + 'admin/delivery/delete', { 'id': mappingId }, function (data) {
                    closeajax();
                    if (data.status == 'success') {
                        get_datas();
                        show_toast('success', data.message)
                    } else {
                        swal('Warning', data.message, 'warning')
                    }
                }, "json");
            }
        });
    });
}
