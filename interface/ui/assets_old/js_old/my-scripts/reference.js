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


if ($("#referenceTbl").length > 0) {
  assignValueToFilter();
  var pageNumber = (GetURLParameter("perpage"));
  get_datas(pageNumber);
}

function get_datas(page) {
  var url = "admin/reference/get_datas";
  ajax_filter(url, page, render_data, false)
}

function render_data(datas) {
  $("#referenceTbl").html(datas.str);
  filters();
  delete_data();
  add_reference();
}


function add_reference() {
  $(".btn-addData").on("click", function (e) {
    e.preventDefault();
    var id = $(this).attr('data-id');
    if (id != '' && id > 0) {
      var modal_title = 'Edit reference Plan';
    } else {
      var modal_title = 'Add New reference Plan';
    }
    var mediaDialog = bootbox.dialog({
      title: modal_title,
      size: 'medium',
      message: '<p><i class="fa fa-spin fa-spinner"></i> Loading...</p>'
    });
    //console.log(boxId);
    mediaDialog.init(function () {
      setTimeout(function () {
        $.post(urljs + "admin/reference/add_new", { 'id': id }, function (data) {
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
  $('#addedit_slider_form').validate({
    rules: {
      main_text: { required: true },
      status: { required: true },
      shop_url: { required: true }
    },
    messages: {
      main_text: { required: "Please provide the main text of a slider" },
      status: { required: "Please select the status" },
      shop_url: { required: "Please provide the shop url" },
    },
    errorClass: "help-block error",
    highlight: function (e) {
      $(e).closest(".form-group.row").addClass("has-error")
    },
    unhighlight: function (e) {
      $(e).closest(".form-group.row").removeClass("has-error")
    },
    submitHandler: function (e) {

      var form_btn = $('#addedit_slider_form').find('button[type="submit"]');
      var form_btn_old_msg = form_btn.html();

      //e.preventDefault();
      var formdata = new FormData($('#addedit_slider_form')[0]);
      $["ajax"]({
        url: urljs + "admin/reference/save_add_data",
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
        },
        error: function () {
          closeajax();
        }
      })
    }
  });
}


function delete_data() {
  $('.delData').unbind().click(function () {
    var mappingId = $(this).attr('data-id');
    swal({
      title: "Are you sure?",
      text: "You will not be able to recover this reference plan!",
      type: "warning",
      showCancelButton: true,
      cancelButtonClass: 'btn-secondary waves-effect',
      confirmButtonClass: 'btn-success',
      confirmButtonText: "Yes, delete it!",
      closeOnConfirm: true
    }, function (isConfirm) {
      if (isConfirm) {
        ajaxloading('Please wait...');
        $.post(urljs + 'admin/reference/delete_reference', { 'id': mappingId }, function (data) {
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