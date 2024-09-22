if($("#login-form").length>0){
    login();
}

function login() {
    $('#login-form').validate({
        errorClass: "help-block",
        rules: {
            uid: {
                required: true
            },
            pwd: {
                required: true
            }
        },
        messages:{
            uid :{required :"Please enter your username"},
            pwd:{required:"Please enter your password"}
        }, 
        submitHandler: function(){
          var form_btn = $("#login-form").find('button[type="submit"]');
          var form_btn_old_msg = form_btn.html();
          form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
            var admin=$('#login-form').serializeArray();
            $.post(urljs+"admin/login/authentication",admin,function(data){
                if(data.result=='success'){
                    form_btn.html("<i class='fa fa-spinner fa-spin '></i> Redirecting...");
                   window.location=data.urldirect;
                }
                else{
                    form_btn.prop('disabled', false).html(form_btn_old_msg);
                    jQuery('#login_res').html(failureResult(data.msg)); 
                    setTimeout(function(){
                        jQuery("#login_res").hide();
                    },5000);
                    jQuery("#login_res").show();
                }
            },"json");

        },
        highlight: function(e) {
            $(e).closest(".form-group").addClass("has-error")
        },
        unhighlight: function(e) {
            $(e).closest(".form-group").removeClass("has-error")
        },
    });
}

if($("#aforgot-form").length>0){
    forgot();
}

function forgot() {
    $('#aforgot-form').validate({
        errorClass: "help-block",
        rules: {
            uid: {
                required: true
            },
            email: {
                required: true,
                email:true
            }
        },
        messages:{
            uid :{required :"Please enter your username"},
            email:{required:"Please enter your email address",email:"Please enter valid email address!"}
        },
        submitHandler: function(){
          var form_btn = $("#aforgot-form").find('button[type="submit"]');
          var form_btn_old_msg = form_btn.html();
          form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
            var admin=$('#aforgot-form').serializeArray();
            $.post(urljs+"admin/login/forgot_pass",admin,function(data){
                if(data.result>0){
                   form_btn.prop('disabled', false).html(form_btn_old_msg);
                   $('#aforgot-form').find('input').val();
                }
                else{
                    form_btn.prop('disabled', false).html(form_btn_old_msg);
                    jQuery('#login_res').html(failureResult(data.msg)); 
                    setTimeout(function(){
                        jQuery("#login_res").hide();
                    },5000);
                    jQuery("#login_res").show();
                }
            },"json");

        },
        highlight: function(e) {
            $(e).closest(".form-group").addClass("has-error")
        },
        unhighlight: function(e) {
            $(e).closest(".form-group").removeClass("has-error")
        },
    });
}