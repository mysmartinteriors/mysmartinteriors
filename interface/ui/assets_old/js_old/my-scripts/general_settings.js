   jQuery(document).ready(function() {
        $('#megamenu_form').validate({
            submitHandler: function(e){
                var form_btn = $('#megamenu_form').find('button[type="submit"]');
                var form_btn_old_msg = form_btn.html();
                var js=$('#megamenu_form').serializeArray();
                form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
                $.post(urljs+"admin/settings/update_megamenu",js,function(data){
                    form_btn.prop('disabled', false).html(form_btn_old_msg);
                    if (data.status == "success") {
                        show_toast('success',data.msg);
                    }else{
                        show_toast('warning',data.msg);
                    }
                },"json");
            }
        });



   });