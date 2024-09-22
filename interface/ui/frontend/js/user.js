//user actions
function loginPopup() {
  $(".login-link").click(function (e) {
    e.preventDefault();
    ajaxloading();
    $.post(urljs + 'account/popup_login', '', function (data) {
      closeajax();
      bootbox.alert({
        message: data.str,
        centerVertical: true
      });
      pwd_showHide();
      init_userform_toggle();
      login_auth();
      user_forgot_pwd();
      user_register();
      $('.bootbox').addClass('login-popup');
      $('.bootbox.modal .modal-footer').css('display', 'none');

    }, "json");
  });
}

function init_userform_toggle() {
  var $formLogin = $('#user_login_panel');
  var $formLost = $('#user_pwd_panel');
  var $formRegister = $('#user_reg_panel');

  $formLost.hide();
  $formRegister.hide();

  $('#forgot_pwd_btn').click(function () { modalAnimate($formLost); });
  $('#lost_login_btn').click(function () { modalAnimate($formLogin); });
  $('#user_regstr_btn').click(function () { modalAnimate($formRegister); });
  $('#reg_login_btn').click(function () { modalAnimate($formLogin); });

  function modalAnimate($newForm) {
    $formLogin.hide();
    $formLost.hide();
    $formRegister.hide();
    $newForm.fadeToggle('slow');
  }
}

function onClickHandler(){
  console.log("SignIN with google button clicked");
}

function login_auth() {
  $('#user_login_form').validate({
    errorClass: 'error',
    validClass: 'valid',
    rules: {
      email: { required: true },
      password: { required: true, minlength: 6 }
    },
    messages: {
      email: { required: "Please enter your email address/mobile number" },
      password: { required: "Please enter your password", minlength: "Password should contain atleast 6 characters!!" }
    },
    submitHandler: function (e) {
      var form_btn = $("#user_login_form").find('button[type="submit"]');
      var form_btn_old_msg = form_btn.html();
      var formdata = new FormData($('#user_login_form')[0]);
      $["ajax"]({
        url: urljs + "account/auth",
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
          if (data.result == 'success') {
            window.location.reload();
          }
          else {
            show_toast('warning', data.msg)
          }
        },
        error: function () {
          closeajax();
        }
      })
    }
  });
}

function user_forgot_pwd() {
  $('#forgotpwd_form').validate({
    errorClass: 'error',
    validClass: 'valid',
    rules: {
      email: { required: true, email: true }
    },
    messages: {
      email: { required: "Please enter your email ID" }
    },
    submitHandler: function (e) {
      //event.preventDefault();
      var form_btn = $("#forgotpwd_form").find('button[type="submit"]');
      var form_btn_old_msg = form_btn.html();
      form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
      var formdata = new FormData($('#forgotpwd_form')[0]);
      $["ajax"]({
        url: urljs + "account/forgotpassword",
        type: "POST",
        dataType: "json",
        data: formdata,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
          //ajaxloading("Saving... Please Wait...");
        },
        success: function (data) {
          form_btn.prop('disabled', false).html(form_btn_old_msg);
          document.getElementById("forgotpwd_form").reset();
          //closeajax();
          if (data.status == 'success') {
            swal_alert('success', data.msg, 'success', '')
          }
          else {
            swal_alert('warning', data.msg, 'warning', '')
          }
        },
        error: function () {
          closeajax();
        }
      })
    }
  });
}

function user_register() {
  $('#user_regstr_form').validate({
    errorClass: 'error',
    validClass: 'valid',
    rules: {
      email: {
        required: true,
        email: true,
        remote: {
          url: urljs + "account/checkemail",
          type: "post",
          data: {
            edit: function () {
              return $("[name=email]").attr("data-edit");
            }
          }
        }
      },
      name: { required: true },
      password: { required: true, minlength: 6 },
      phone: {
        required: true, minlength: 10, maxlength: 10,
        remote: {
          url: urljs + "account/checkphone",
          type: "post",
          data: {
            edit: function () {
              return $("[name=phone]").attr("data-edit");
            }
          }
        }
      },  
      referral_code: {
        minlength: 10, maxlength: 10,
        remote: {
          url: urljs + "account/checkreferral",
          type: "post",
          data: {
            edit: function() {
              return $("[name=referral_code]").attr("data-edit");
            }
          }
        }
      } 
    },
    messages: {
      email: { required: "Please enter your email address", email: "Please enter valid email address", remote: "Email already exists!!!" },
      name: { required: "Please enter your full name" },
      password: { required: "Please choose your password", minlength: "Password should contain atleast 6 characters" },
      phone: { required: "Please enter your Mobile Number", minlength: "Mobile Number should contain 10 numbers", maxlength: "Mobile Number should contain 10 numbers", remote: "Phone number already exists!!!" },
      referral_code: {minlength: 'Ref Code should be at least of 10 digits', maxlength: 'Ref Code should be at only of 10 digits', remote: 'Referral Code did not match or is expired'}
    },
    submitHandler: function (e) {
      //event.preventDefault();
      var form_btn = $("#user_regstr_form").find('button[type="submit"]');
      var form_btn_old_msg = form_btn.html();
      form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
      var formdata = new FormData($('#user_regstr_form')[0]);
      $["ajax"]({
        url: urljs + "account/register",
        type: "POST",
        dataType: "json",
        data: formdata,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
          //ajaxloading("Saving... Please Wait...");
        },
        success: function (data) {
          console.log(data.message);
          form_btn.prop('disabled', false).html(form_btn_old_msg);
          document.getElementById("user_regstr_form").reset();
          $(".bootbox").find('.bootbox-close-button').trigger('click');
          if (data.status == 'success') {
            swal_alert("Success", data.message, "success", "")
          } else {
            $(".form_error").text($(data.message).text());
            // alert($(data.message).text());
            // show_toast('warning', (data.message).text())
          }
        },
        error: function () {
          closeajax();
        }
      })
    }
  });
}

if ($('#user_profile_form').length > 0) {
  user_profile();
}

function user_profile() {
  $('#user_profile_form').validate({
    errorClass: 'error',
    validClass: 'valid',
    rules: {
      firstName: { required: true },
      lastName: { required: true },
      email: { required: true, email: true },
      phone: { required: true, number: true, minlength: 10, maxlength: 10 },
      address: { required: true },
      city: { required: true },
      state: { required: true },
      country: { required: true },
      postalCode: { required: true, minlength: 6 }
    },
    messages: {
      firstName: { required: "Please provide your first name" },
      lastName: { required: "Please provide your last name" },
      email: { required: "Please provide your email address", email: "Please enter valid email address" },
      phone: { required: "Please provide your phone number" },
      address: { required: "Please provide your area/lane address" },
      city: { required: "Please provide your city name" },
      state: { required: "Please provide state name" },
      country: { required: "Please provide your country name" },
      postalCode: { required: "Please provide your postal code", minlength: "Please enter valid postal code" }
    },
    submitHandler: function (e) {
      event.preventDefault();
      var form_btn = $("#user_profile_form").find('button[type="submit"]');
      var form_btn_old_msg = form_btn.html();
      form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
      var formdata = new FormData($('#user_profile_form')[0]);
      $["ajax"]({
        url: urljs + "account/saveprofile",
        type: "POST",
        dataType: "json",
        data: formdata,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
          //ajaxloading("Saving... Please Wait...");
        },
        success: function (data) {
          form_btn.prop('disabled', false).html(form_btn_old_msg);
          //closeajax();
          if (data.status == 'success') {
            // swal_alert('Success', data.message, "success")
            swal('Success', data.message, 'success');
            location.reload();
          } else {
            show_toast('warning', 'Errors occured, Please check')
            console.log($("#error"));
            $("#error").html(data.message)
          }
        },
        error: function () {
          closeajax();
        }
      })
    }
  });
}

//account shipping address
if ($('#my_addr_tbl').length > 0) {
  add_ship_addr();
  delete_shipAddr();
  update_pri_shipAddr();
}

function delete_shipAddr() {
  $(".btn-delAddr").unbind().on("click", function (e) {
    var id = $(this).attr('data-id');
    e.preventDefault();
    swal({
      title: "Are you sure?",
      text: "You cannot revert this back!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonClass: 'btn-warning',
      confirmButtonText: 'Yes, delete it!',
      closeOnConfirm: true,
    }, function () {
      $.post(urljs + 'account/del_shipaddr', { 'id': id }, function (data) {
        if (data.status == 'success') {
          swal_alert("success", data.message, "success", "");
        } else {
          swal_alert("warning", data.message, "warning", "");
        }
      }, "json");
    });
  });
}

function update_pri_shipAddr() {
  $(".btn-priAddr").unbind().on("click", function (e) {
    var id = $(this).attr('data-id');
    e.preventDefault();
    $.post(urljs + 'account/pri_shipAddr', { 'id': id }, function (data) {
      window.location.reload();
    }, "json");
  });
}

//checkout shipping
// if ($('#shipaddressTbl').length > 0) {
//   get_ship_addr();
// }

function get_ship_addr() {
  // ajaxloading('');
  // $.post(urljs + "checkout/get_shipaddrData", '', function (data) {
  //   if (data.str != '') {
  //     closeajax();
  //     $("#shipaddressTbl").html(data.str);
  //     select_ship_addr();
  //     add_ship_addr();
  //     save_checkout_addr();
  //   }
  // }, "json");
}

function select_ship_addr() {
  $(".select-shipaddr").unbind().on("click", function (e) {
    var id = $(this).attr('data-id');
    $(".shipping-address-box").removeClass("active");
    $(this).parent().parent().addClass('active');
    $(document).find("#checkout_addrform").find('[name="addressId"]').val(id);
  });
}

function add_ship_addr() {
  $(".btn-shipaddr").unbind().on("click", function (e) {
    var id = $(this).attr('data-id');
    if ($(this).is(".btn-edit")) {
      var modalTitle = 'Edit your shipping address';
      if (id == undefined) {
        swal('Warning', 'Please select the shipping address to edit!', 'warning');
        return;
      }
    } else {
      var modalTitle = 'Add new shipping address';
    }
    e.preventDefault();
    ajaxloading('');
    $.post(urljs + 'account/shipaddress', { 'id': id }, function (data) {
      closeajax();
      bootbox.alert({
        title: modalTitle,
        message: data.str,
        centerVertical: true
      });
      save_ship_address();
      $('.bootbox.modal').addClass('addressmodal');
      $('.bootbox.modal .modal-footer').css('display', 'none');
    }, "json");
  });
}

function save_ship_address() {
  $('#addedit_ship_addr').validate({
    errorClass: 'error',
    validClass: 'valid',
    rules: {
      name: { required: true },
      phone: { required: true, number: true, minlength: 10, maxlength: 10 },
      address: { required: true },
      city: { required: true },
      state: { required: true },
      country: { required: true },
      postalCode: { required: true, minlength: 6 }
    },
    messages: {
      name: { required: "Please provide name" },
      phone: { required: "Please provide phone number" },
      address: { required: "Please provide area/lane address" },
      city: { required: "Please provide city name" },
      state: { required: "Please provide state name" },
      country: { required: "Please provide country name" },
      postalCode: { required: "Please provide postal code", minlength: "Please enter valid postal code" }
    },
    submitHandler: function (e) {
      event.preventDefault();
      var form_btn = $("#addedit_ship_addr").find('button[type="submit"]');
      var form_btn_old_msg = form_btn.html();
      form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
      var formdata = new FormData($('#addedit_ship_addr')[0]);
      $["ajax"]({
        // url: urljs + "account/saveshipaddr",
        url: urljs + "account/saveshipaddr",
        type: "POST",
        dataType: "json",
        data: formdata,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
          //ajaxloading("Saving... Please Wait...");
        },
        success: function (data) {
          form_btn.prop('disabled', false).html(form_btn_old_msg);
          bootbox.hideAll();
          get_ship_addr();
          if (data.status == 'success') {
            swal('success', data.messae, 'success');
            location.reload();
          }
          else {
            swal('warning', data.message, 'warning');
          }
        },
        error: function () {
          closeajax();
        }
      })
    }
  });
}




function save_checkout(formdata) {
  // var order_total = 
  $.post(urljs + "checkout/save_checkout", { "formdata": formdata }, function (data) {
    console.log(data);
  }, "json");
}

//change password
if ($('#user_cpass_form').length > 0) {
  update_user_password();
}

function update_user_password() {
  $('#user_cpass_form').validate({
    errorClass: 'error',
    validClass: 'valid',
    rules: {
      old_pass: { required: true },
      new_pass: { required: true, minlength: 6, maxlength: 12 },
      cnew_pass: { required: true, minlength: 6, maxlength: 12, equalTo: "[name='new_pass']" }
    },
    messages: {
      old_pass: { required: "You need to enter your old password" },
      new_pass: { required: "Please enter your new password" },
      cnew_pass: { required: "Please type new password again!", equalTo: "Please enter the same password again!" }
    },
    submitHandler: function (e) {
      event.preventDefault();
      var form_btn = $("#user_cpass_form").find('button[type="submit"]');
      var form_btn_old_msg = form_btn.html();
      var formdata = new FormData($('#user_cpass_form')[0]);
      $["ajax"]({
        url: urljs + "account/update_password",
        type: "POST",
        dataType: "json",
        data: formdata,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
          //ajaxloading("Saving... Please Wait...");
          form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
        },
        success: function (data) {
          form_btn.prop('disabled', false).html(form_btn_old_msg);
          //closeajax();
          if (data.status == 'success') {
            show_toast('success', data.message)
          } else {
            show_toast('warning', data.message)
          }
        },
        error: function () {
          closeajax();
        }
      })
    }
  });
}

//contact support
if ($('#contact_support_form').length > 0) {
  save_support();
}

function save_support() {
  $('#contact_support_form').validate({
    errorClass: 'error',
    validClass: 'valid',
    rules: {
      name: { required: true },
      email: { required: true, email: true },
      phone: { required: true, number: true, minlength: 10, maxlength: 10 },
      subject: { required: true },
      message: { required: true }
    },
    messages: {
      name: { required: "Please provide your first name" },
      email: { required: "Please provide your email address", email: "Please enter valid email address" },
      phone: { required: "Please provide your phone number" },
      subject: { required: "Please provide the support subject" },
      message: { required: "Please describe that how can we help you?" }
    },
    submitHandler: function (e) {
      event.preventDefault();
      var form_btn = $("#contact_support_form").find('button[type="submit"]');
      var form_btn_old_msg = form_btn.html();
      form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
      var formdata = new FormData($('#contact_support_form')[0]);
      $["ajax"]({
        url: urljs + "contact/save_support",
        type: "POST",
        dataType: "json",
        data: formdata,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
          //ajaxloading("Saving... Please Wait...");
        },
        success: function (data) {
          form_btn.prop('disabled', false).html(form_btn_old_msg);
          //closeajax();
          if (data.result == 'success') {
            $("#contact_support_form").find("input,select,textarea").val("");
            swal_alert('success', data.msg, 'success', '');
            // window.location.reload();
          }
          else {
            swal_alert('warning', data.msg, 'warning', '')
          }
        },
        error: function () {
          closeajax();
        }
      })
    }
  });
}

//dashboard tickets
if ($('#user_ticketTbl').length > 0) {
  get_user_tickets();
}

function get_user_tickets() {
  ajaxloading('Loading your tickets...');
  $.post(urljs + "account/get_tickets", '', function (data) {
    $("#user_ticketTbl").html(data.str);
    closeajax();
    view_ticket();
  }, "json");
}

function view_ticket() {
  $('.btn-tktView').unbind().on('click', function (e) {
    //e.preventDefault();
    var id = $(this).attr('data-id');
    var code = $(this).attr('data-code');
    var ticketModal = bootbox.dialog({
      title: 'View ticket details of ' + code,
      message: '<p class="loaderBlock"><i class="fa fa-spinner fa-spin"></i> Loading, Please wait...</p>',
      closeButton: true,
      size: 'extra-large',
      animate: true,
      className: "largeWidth ticketViewMdl",
    });
    //ajaxloading('Please wait...');
    $.post(urljs + "account/viewticket", { 'id': id, 'code': code }, function (data) {
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
    errorClass: 'error',
    validClass: 'valid',
    rules: {
      subject: { required: true },
      message: { required: true }
    }, 
    messages: {
      subject: { required: "Please type reply subject" },
      message: { required: "Please type reply message" }
    },
    submitHandler: function () {
      var editorInput = CKEDITOR.instances.message.getData();
      var editorBox = $('#ticket_reply_form').find('#cke_message');
      if (editorInput == '') {
        editorBox.after('<label class="error">Please type your message to update...</label>')
      } else {
        var formdata = new FormData($('#ticket_reply_form')[0]);
        formdata.append('message', editorInput);
        $["ajax"]({
          url: urljs + "account/update_ticket",
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
            if (data.result > 0) {
              show_toast('success', data.msg);
              bootbox.hideAll();
            }
            else {
              swal('Failed!', data.msg, 'warning');
            }
            closeajax()
          },
          error: function () { }
        })
      }
    }
  });
}



if ($('.btn-getBookData').length > 0) {
  view_booking();
}


function view_booking() {
  $(".btn-getBookData").unbind().on("click", function (e) {
    var id = $(this).attr('data-id');
    var status = $(this).attr('data-status');
    e.preventDefault();
    ajaxloading('Fetching details...');
    $.post(urljs + 'account/get_booking', { 'id': id, 'status': status }, function (data) {
      closeajax();
      if (data.result == 'success') {
        bootbox.dialog({
          title: 'View booking details',
          message: data.msg,
          centerVertical: false
        });
        $('.bootbox.modal').addClass('dataViewModal');
        $('.bootbox.modal .modal-footer').css('display', 'none');
      } else {
        swal('warning', data.msg);
      }
    }, "json");
  });
}

$(".btn-generate_pdf").unbind().on("click", function (e) {
  var id = $(this).attr('data-id');
  e.preventDefault();
  ajaxloading('Fetching details...');
  $.post(urljs + 'account/pdf', { 'id': id }, function (data) {
    closeajax();
    // if (data.result == 'success') {
    //   bootbox.dialog({
    //     title: 'View booking details',
    //     message: data.msg,
    //     centerVertical: false
    //   });
    //   $('.bootbox.modal').addClass('dataViewModal');
    //   $('.bootbox.modal .modal-footer').css('display', 'none');
    // } else {
    //   swal('warning', data.msg);
    // }
  }, "json");
});

$(".updateDelivery").on("click", function () {
  var delId = $('#delivery').find(":selected").val();
  if (delId != '') {
    $("#error").hide();
    $.post(urljs + 'checkout/placeorder', { 'delId': delId }, function (data) {
      $("#placeorder").html(data.str);
      $(".cart-summary").removeClass("d-none");
      $("#checkout_addrform").find("button").removeClass("d-none");
    }, "json")
  } else {
    $("#error").text("Please select city locality to deliver the order");
  }
  // var orderTotal = $(".ordersubTotal").text();
  // var orderTax = $(".orderTax").text();
  // if(delId != ''){

  //   $(".delivery_charge").html('<i class="fa fa-inr"></i>' + ' ' + delId);
  //   $totalcost = parseFloat(orderTotal) + parseFloat(orderTax) + parseFloat(delId);
  //   $(".orderTotal").html('<i class="fa fa-inr"></i>' + ' ' + $totalcost);
  //   $extraCost = parseFloat("375") - parseFloat($totalcost);
  //   $(".freeDelivery").html('<i class="fa fa-inr"></i>' + ' ' + $extraCost);
  // }else{
  //   alert("Please select city to deliver order");
  // }
})

$('.share_invoice').unbind().on('click', function (e) {
  //e.preventDefault();
  // console.log("HERE");
  // var planId = $(this).attr('data-id');
  var customerId = $(this).attr('customer-id');
  var dataModal = bootbox.dialog({
    title: "",
    message: '<i class="fa fa-spinner fa-spin"></i> Loading, Please wait...',
    closeButton: true,
    size: 'small',
    animate: true,
    centerVertical: true,
    className: "userModalView smallWidth",
  });
  $.post(urljs + "account/reference_view", { 'customerId': customerId }, function (data) {
    if (data.status == 'success') {
      dataModal.find('.bootbox-body').html(data.message);
      $('[data-toggle="tooltip"]').tooltip();
      share_invoice_link();
    } else {
      swal("Error!", data.msg, "warning");
    }
  }, "json");
});

function share_invoice_link() {


  $('#share_invoice').validate({

    errorClass: 'error',
    validClass: 'valid',
    rules: {

    },
    messages: {

    },
    errorPlacement: function (error, element) {
      if (element.parent().hasClass('input-group')) {
        error.insertAfter(element.parent());
      } else {
        error.insertAfter(element);
      }
    },


    submitHandler: function () {

      // $('#sign_in').find('.div_res').html('');
      if ($('.share_invoice').is(':checked')) {
        // if ($('.expiry_hours').is(':checked')) {
        var formdata = new FormData($('#share_invoice')[0]);
        // console.log(formdata);
        $["ajax"]({
          url: urljs + 'account/check_input',
          type: "POST",
          dataType: "json",
          data: formdata,
          contentType: false,
          cache: false,
          processData: false,
          beforeSend: function () {
            // button_load('#share_invoice', 'Processing...', '');
          },
          success: function (data) {
            // end_button_load('#share_invoice', '');
            if (data.status == 'success') {
              if (data.val == '1') {
                var share_link_data = data.data;

                share_link(share_link_data);
              } else {
                $('.' + data.method).show();
              }
            }
          },
          error: function () {

          }

        }, 'json');
        // } else {

        //   failureResult('#share_invoice', 'select expiry hours', false);
        // }
      } else {
        // failureResult('#share_invoice', 'select Share link method', false);
      }
    }
  });

}

function share_link(share_link_data) {
  $.post('share_link', { data: share_link_data }, function (data) {
    if (data.status == 'success') {
      bootbox.hideAll();
      $('.modal').modal('hide');
      show_toast('success', data.message);
      getdatas();
    } else {
      // failureResult('#share_invoice', data.message, false);
    }
  }, 'json');
}


// payment and form handling
// function save_checkout_addr() {
//   $('#checkout_addrform').validate({
//     errorClass: 'error', 
//     validClass: 'valid',
//     submitHandler: function (e) {
//       const selectedAddress = $(document).find(".shipping-address-box.active").attr('data-id');
//       const selectedAddressLatLong = $(document).find("#latLongVal").val();
//       if (selectedAddress && selectedAddressLatLong.length > 0) {
//         event.preventDefault();
//         const deliveryDate = $("#deliveryDate").val();
//         if(!deliveryDate){
//           swal('Please select the delivery date', '', 'warning');
//           return;
//         }
//         var form_btn = $("#checkout_addrform").find('button[type="submit"]');
//         var form_btn_old_msg = form_btn.html();
//         form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
//         var formdata = new FormData($('#checkout_addrform')[0]); 
//         formdata.append('deliveryDate', deliveryDate)
//         formdata.append("selectedAddress", selectedAddress);
//         formdata.append("selectedAddressLatLong", selectedAddressLatLong);
//         $["ajax"]({
//           url: urljs + "checkout/save_checkout",
//           type: "POST",
//           dataType: "json",
//           data: formdata, 
//           contentType: false,
//           cache: false,
//           processData: false,
//           beforeSend: function () {
//             const paymentMethod = formdata.get('payment_method');
//             if(paymentMethod==='pay_online'){
//               const actualAmountToPay = formdata.get('actualAmountToPay');
//               console.log(actualAmountToPay);
//               return;
//             }
//             //sending the request to generate the session id with the given amount
//           },
//           success: function (data) {
//             form_btn.prop('disabled', false).html(form_btn_old_msg);
//             if (data.status == 'success') {
//                 if (data.paymentMethod == 'pay_online' && data.dataList && data.dataList.actualAmountToPay>0) {
//                     if(data.paymentDetails.status=='success' && data.paymentDetails.sessionId.length && data.paymentDetails.orderId.length){
//                       const cashfree = Cashfree({
//                           mode: "sandbox",
//                           // mode: "production",
//                       });
//                       let checkoutOptions = {
//                           paymentSessionId: `${data.paymentDetails.sessionId}`,
//                           redirectTarget: document.getElementById("checkoutView"),
//                           appearance: {
//                               width: "370px",
//                               height: "700px",
//                           },
//                       };
//                       cashfree.checkout(checkoutOptions).then((result)=>{
                        
//                       })
//                     }else{
//                       swal_alert('Failed', data.message, 'error', 'account/myorders');
//                     }
//                 }else{
//                   $.post(urljs + "cart/clear", '', function (response) {
//                   }, "json")
//                   swal_alert('Success', data.message, 'success', 'account/myorders');
//                 }
//             } else {
//               swal('warning', data.message, 'warning');
//             }
//           },
//           error: function () {
//             closeajax();
//           }
//         })
//       } else {
//         swal('Warning', 'Please select shipping address from list and the map as well to place your order', 'warning');
//         return false;
//       }
//     }
//   });
// }


function save_checkout_addr() {
  $('#checkout_addrform').validate({
    errorClass: 'error',
    validClass: 'valid',
    submitHandler: function (e) {
      const selectedAddress = $(document).find(".selectedAddressId").val();
      const selectedAddressLatLong = $(document).find(".latlong").val();
      if (selectedAddress && selectedAddressLatLong.length > 0) {
        event.preventDefault();
        const deliveryDate = $("#deliveryDate").val();
        if(!deliveryDate){
          swal('Please select the delivery date', '', 'warning');
          return;
        }
        var form_btn = $("#checkout_addrform").find('button[type="submit"]');
        var form_btn_old_msg = form_btn.html();
        form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
        var formdata = new FormData($('#checkout_addrform')[0]); 
        formdata.append('deliveryDate', deliveryDate);
        formdata.append("selectedAddress", selectedAddress);
        formdata.append("selectedAddressLatLong", selectedAddressLatLong);

        const paymentMethod = formdata.get('payment_method');
        if(paymentMethod === 'pay_online'){
          const actualAmountToPay = formdata.get('actualAmountToPay');
          // Handle payment here
          initiatePayment(formdata, form_btn, form_btn_old_msg);
        } else {
          // Send AJAX request directly if payment method is not online
          sendAjaxRequest(formdata, form_btn, form_btn_old_msg);
        }
      } else {
        swal('Warning', 'Invalid Address, Please select the valid address', 'warning');
        return false;
      }
    }
  });
}

function initiatePayment(formdata, form_btn, form_btn_old_msg) {
  // Assuming you have a function to handle payment and it returns a promise
  processPayment(formdata).then(paymentDetails => {
    if(paymentDetails.status === 'success' && paymentDetails.sessionId.length && paymentDetails.orderId.length) {
      try {
        $('html, body').animate({
            scrollTop: $("#checkoutView").offset().top
        }, 2000);
        const cashfree = Cashfree({
          // mode: "sandbox",
          mode: "production", 
        });
        let checkoutOptions = {
          paymentSessionId: `${paymentDetails.sessionId}`,
          redirectTarget: document.getElementById("checkoutView"),
          appearance: {
            width: "100%",
            height: "700px",
          },
        };
        cashfree.checkout(checkoutOptions).then((result) => {
          if(result.error){
            // This will be true whenever user clicks on close icon inside the modal or any error happens during the payment
            console.log("User has closed the popup or there is some payment error, Check for Payment Status");
            console.log(result.error);
          }
          if(result.paymentDetails){
            $.post(urljs+'checkout/check_payment_status', {orderId: paymentDetails.orderId}, function(paymentStatus){
              if(paymentStatus.status=='success'){
                formdata.append('paymentDetails', JSON.stringify(paymentStatus));
                sendAjaxRequest(formdata, form_btn, form_btn_old_msg);
              }else{
                swal_alert('Network Error during payment, Please Try again Later', 'payment failed', 'error', 'checkout')
              }
            }, 'json')
          }else{
            swal_alert('Network Error during payment, Please Try again Later', 'payment failed', 'error', 'checkout')
          }
          // After successful payment, send the AJAX request
        });
      } catch (error) {
        console.log(error);
        swal_alert('Network Error, Please Try again Later', 'payment failed', 'error', 'checkout')
      }
    } else {
      swal_alert('Failed', 'Payment failed', 'error', 'checkout');
      form_btn.prop('disabled', false).html(form_btn_old_msg);
    }
  }).catch(error => {
    console.log(error);
    swal_alert('Failed', 'Payment processing error', 'error', 'checkout');
    // form_btn.prop('disabled', false).html(form_btn_old_msg);
  });
}

function sendAjaxRequest(formdata, form_btn, form_btn_old_msg) {
  $.ajax({
    url: urljs + "checkout/save_checkout",
    type: "POST",
    dataType: "json",
    data: formdata, 
    contentType: false,
    cache: false,
    processData: false,
    success: function (data) {
      form_btn.prop('disabled', false).html(form_btn_old_msg);
      if (data.status == 'success') {
          $.post(urljs + "cart/clear", '', function (response) {
          }, "json");
          swal_alert('Success', data.message, 'success', 'account/myorders');
      }else{
        swal_alert('Error', data.message, 'error', 'checkout')
      }
    },
    error: function () {
      closeajax();
    }
  });
}



function processPayment(formdata) {
  return new Promise((resolve, reject) => {
    // Simulate an AJAX request to initiate the payment
    $.ajax({
      url: urljs + "checkout/initiate", // Replace with your payment initiation endpoint
      type: "POST",
      dataType: "json",
      data: formdata, 
      contentType: false,
      cache: false,
      processData: false,
      success: function (data) {
        if (data.status == 'success') {
          resolve(data.paymentDetails);
        } else {
          reject(data.message);
        }
      },
      error: function (error) {
        reject('Payment initiation failed');
      }
    });
  });
}

