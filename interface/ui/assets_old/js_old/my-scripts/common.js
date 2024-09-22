var csrf_test_name = $["cookie"]("csrf_cookie_name");

function ajaxloading(texts){
 if(jQuery('body').find('#resultLoading').attr('id') != 'resultLoading'){
 jQuery('body').append('<div id="resultLoading" style="display:none"><div><div class="card-box"><img src="'+urljs+'ui/loaders/throbber_12.gif" height="50"><div id="ajaxtext"></div></div></div><div class="bg"></div></div>');
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
 'width': '250px',
 'height':'75px',
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
  jQuery('#ajaxtext').css('color','#000');
 
}

function closeajax() {
 jQuery('#resultLoading .bg').height('100%');
 jQuery('#resultLoading').fadeOut(300);
 jQuery('body').css('cursor', 'default');
}

function libraryLoader(texts){
    jQuery('#ajax_Library').append('<div class="card"><div class="card-box"><img src="'+urljs+'ui/loaders/throbber_12.gif" height="50"><div id="Ajaxlib_text"></div></div></div>');
    jQuery('#ajax_Library').css({
    'width': '400px',
    'height': '100px',
    'position': 'absolute',
    'left': '50%',
    'top': '50%',
    'margin-top': '-50px',
    'margin-left': '-200px',
    'text-align':'center'
    });
    jQuery('#Ajaxlib_text').css({
        'padding-top': '10px'
    })
    jQuery('#Ajaxlib_text').html(texts);
}

function closeLibLoader(){
    jQuery('#ajax_Library').fadeOut(300);
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

deparam = (function(variable_0, variable_1, variable_2, variable_3, variable_4) {
    return function(variable_5) {
        variable_2 = {};
        variable_5 = variable_5["substring"](variable_5["indexOf"]("#") + 1)["replace"](variable_1, " ")["split"]("&");
        for (variable_4 = variable_5["length"]; variable_4 > 0;) {
            variable_3 = variable_5[--variable_4]["split"]("=");
            variable_2[variable_0(variable_3[0])] = variable_0(variable_3[1])
        };
        return variable_2
    }
})(decodeURIComponent, /\+/g);

function assignValueToFilter() {
    var variable_6 = window["location"]["hash"]["substr"](1);
    var variable_7 = deparam(variable_6);
    var variable_8 = JSON["stringify"](variable_7);
    var variable_9 = $["parseJSON"](variable_8);
    console.log(variable_9);
    $["each"](variable_9, function(variable_10, variable_11) {
        if (variable_10 != "") {
            var variable_12 = $("[data-type=" + variable_10 + "]")["attr"]("id");
            $("#" + variable_12)["val"](variable_11)
        }
    })
}

function emptyValueToFilter() {
    var variable_6 = window["location"]["hash"]["substr"](1);
    var variable_7 = deparam(variable_6);
    var variable_8 = JSON["stringify"](variable_7);
    var variable_9 = $["parseJSON"](variable_8);
    $["each"](variable_9, function(variable_10, variable_11) {
        var variable_12 = $("[data-type=" + variable_10 + "]")["attr"]("id");
        $("#" + variable_12)["val"]("")
    })
}

function GetURLParameter(variable_01) {
    var variable_02 = window["location"]["hash"]["substring"](1);
    var variable_03 = variable_02["split"]("&");
    for (var variable_4 = 0; variable_4 < variable_03["length"]; variable_4++) {
        var variable_04 = variable_03[variable_4]["split"]("=");
        if (variable_04[0] == variable_01) {
            return variable_04[1]
        }
    }
}

function ajax_filter(variable_1e, variable_6, variable_1f, hashlink=true) {
    var variable_26 = [];
    var variable_27 = [];
    jQuery(".refine_filter option:selected,input.refine_filter")["each"](function() {
        variable_26["push"]({
            "\x74\x79\x70\x65": $(this)["attr"]("data-type"),
            "\x76\x61\x6C\x75\x65": $(this)["val"]()
        });
        variable_27["push"]({
            "\x6E\x61\x6D\x65": $(this)["attr"]("data-type"),
            "\x76\x61\x6C\x75\x65": $(this)["val"]()
        })
    });
    var variable_28 = $["param"](variable_27, true);
    if(hashlink==true){
    window["location"]["hash"] = variable_28;
    }
    $["ajax"]({
        url: urljs + variable_1e,
        type: "POST",
        dataType: "json",
        data: {
            "\x70\x61\x67\x65": variable_6,
            "\x66\x69\x6C\x74\x65\x72\x5F\x64\x61\x74\x61": variable_26,
            "\x63\x73\x72\x66\x5F\x74\x65\x73\x74\x5F\x6E\x61\x6D\x65": $["cookie"]("csrf_cookie_name")
        },
        beforeSend: function() {
            var texts = "Loading Data... Please Wait...";
            ajaxloading(texts);
        },
        success: function(variable_1d) {
            variable_1f(variable_1d);
            if (variable_1d["result"] == "success") {
                variable_1f(variable_1d["result"])
            } else {
                if (variable_1d["result"] == "fail") {
                    show_toast('warning','Something went wrong')
                }
            };
            closeajax()
        },
        error: function() {}
    })
}

function failureResult(msg){
  var result = '<div class="alert alert-danger"><a href="javascript:void(0);" class="close" data-dismiss="alert" aria-label="close"></a> <strong>Error!</strong> '+msg+' </div>';
  return result
}
function successResult(msg){
  var result = '<div class="alert alert-success"><a href="javascript:void(0);" class="close" data-dismiss="alert" aria-label="close"></a> <strong>Success!</strong> '+msg+'</div>';
  return result
}

//utilities

jQuery(function($) {
   var path = window.location.href; // because the 'href' property of the DOM element is the absolute path
   $('ul.side-menu.metismenu a').each(function() { 
      if (this.href === path) {
          $(this).addClass('active');
          $(this).parent().parent().closest("ul").addClass('in');
          $(this).parent().parent().parent().closest("li").addClass('active');
      }
   });
}); 

function check_pass() {
  var val=document.getElementById("password").value;
  var no=0;
  if(val!="")
  {
    // If the password length is less than or equal to 6
    if(val.length<=6)no=1;

    // If the password length is greater than 6 and contain any lowercase alphabet or any number or any special character
    if(val.length>6 && (val.match(/[a-z]/) || val.match(/\d+/) || val.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)))no=2;

    // If the password length is greater than 6 and contain alphabet,number,special character respectively
    if(val.length>6 && ((val.match(/[a-z]/) && val.match(/\d+/)) || (val.match(/\d+/) && val.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)) || (val.match(/[a-z]/) && val.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/))))no=3;

    // If the password length is greater than 6 and must contain alphabets,numbers and special characters
    if(val.length>6 && val.match(/[a-z]/) && val.match(/\d+/) && val.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/))no=4;

    // If the password length is greater than 15 and must contain alphabets,numbers and special characters
    if(val.length>15 && val.match(/[a-z]/) && val.match(/\d+/) && val.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/))no=5;

    if(no==1)
    {
     document.getElementById("pass_type").innerHTML="Very Weak";
    }

    if(no==2)
    {
     document.getElementById("pass_type").innerHTML="Weak";
    }

    if(no==3)
    {
     document.getElementById("pass_type").innerHTML="Good";
    }

    if(no==4)
    {
     document.getElementById("pass_type").innerHTML="Strong ";
    }

    if(no==4)
    {
     document.getElementById("pass_type").innerHTML="Very Strong ";
    }
  }

  else
  {
    document.getElementById("pass_type").innerHTML="";
  }
}

function goBottomScroll(id){
  $(id).stop().animate({ scrollTop: $(id)[0].scrollHeight}, 1000);
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
