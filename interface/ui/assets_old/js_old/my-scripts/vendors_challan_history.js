//customers
function filterCustomers() {
    $(".filter").unbind().on("click", function(e) {
        e.preventDefault();
        var page = 1;
        $("#pagenumber").val(page);
        get_challan_history(page);
    });
    $("#clearFilter").on("click",function(e){
        e.preventDefault();
        $('#adv-search').find('input.clearAbleFilt,select.clearAbleFilt').val('');
        $('#adv_filter_form').find('input.clearAbleFilt,select.clearAbleFilt').val('');
        $(".filter").click();
    });
    $("#page_result").unbind().on("click", ".pagination a", function(e) {
        e.preventDefault();
        var page = $(this).attr("data-page");
        $("#pagenumber").val(page);
        get_challan_history(page);
    });
}

if($("#challanTable").length>0){
    assignValueToFilter();    
    var pageNumber = (GetURLParameter("perpage"));
    get_challan_history(pageNumber);
}
       
function get_challan_history(page){
    ajaxloading('Loading...');
    var url="admin/vendors/get_challan_history_datas";
    ajax_filter(url, page, renderChallanHistory,false) 
}

function renderChallanHistory(datas) {
    $("#challanTable").html(datas.str);
    add_challan();


}

if($("#addedit_customer_form").length>0){
    saveCustomer();
}

function add_challan() {
    $(".btn-addData").on("click", function (e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        if (id) {
            var modal_title = 'Edit Challan';
        } else {
            var modal_title = 'Add New Challan';
        }
        var mediaDialog = bootbox.dialog({
            title: modal_title,
            size: 'medium',
            message: '<p><i class="fa fa-spin fa-spinner"></i> Loading...</p>'
        });
        //console.log(boxId);
        mediaDialog.init(function () {
            setTimeout(function () {
                $.post(urljs + "admin/vendors/add_challan", { 'id': id }, function (data) {
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
    $('#addedit_vendor_challan_form').validate({
        rules: {
            vendor_id: {
                required: true,
            },
        },
        messages: {
            vendor_id: { required: "Please provide Vendor ID" },
        },
        errorClass: "help-block error",
        highlight: function (e) {
            $(e).closest(".form-group.row").addClass("has-error")
        },
        unhighlight: function (e) {
            $(e).closest(".form-group.row").removeClass("has-error")
        },
        submitHandler: function (e) {

            var form_btn = $('#addedit_vendor_challan_form').find('button[type="submit"]');
            var form_btn_old_msg = form_btn.html();

            //e.preventDefault();
            var formdata = new FormData($('#addedit_vendor_challan_form')[0]);
            $["ajax"]({
                url: urljs + "admin/vendors/save_challan",
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
                    get_challan_history();
                    if (data.status == 'success') {
                        bootbox.hideAll();
                        swal_alert(data.status, data.message, 'success', '');
                    } else {
                        show_toast('warning', data.message);
                    }
                },
                error: function () {
                    closeajax();
                }
            })
        }
    });
}
