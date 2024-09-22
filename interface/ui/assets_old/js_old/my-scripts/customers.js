//Vendors Challan History
function filterCustomers() {
  $(".filter")
    .unbind()
    .on("click", function (e) {
      e.preventDefault();
      var page = 1;
      $("#pagenumber").val(page);
      getCustomers(page);
    });
  $("#clearFilter").on("click", function (e) {
    e.preventDefault();
    $("#adv-search").find("input.clearAbleFilt,select.clearAbleFilt").val("");
    $("#adv_filter_form")
      .find("input.clearAbleFilt,select.clearAbleFilt")
      .val("");
    $(".filter").click();
  });
  $("#page_result")
    .unbind()
    .on("click", ".pagination a", function (e) {
      e.preventDefault();
      var page = $(this).attr("data-page");
      $("#pagenumber").val(page);
      getCustomers(page);
    });
}

if ($("#customersTbl").length > 0) {
  assignValueToFilter();
  var pageNumber = GetURLParameter("perpage");
  getCustomers(pageNumber);
}

function getCustomers(page) {
  ajaxloading("Loading...");
  var url = "admin/customers/get_customers";
  ajax_filter(url, page, renderCustomers, false);
}

function renderCustomers(datas) {
  $("#customersTbl").html(datas.str);
  deleteCustomer();
  filterCustomers();
  export_data();
}

if ($("#addedit_customer_payment_form").length > 0) {
  saveSubscriptionUpdateDetails();
}

function saveSubscriptionUpdateDetails() {
    // console.log("FUNCTION ENABLED");
  $("#addedit_customer_payment_form").validate({ 
    rules: {
      subscriptionAmount: {required: true},
      subscriptionPoints: {required: true},
      pCfPaymentId: { 
        required: true ,
        remote: {
          url: urljs+'admin/customers/check_duplicate_payment_cforderId',
          type: 'post',
          data: {
            customerId: $("[name=reference_id]").val()
          }
        }
      },
      reference_id: { required: true },
      pResponseStatus: { required: true },
      pOrderId: {
        required: true,
        remote: {
          url: urljs+'admin/customers/check_duplicate_payment_orderId',
          type: 'post',
          data: {
            customerId: $("[name=reference_id]").val()
          }
        }
      },
      pOrderAmount: {required: true},
      paymentGroup: {required: true},
      paymentAmount: {required: true},
      paymentTime: {required: true}
    },
    messages: {
      subscriptionAmount: { required: "Subscription Amount is required" },
      subscriptionPoints: { required: "Subscription Points is required" },
      pCfPaymentId: { required: "Payment ID is required", remote: "Duplicate CF Payment ID is not allowed" },
      reference_id: {required: 'Reference ID is required'},
      pResponseStatus: {required: 'Please mention the response status'},
      pOrderId: { required: "Payment Order ID is required", remote: "Duplicate Payment Order ID is not allowed" },
      pOrderAmount: { required: "Payment Ordered Amount" },
      paymentGroup: { required: "Payment Group UPI/CARD is required" },
      paymentAmount: { required: "Please enter the paid amount" },
      paymentTime: { required: "Paid At Time is required" },
    },
    errorClass: "help-block error",
    highlight: function (e) {
      $(e).closest(".form-group.row").addClass("has-error");
    },
    unhighlight: function (e) {
      $(e).closest(".form-group.row").removeClass("has-error");
    },
    submitHandler: function (e) {
      event.preventDefault();
      var formdata = new FormData($("#addedit_customer_payment_form")[0]);
      $('#addedit_customer_payment_form').find(':input').each(function() {
        var input = $(this);
        formdata[input.attr('name')] = input.val();
    });
      $["ajax"]({
        url: urljs + "admin/customers/save_subscription_details",
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
          if (data.result == "success") {
            swal_alert("Success", data.msg, "success", "admin/customers");
          } else {
            show_toast("warning", data.msg);
          }
        },
        error: function () {
          closeajax();
        },
      });
    },
  });
}

if ($("#addedit_customer_form").length > 0) {
  saveCustomer();
}
function saveCustomer() {
  $("#addedit_customer_form").validate({
    rules: {
      firstName: { required: true },
      lastName: { required: true },
      email: { required: true },
      phone: {
        required: true,
        number: true,
        minlength: 10,
        maxlength: 10,
      },
      password: {
        required: true,
        minlength: 6,
        maxlength: 15,
      },
      cpassword: {
        required: true,
        equalTo: "[name=password]",
      },
      address: { required: true },
      city: { required: true },
      state: { required: true },
      country: { required: true },
      postalCode: {
        required: true,
        number: true,
        minlength: 6,
        maxlength: 6,
      },
    },
    messages: {
      firstName: { required: "Please provide first name" },
      lastName: { required: "Please provide last name" },
      email: { required: "Please provide email address" },
      phone: {
        required: "Please provide phone number",
        number: "Please enter valid phone number",
      },
      password: { required: "Please enter the password" },
      cpassword: {
        required: "Please confirm the password",
        equalTo: "Please enter the same password again",
      },
      address: { required: "Please provide the area/lane name" },
      city: { required: "Please provide city name" },
      state: { required: "Product provide the state name" },
      country: { required: "Product provide the country name" },
      postalCode: {
        required: "Product provide the postal code",
        number: "Please enter valid postal code",
      },
    },
    errorClass: "help-block error",
    highlight: function (e) {
      $(e).closest(".form-group.row").addClass("has-error");
    },
    unhighlight: function (e) {
      $(e).closest(".form-group.row").removeClass("has-error");
    },
    submitHandler: function (e) {
      event.preventDefault();
      var formdata = new FormData($("#addedit_customer_form")[0]);
      $["ajax"]({
        url: urljs + "admin/customers/save_customer",
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
          if (data.result == "success") {
            swal_alert("Success", data.msg, "success", "admin/customers");
          } else {
            show_toast("warning", data.msg);
          }
        },
        error: function () {
          closeajax();
        },
      });
    },
  });
}

function deleteCustomer() {
  $(".delData")
    .unbind()
    .click(function () {
      var mappingId = $(this).attr("data-id");
      swal(
        {
          title: "Are you sure?",
          text: "You will not be able to recover this customer",
          type: "warning",
          showCancelButton: true,
          cancelButtonClass: "btn-secondary waves-effect",
          confirmButtonClass: "btn-success",
          confirmButtonText: "Yes, delete it!",
          closeOnConfirm: true,
        },
        function (isConfirm) {
          if (isConfirm) {
            ajaxloading("Please wait...");
            $.post(
              urljs + "admin/customers/deleteCustomer",
              { id: mappingId },
              function (data) {
                closeajax();
                if (data.result == "success") {
                  show_toast("success", data.msg);
                } else {
                  show_toast("warning", data.msg);
                }
                getCustomers();
              },
              "json"
            );
          }
        }
      );
    });
}

function export_data() {
  $(".exportData")
    .unbind()
    .click(function () {
      ajaxloading("Exporting data...Please wait...");
      $.post(
        urljs + "admin/customers/export_data",
        "",
        function (data) {
          closeajax();
          if (data.result > 0) {
            swal(
              {
                title: "Yup!!!",
                text: "Customers data is ready for download!",
                type: "success",
                showCancelButton: true,
                cancelButtonClass: "btn-secondary waves-effect",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Download",
                cancelButtonText: "Close",
                closeOnConfirm: true,
                closeOnCancel: true,
              },
              function (isConfirm) {
                if (isConfirm) {
                  window.location.href = urljs + data.filepath;
                }
              }
            );
          } else {
            swal("Failed!", "Please try later!!!", "warning");
          }
        },
        "json"
      );
    });
}

function uploadMdl() {
  var module = "customers";
  $.post(
    urljs + "admin/excelimport/importModal",
    { module: module },
    function (data) {
      if (data.status == "success") {
        var dialog = bootbox.dialog({
          title: "Upload your customers data file here",
          message: data.msg,
          closeButton: true,
        });
        init_excel_dropify();
        import_users();
      } else {
        swal("Warning", "Error occured!", "warning");
      }
    },
    "json"
  );
}

function import_users() {
  $("#import_cs_form").validate({
    errorClass: "error",
    validClass: "valid",
    rules: {
      file: { required: true },
    },
    messages: {
      file: { required: "Please select the file!" },
    },
    submitHandler: function (e) {
      var form_btn = $("#import_cs_form").find('button[type="submit"]');
      var form_btn_old_msg = form_btn.html();

      event.preventDefault();
      var formdata = new FormData($("#import_cs_form")[0]);
      $["ajax"]({
        url: urljs + "admin/excelimport/customers",
        type: "POST",
        dataType: "json",
        data: formdata,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
          form_btn.html(form_btn.prop("disabled", true).data("loading-text"));
          excel_data_timer();
        },
        success: function (data) {
          form_btn.prop("disabled", false).html(form_btn_old_msg);
          if (data.status == "success") {
            bootbox.hideAll();
            getCustomers();
            swal("Success", data.msg, "success");
          } else {
            $(".excel_progress").html("");
            delete_timer();
            swal("Error", data.msg, "warning");
          }
        },
        error: function () {},
      });
    },
  });
}
