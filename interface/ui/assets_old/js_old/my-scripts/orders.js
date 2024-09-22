//customers
function filterBookings() {
  $(".filter").unbind().on("click", function (e) {
    e.preventDefault();
    var page = 1;
    $("#pagenumber").val(page);
    getBookings(page);
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
    getBookings(page);
  });
}

if ($("#ordersTbl").length > 0) {
  assignValueToFilter();
  var pageNumber = (GetURLParameter("perpage"));
  getBookings(pageNumber);
}

function getBookings(page) {
  ajaxloading('Loading...');
  var url = "admin/orders/get_bookings";
  ajax_filter(url, page, renderBookings, false)
}

function renderBookings(datas) {
  $("#ordersTbl").html(datas.str);
  filterBookings();
  assign_order();
}


if ($('#edit_ubook_form').length > 0) {
  update_total();
  calgtotal();
  save_data();
  assign_order();
}

function update_total(param) {
  var obj = $(param).parent().parent();
  var tot = obj.find(".prdTotal");
  var qty = obj.find(".prdQty").val();
  var tax = obj.find(".prdTax").val();
  var pri = obj.find(".prdPrice").val();
  pri = pri == "" ? 0 : parseFloat(pri);
  tax = tax == "" ? 0 : (pri) * (parseFloat(tax) / 100);
  qty = qty == "" ? 1 : parseInt(qty);
  tot.val(tot.val() == "" ? 0 : 0);
  var total = (qty * (pri + tax));
  totalpri = Math.round(total * 100) / 100;
  tot.val(totalpri);
  calgtotal();
}

function calgtotal() {
  var gt = 0;
  $(".prdTotal").each(function (x, y) {
    gt += parseFloat($(y).val());
  });
  totalpri = Math.round(gt * 100) / 100;
  //$(".quotgtotal").html(totalpri);
  if (totalpri > 0) {
    $("[name=total_amount]").val(totalpri);
  }
}

function save_data() {
  $('#edit_ubook_form').validate({
    submitHandler: function (e) {
      event.preventDefault();
      var formdata = new FormData($('#edit_ubook_form')[0]);
      $["ajax"]({
        url: urljs + "admin/orders/update_booking",
        type: "POST",
        dataType: "json",
        data: formdata,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
          ajaxloading("Saving... Please Wait...");
        },
        success: function (data) {
          closeajax();
          if (data.result == 'success') {
            swal_alert('Success', data.msg, 'success', 'admin/orders');
          }
          else {
            swal_alert('warning', data.msg, 'warning', '');
          }
        },
        error: function () {
          closeajax();
        }
      })
    }
  });
}

function assign_order() {
  $('.btn-assign').unbind().on('click', function (e) {
    //e.preventDefault();
    var id = $(this).attr('data-id');
    var code = $(this).attr('data-code');
    var ticketModal = bootbox.dialog({
      title: 'Assign Order to Delivery Boy - <br> Order Id ' + code,
      message: '<p class="loaderBlock"><i class="fa fa-spinner fa-spin"></i> Loading, Please wait...</p>',
      closeButton: true,
      size: 'medium',
      animate: true,
      // className: "small ticketViewMdl",
    });
    //ajaxloading('Please wait...');
    $.post(urljs + "admin/orders/assign_order", { 'id': id, 'code': code }, function (data) {
      //closeajax();
      if (data.status == 'success') {
        ticketModal.find('.bootbox-body').html(data.msg);
        $('[data-toggle="tooltip"]').tooltip();
        update_assignedorder();
        // scroll_down();
      } else {
        swal("Error!", data.msg, "warning");
      }
    }, "json");
  });
}
function update_assignedorder() {
  var form_btn = $("#assign_order_form").find('button[type="submit"]');
  var form_btn_old_msg = form_btn.html();
  $('#assign_order_form').validate({
    rules: {},
    messages: {},
    errorClass: "help-block error",
    highlight: function (e) {
      $(e).closest(".form-group.row").addClass("has-error")
    },
    unhighlight: function (e) {
      $(e).closest(".form-group.row").removeClass("has-error")
    },
    submitHandler: function () {
      // var editorInput = CKEDITOR.instances.message.getData();
      // var editorBox = $('#ticket_reply_form').find('#cke_message');
      // if (editorInput == '') {
      //   editorBox.after('<label class="help-block error">Please type your message to update...</label>')
      // } else {
      var formdata = new FormData($('#assign_order_form')[0]);
      // formdata.append('message', editorInput);
      $["ajax"]({
        url: urljs + "admin/orders/update_assign_order",
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
          // console.log("from 1");
          // console.log(data);
          // return;
          form_btn.prop('disabled', false).html(form_btn_old_msg);
          if (data.status == 'success') {
            swal('success', data.message, 'success');
            // bootbox.hideAll();
            window.location.reload();
          }else {
            swal('Failed!', data.message, 'warning');
          }
          closeajax()
        },
        error: function () { }
      })
      // }
    }
  });
}