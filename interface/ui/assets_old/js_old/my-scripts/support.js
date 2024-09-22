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

if ($("#supportTbl").length > 0) {
  assignValueToFilter();
  var pageNumber = (GetURLParameter("perpage"));
  get_datas(pageNumber);
}

function get_datas(page) {
  ajaxloading('Loading...');
  var url = "admin/support/get_datas";
  ajax_filter(url, page, render_data, false)
}

function render_data(datas) {
  $("#supportTbl").html(datas.str);
  filters();
  view_ticket();

}

function view_ticket() {
  $('.btn-tktView').unbind().on('click', function (e) {
    //e.preventDefault();
    var id = $(this).attr('data-id');
    var code = $(this).attr('data-code');
    var ticketModal = bootbox.dialog({
      title: 'Update ticket - ' + code,
      message: '<p class="loaderBlock"><i class="fa fa-spinner fa-spin"></i> Loading, Please wait...</p>',
      closeButton: true,
      size: 'medium',
      animate: true,
      // className: "small ticketViewMdl",
    });
    //ajaxloading('Please wait...');
    $.post(urljs + "admin/support/view", { 'id': id, 'code': code }, function (data) {
      //closeajax();
      if (data.status == 'success') {
        ticketModal.find('.bootbox-body').html(data.msg);
        $('[data-toggle="tooltip"]').tooltip();
        $('textarea.textEditor').ckeditor();
        update_ticket();
        scroll_down();
      } else {
        swal("Error!", data.msg, "warning");
      }
    }, "json");
  });
}


function scroll_down() {
  $('#ticketsChatTbl').stop().animate({ scrollTop: $('#ticketsChatTbl')[0].scrollHeight }, 1000);
}

function update_ticket() {
  var form_btn = $("#ticket_reply_form").find('button[type="submit"]');
  var form_btn_old_msg = form_btn.html();
  $('#ticket_reply_form').validate({
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
      var formdata = new FormData($('#ticket_reply_form')[0]);
      // formdata.append('message', editorInput);
      $["ajax"]({
        url: urljs + "admin/support/update_ticket",
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
          form_btn.prop('disabled', false).html(form_btn_old_msg);
          if (data.msg == 'success') {
            swal_alert('success', data.msg, 'success', '');
            bootbox.hideAll();
            // window.location.reload();
          }
          else {
            swal('Failed!', data.msg, 'warning');
          }
          closeajax()
        },
        error: function () { }
      })
      // }
    }
  });
}