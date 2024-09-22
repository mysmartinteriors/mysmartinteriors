function ajaxloading(texts){
 if(jQuery('body').find('#resultLoading').attr('id') != 'resultLoading'){
 jQuery('body').append('<div id="resultLoading" style="display:none"><div><div class="card-box"><img src="'+urljs+'ui/loaders/throbber_12.gif" style="display: inline-block;height:55px"><div id="ajaxtext"></div></div></div><div class="bg"></div></div>');
 }
  jQuery('#resultLoading').css({
 'width':'100%',
 'height':'100%',
 'position':'fixed',
 'z-index':'10000000',
 'top':'0',
 'left':'0',
 'right':'0',
 'bottom':'0',
 'margin':'auto'
 });

  jQuery('#resultLoading .bg').css({
 'background':'#000000',
 'opacity':'0.7',
 'width':'100%',
 'height':'100%',
 'position':'absolute',
 'top':'0'
 });

  jQuery('#resultLoading>div:first').css({
 'width': '100%',
 'height':'85px',
 'text-align': 'center',
 'position': 'fixed',
 'top':'0',
 'left':'0',
 'right':'0',
 'bottom':'0',
 'margin':'auto',
 'font-size':'16px',
 'z-index':'10',
 'color':'#ffffff'

  });

 jQuery('#resultLoading .bg').height('100%');
 jQuery('#resultLoading').fadeIn(300);
 jQuery('body').css('cursor', 'wait');
  jQuery('#ajaxtext').html(texts);
  jQuery('#ajaxtext').css({'color':'#fff','font-size':'18px','padding-top': '5px'});
 
}

function closeajax() {
 jQuery('#resultLoading .bg').height('100%');
 jQuery('#resultLoading').fadeOut(300);
 jQuery('body').css('cursor', 'default');
}


function show_toast(msgType,msg){
    toastr[msgType](msg)
    toastr.options = {
      "closeButton": true,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }
}

function swal_alert(title,msg,alertType,url){
    swal({
        title: title,
        text: msg,
        type: alertType,
        showCancelButton: false,
        confirmButtonClass: 'btn-'+alertType,
        confirmButtonText: 'OK'
    },function(isConfirm) {
      if (isConfirm) {
        if(url!=''){
            window.location=urljs+url;
        }else{
            window.location.reload();
        }
      } 
    });
}

function ajaxLoader() {
    jQuery("body").append("<div class='ajaxOverlay'><i class='porto-loading-icon'></i></div>")
}

$(document).ready(function(){
  var loginpage=window.location.pathname;
  if (loginpage.indexOf('login') > 0){
      $('.head-ulog-link').remove();
  }
  showcartquantity();
  loginPopup();
  addcart();
  quickview_prd();
});

function showcartquantity(){
  $.post(urljs+"cart/showcartqty",'',function(data){
    $(".cartQuantity").html(data.result);
  },"json");
}

function quickview_prd(){
  $(".btn-quickview").unbind().on("click", function(e) {
      e.preventDefault();
      var metricsId = $(this).closest('.product').find('.select2_unit').val();
      var mappingId = $(this).attr('data-id');
      if(mappingId!=''){
        ajaxloading('Getting your product...');
        $.post(urljs+'products/prdQuickView',{'id':mappingId, metricsId},function(data){
            closeajax();
            if(data.result>0){   
                bootbox.alert({
                    message: data.str,
                    centerVertical: true,
                    className:'prdModalView'
                });
                $('.bootbox.modal .modal-footer').css('display','none');
                addcart();
                buynow();
            }else{
                show_toast('warning','Something went wrong!');
            }
        },"json");
      }else{
        show_toast('warning','Something went wrong!');
      }
  });
}

//toggle password
function pwd_showHide(){
    $(".Togglepwd .fa").on('click', function(event) {
        event.preventDefault();
        var elem=$(this).parent();
        if($(elem).find('input').attr("type") == "password"){
            $(elem).find('input').attr('type', 'text');
            $(this).toggleClass("fa-eye-slash").toggleClass("fa-eye");
        }else{
            $(elem).find('input').attr('type', 'password');
            $(this).toggleClass("fa-eye").toggleClass("fa-eye-slash");
        }
    });
}

if($(".Togglepwd").length>0){
    pwd_showHide();
}