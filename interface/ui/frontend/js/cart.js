if ($('.product-action').length > 0) {
  addcart();
  // buynow();
}

function addcart() {
  $(".add-cart").unbind().on("click", function (e) {
    e.preventDefault();
    var form_btn = $(this);
    var form_btn_old_msg = form_btn.html();
    form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
    var productId = $(this).attr('data-id');
    // var metricsId = $(this).closest('.product-action').prev('select[name="metricsId"]').val();
    // // console.log($(this).closest('.product-action').prev().closest(''));
    var metricsId = $(this).attr('data-metricsid');
    console.log("The metrics ID is "+metricsId);
    var curMetricsId = $(this).attr('data-metricsid');
    console.log("Cur Metrics ID is "+curMetricsId);
    var nearestMetricsData = $(this).closest('.product-action').prev().find('.select2_unit').val();
    var homePageMetricsData = $(this).closest(".product-action").find(".metrics").val();
    if(!metricsId && curMetricsId){ 
      metricsId = curMetricsId;
    }else if(nearestMetricsData){
      metricsId = nearestMetricsData;
    }
    var quantity = $(this).closest('div').find('[name=quantity]').val();
    console.log({productId, quantity, metricsId});
    $.post(urljs + "cart/savecart", { 'productId': productId, 'quantity': quantity, 'metricsId': metricsId }, function (data) {
      form_btn.prop('disabled', false).html(form_btn_old_msg);
      showcartquantity();
      if (data.status == 'success') {
        $("#incre-decre").removeClass("d-none");
        show_toast('success', data.message);
      } else {
        show_toast('warning', data.message);
        window.location.href = urljs + 'account';
      }
    }, "json");
  });
}

// function buynow() {
//   $(".buy-now").unbind().on("click", function (e) {
//     e.preventDefault();
//     var form_btn = $(this);
//     var form_btn_old_msg = form_btn.html();
//     form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
//     var productId = form_btn.attr('data-id');
//     var quantity = $('[name=quantity]').val();
//     $.post(urljs + "cart/savecart", { 'productId': productId, 'quantity': quantity }, function (data) {
//       form_btn.prop('disabled', false).html(form_btn_old_msg);
//       showcartquantity();
//       if (data.status == 'success') {
//         window.location.href = urljs + 'checkout';
//       } else {
//         show_toast('warning', data.messages);
//       }
//     }, "json");
//   });
// }


if ($('#cartTbl').length > 0) {
  get_cart_data();
}

function get_cart_data() {
  $.post(urljs + "cart/get_data", '', function (data) {
    // console.log(data);
    console.log("Function called");
    if (data.str != '') {
      $("#cartTbl").html(data.str)
      qtyInputs();
      //order_total();
      book_option();
      update_cart();
      remove_cart();
      clear_cart();
    }
  }, "json");
}

function book_option() {
  $(".cartBookOpt").unbind().on("click", function (e) {
    //e.preventDefault();
    var form_btn = $(this);
    var dataType = form_btn.attr('data-type');
    var form_btn_old_msg = form_btn.html();
    if (dataType > 0) {
      form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
      $.post(urljs + "cart/save_booking", '', function (data) {
        form_btn.prop('disabled', false).html(form_btn_old_msg);
        get_cart_data();
        if (data.result == 'success') {
          swal('Success', data.msg, 'success');
        } else {
          swal('Warning', data.msg, 'warning');
        }
      }, "json");
    } else {
      $('.head-ulog-link a.login-link').trigger("click");
    }
  });
}

function qtyInputs() {
  $(".vertical-quantity").TouchSpin({
    verticalbuttons: !0,
    verticalup: "",
    verticaldown: "",
    verticalupclass: "icon-up-dir",
    verticaldownclass: "icon-down-dir",
    buttondown_class: "btn btn-outline",
    buttonup_class: "btn btn-outline",
    initval: 1,
    min: 1
  });
}

function order_total_not_used() {
  var gt = 0;
  var count = 0;
  $(".prdTotal").each(function (x, y) {
    gt += parseFloat($(y).html());
    count++;
  });
  ordersubTot = Math.floor(gt * 100) / 100;
  pri = ordersubTot == "" ? 0 : parseFloat(ordersubTot);
  tax = (pri) * (parseFloat(18) / 100);

  orderTotal = tax + ordersubTot;

  $(".ordersubTotal").html('<i class="fa fa-inr"></i> ' + ordersubTot);
  $(".orderTax").html('<i class="fa fa-inr"></i> ' + tax);
  $(".orderTotal").html('<i class="fa fa-inr"></i> ' + orderTotal);
  $('[name=orderTax]').val(tax);
  $('[name=subTotal]').val(ordersubTot);
  $('[name=orderTotal]').val(orderTotal);
}

// function update_cart() {
//   $(".input-group-btn-vertical").unbind().on("click", function (e) {
//     var form_btn = $(this);
//     var form_btn_old_msg = form_btn.html();
//     form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
//     var formdata = new FormData($('#cart_update_form')[0]);
//     $["ajax"]({
//       url: urljs + "cart/update",
//       type: "POST",
//       dataType: "json",
//       data: formdata,
//       contentType: false,
//       cache: false,
//       processData: false,
//       beforeSend: function () {
//         //ajaxloading("Saving... Please Wait...");
//       },
//       success: function (data) {
//         get_cart_data();
//         form_btn.prop('disabled', false).html(form_btn_old_msg);
//         //closeajax();
//         if (data.status == 'success') {
//           show_toast('success', data.message)
//         }
//         else {
//           show_toast('warning', data.message)
//         } 
//       },
//       error: function () {
//         closeajax();
//       }
//     })
//   });
// }
function update_cart() {
  // $(".bootstrap-touchspin-up").on("click", function (e) {
  $(".increment").on("click", function (e) {
    e.preventDefault();
    // var inputGroup = $(this).closest(".input-group");
    // var cartId = inputGroup.find(".prdQty").data("cartid");
    // var productId = inputGroup.find(".prdQty").data("productid");
    // var value = inputGroup.find(".prdQty").val();
    const elem = $(this);
    const cartId = elem.attr('data-cartId');
    const productId = elem.attr('data-productId');
    const value = parseInt(elem.siblings('.count').attr('data-count'));
    // var countContainer = $(this).siblings('.count');
    let quantity = parseInt(value)+1;
    
    $.post(urljs+'cart/update', {cartId, productId, quantity}, function(data){
      get_cart_data();
      if (data.status == 'success') {
        show_toast('success', data.message)
      } else {
        show_toast('warning', data.message)
      } 
    }, 'json')
  });

  // $(".bootstrap-touchspin-down").on("click", function (e) {
  $(".decrement").on("click", function (e) {
    e.preventDefault();
    // console.log("CLICKED DOWN");
    const elem = $(this);
    const cartId = elem.attr('data-cartId');
    const productId = elem.attr('data-productId');
    const value = parseInt(elem.siblings('.count').attr('data-count'));
    // var inputGroup = $(this).closest(".input-group");
    // var cartId = inputGroup.find(".prdQty").data("cartid");
    // var productId = inputGroup.find(".prdQty").data("productid");
    // var value = elem.closest('.count').attr('data-count');
    let quantity = parseInt(value);
    if(quantity>1){
      quantity -= 1;
      $.post(urljs+'cart/update', {cartId, productId, quantity}, function(data){
        get_cart_data();
        if (data.status == 'success') {
          // elem.closest('.count').html(quantity-1)
          show_toast('success', data.message)
        } else {
          show_toast('warning', data.message)
        } 
      }, 'json')
    }

  });
}


function remove_cart() {
  $(".delCartPrd").unbind().on("click", function (e) {
    var id = $(this).attr('data-id');
    ajaxloading();
    $.post(urljs + "cart/remove", { 'id': id }, function (data) {
      closeajax();
      get_cart_data();
      showcartquantity();
      if (data.status == 'success') {
        show_toast('success', data.message)
      }
      else {
        show_toast('warning', data.message)
      }
    }, "json");
  });
}

function clear_cart() {
  $(".btn-clear-cart").unbind().on("click", function (e) {
    ajaxloading();
    $.post(urljs + "cart/clear", '', function (data) {
      closeajax();
      get_cart_data();
      showcartquantity();
      console.log(data);
      if (data.status == 'success') {
        show_toast('success', data.message)
      } else {
        show_toast('warning', data.message)
      }
    }, "json");
  });
}

if ($('#chktloginBody').length > 0) {
  get_loginbody();
}

function get_loginbody() {
  ajaxloading('');
  $.post(urljs + "checkout/get_loginbody", '', function (data) {
    if (data.str != '') {
      closeajax();
      $("#chktloginBody").html(data.str);
      chekout_auth();
      pwd_showHide();
    }
  }, "json");
}

function chekout_auth() {
  $('#checkout_login').validate({
    errorClass: 'error',
    validClass: 'valid',
    rules: {
      email: { required: true, email: true },
      password: { required: true, minlength: 6 }
    },
    messages: {
      email: { required: "Please enter your email address" },
      password: { required: "Please enter your password", minlength: "Password should contain atleast 6 characters!!" }
    },
    submitHandler: function (e) {
      event.preventDefault();
      var form_btn = $("#checkout_login").find('button[type="submit"]');
      var form_btn_old_msg = form_btn.html();
      form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
      var formdata = new FormData($('#checkout_login')[0]);
      $["ajax"]({
        url: urljs + "account/auth",
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
          document.getElementById("checkout_login").reset();
          //closeajax();
          if (data.result == 'success') {
            window.location.href = urljs + 'checkout';
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

if ($('#chktpaymentBody').length > 0) {
  get_chkt_payoptions();
  chkt_backto();
}

function get_chkt_payoptions() {
  ajaxloading('');
  $.post(urljs + "checkout/get_payoptions", '', function (data) {
    if (data.str != '') {
      closeajax();
      $("#chktpaymentBody").html(data.str);
    }
  }, "json");
}

function chkt_backto() {
  $(".chktbackto").unbind().on("click", function (e) {
    var tblBody = $(this).attr('data-name');
    if (tblBody == 'shipaddr') {
      if ($('.checkout-forms').is('#chktpaymentBody')) {
        $(this).prop('disabled', true);
        $(this).parent().parent().removeClass('active');
        $('.checkout-forms').attr('id', 'shipaddressTbl');
        get_ship_addr();
      }
    }
  });
}