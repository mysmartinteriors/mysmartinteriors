//company settings
if($("#save_company_sett").length>0){
    saveCompanyData();
    initUpload();
}
function initUpload(){
    var logo_exist=$("#logoinput").attr("data-logo");
    if(logo_exist!=""){
        var imageFile=urljs+'uploads/site/'+logo_exist;
    }
    var drEvent= $('.dropify').dropify({
        defaultFile:imageFile,
        messages: {
            'default': 'Drag and drop a file here or click',
            'replace': 'Drag and drop or click to replace',
            'remove': 'Remove',
            'error': 'Ooops, something wrong appended.'
        },
        error: {
            'fileSize': 'The file size is too big (25M max).'
        }
    });
    $('.dropify-filename-inner').html(logo_exist);
}

function saveCompanyData(){ 
    $('#save_company_sett').validate({
        rules:{
            companyName: {  required: true},
            address: {required: true},
            phone: {required: true,number:true},
            mobile: {required: true,number:true},
            email: {required: true,email:true}              
        },
        messages:{
            companyName: {required: "Please enter company name"},
            address: {required: "Please enter address"},
            phone: {required: "Please enter phone number"},
            mobile: {required: "Please enter mobile number"},
            email: {required: "Please enter email address",email:"Please enter valid email address!"},
        },  
        errorClass: "help-block error",
        highlight: function(e) {
            $(e).closest(".form-group.row").addClass("has-error")
        },
        unhighlight: function(e) {
            $(e).closest(".form-group.row").removeClass("has-error")
        },
        submitHandler: function(e){
            event.preventDefault();
            var formdata = new FormData($('#save_company_sett')[0]);
            $["ajax"]({
                url: urljs+"admin/settings/save_csettings",
                type: "POST",
                dataType: "json",
                data: formdata,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                   ajaxloading("Saving... Please Wait...");
                },
                success: function(data) {
                    closeajax();
                    if(data.status=='success'){
                        swal_alert('Success',data.message,'success','');
                    }
                    else{
                        show_toast('Error',data.message);
                    }
                },
                error: function() {
                    closeajax();
                }
            })
        }
    });
}

//email settings
if($("#save_email_settings").length>0){
    save_email_sett();
}

function save_email_sett(){ 
    $('#save_email_settings').validate({
        rules:{
            host: {required: true },
            port: {required: true,number:true},
            hostUsername: {required: true},
            hostPassword: {required: true},
            from: {required: true,email:true},
            fromText: {required: true},
            smtpAuth: {required: true},
            smtpSecure: {required: true}
            
        },
        messages:{
            host: {required: "Please enter host"},
            port: {required: "Please enter port",number:"Please nenter valid port numbers!"},
            hostUsername: {required: "Please enter host username"},
            hostPassword: {required: "Please enter host password"},
            from: {required: "Please enter from email",email :"Invalid from email"},
            fromText: {required: "Please enter from text"},
            smtpAuth: {required: "Please enter smtp authentication type"},
            smtpSecure: {required: "Please enter smtp secure type"}
        },  
        errorClass: "help-block error",
        highlight: function(e) {
            $(e).closest(".form-group.row").addClass("has-error")
        },
        unhighlight: function(e) {
            $(e).closest(".form-group.row").removeClass("has-error")
        },
        submitHandler: function(e){
            event.preventDefault();
            var formdata = new FormData($('#save_email_settings')[0]);
            $["ajax"]({
                url: urljs+"admin/settings/save_esettings",
                type: "POST",
                dataType: "json",
                data: formdata,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                   ajaxloading("Saving... Please Wait...");
                },
                success: function(data) {
                    closeajax();
                    if(data.result=='success'){
                        swal_alert('Success','Updated Successfully!','success','');
                    }
                    else{
                        show_toast('warning','Unable to update at this time!');
                    }
                },
                error: function() {
                    closeajax();
                }
            })
        }
    });
}

//profile settings
if($("#save_profile_form").length>0){
    saveProfileData();
    initUpload();
}
function saveProfileData(){ 
    $('#save_profile_form').validate({
        rules: {
            username:{required:true},
            password:{required:true,minlength:6,maxlength:12},
            c_password:{required: true, minlength: 6,maxlength:12,equalTo:"[name=password]"},
            firstName:{required:true},
            lastName:{required:true},
            email:{required:true,email:true}
        },
        messages:{
            username :{required :"Please provide username"},
            password :{required :"Please enter the password"},
            c_password :{required :"Please confirm the password",equalTo:"Password do not match"},
            firstName:{required:"Please provide the first name"},
            lastName:{required:"Product provide the last name"},
            email :{required :"Please provide email address"}
        },  
        errorClass: "help-block error",
        highlight: function(e) {
            $(e).closest(".form-group.row").addClass("has-error")
        },
        unhighlight: function(e) {
            $(e).closest(".form-group.row").removeClass("has-error")
        },
        submitHandler: function(e){
            event.preventDefault();
            var formdata = new FormData($('#save_profile_form')[0]);
            $["ajax"]({
                url: urljs+"admin/settings/savemysettings",
                type: "POST",
                dataType: "json",
                data: formdata,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                   ajaxloading("Saving... Please Wait...");
                },
                success: function(data) {
                    closeajax();
                    if(data.status=='success'){
                        swal_alert('Success',data.message,'success','');
                    }
                    else{
                        show_toast('warning',data.message);
                    }
                },
                error: function() {
                    closeajax();
                }
            })
        }
    });
}