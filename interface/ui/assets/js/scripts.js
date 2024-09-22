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
    $["each"](variable_9, function(variable_10, variable_11) {
        if (variable_10 != "") {
            var variable_12 = $("[data-type=" + variable_10 + "]")["attr"]("id");
            if($("#" + variable_10).length<=0){
                $('.filter_properties').prepend('<input type="hidden" name="'+variable_10+'" value="'+variable_11+'" data-type="'+variable_10+'" id="'+variable_10+'" class="refine_filter" />');
            }
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

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
    return false;
};

function updateURLParameter(variable,value){
    var currentUrl = window.location.href;
    var parsedUrl = $.url(currentUrl);
    var params = parsedUrl.param();
    params[variable] = value;
    var newUrl = "#" + $.param(params);
    window.location.href = newUrl;
}


function ajaxloading(texts){
    if(jQuery('body').find('#resultLoading').attr('id') != 'resultLoading'){
        jQuery('body').append('<div id="resultLoading"><div class="loaderBox"><div class="card"><div class="card-body"><div class="spinner-border avatar-sm text-primary" role="status"></div><div id="ajaxtext">'+texts+'</div></div></div></div><div class="bg"></div></div>');
        jQuery('body').css('cursor', 'wait');
    }
}

function closeajax(){
    $("#resultLoading").remove();
     jQuery('body').css('cursor', 'default');
}

function swal_alert(type,msg,title,load_type,urlredirect){
    if(urlredirect=='' && load_type==''){
        swal(title,msg,type);
    }else{
        swal(title,msg,type).then (function (isConfirm) {
            if(urlredirect!=''){
                if (isConfirm.value) {
                    if(load_type=='url'){
                        window.location.href=urljs+urlredirect;
                    }else{
                        window.location.reload();
                    }
                }else{
                    if(load_type=='url'){
                        window.location.href=urljs+urlredirect;
                    }else{
                        window.location.reload();
                    }
                }
            }
        });
    }
}

function failureResult(form,msg,autohide){
    var div =$(form).find('.div_res');
  //console.log(div);
  if(div.length<=0){
    $(form).prepend('<div class="div_res"></div>');
  }
  if(autohide==true){
    var result = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>'+msg+'</div>';  
  }else{
    var result = '<div class="alert alert-danger fade show" role="alert">'+msg+'</div>';
  }
  
  $(div).html(result); 
  if(autohide==true){
    setTimeout(function(){
      $(div).html('');
    },5000);
  }
  $(div).show();
}

function successResult(form,msg,autohide){
  if(autohide==true){
    var result = '<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>'+msg+'</div>';
  }else{
    var result = '<div class="alert alert-success fade show" role="alert">'+msg+'</div>';
  }
  var div =$(form).find('.div_res');
  if(div.length<=0){
    $(form).prepend('<div class="div_res"></div>');
  }
  $(div).html(result);
  if(autohide==true){
    setTimeout(function(){
      $(div).html('');
    },5000);
  }
  $(div).show();
}

function button_load(form,msg,btn_elem){
  if(btn_elem==''){
    var btn = $(form).find('button[type=submit]');
  }else{
    var btn = $(btn_elem);
  }
  var old_msg = btn.html();
  btn.attr('data-html',old_msg);
  btn.attr('disabled','true');
  btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> '+msg);
}

function end_button_load(form,btn_elem){
  if(btn_elem==''){
    var btn = $(form).find('button[type=submit]');
  }else{
    var btn = $(btn_elem);
  }
  var old_msg = btn.attr('data-html');
  btn.removeAttr('data-html');
  btn.removeAttr('disabled');
  btn.html(old_msg);
}

function show_toast(msgType,message){
  toastr.clear();
    toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": true,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "6500",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }
    toastr[msgType](message)
}

function getUrlsParam() {
    var arrayFilter=[];
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('#') + 1);
    //console.log('hashes: '+hashes);
    var sPageURL = window["location"]["hash"]["substring"](1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;
    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');
        if(sParameterName[0] === undefined || sParameterName[0]!=''){            
            // variable_26["push"]({
            //     "\x74\x79\x70\x65":sParameterName[0],
            //     "\x76\x61\x6C\x75\x65":sParameterName[1]
            // });
            arrayFilter["push"]({
                "\x6E\x61\x6D\x65":sParameterName[0],
                "\x76\x61\x6C\x75\x65":sParameterName[1]
            });
        }
        
    }
    return arrayFilter;
}


function ajax_filter(variable_1e,variable_6, variable_1f) {

    var variable_26 = [];
    var variable_27 = [];
    jQuery(".refine_filter option:selected,input.refine_filter")["each"](function() {
        //console.log(GetURLParameter($(this)["attr"]("data-type")));
        if($(this)["val"]()!=''){
            variable_26["push"]({
                "\x74\x79\x70\x65": $(this)["attr"]("data-type"),
                "\x76\x61\x6C\x75\x65": $(this)["val"]()
            });
            variable_27["push"]({
                "\x6E\x61\x6D\x65": $(this)["attr"]("data-type"),
                "\x76\x61\x6C\x75\x65": $(this)["val"]()
            });
        }
    });
    // var arrayFilter=getUrlsParam();

    // variable_27.filter(function(item) {
    //     var i=0;
    //     for(i=0;i<arrayFilter.length;i++){
    //     //$(arrayFilter).each(function () {
    //         if(item.name==arrayFilter[i]['name']){
    //          //console.log('found',arrayFilter[i]['name']);
    //         }else{
    //             variable_26["push"]({
    //                 "\x74\x79\x70\x65":arrayFilter[i]['name'],
    //                 "\x76\x61\x6C\x75\x65":arrayFilter[i]['value']
    //             });
    //             variable_27["push"]({
    //                 "\x6E\x61\x6D\x65":arrayFilter[i]['name'],
    //                 "\x76\x61\x6C\x75\x65":arrayFilter[i]['value']
    //             });
    //         }
    //     }
    // });   

    var variable_28 = "?"; 
    // variable_28 += $["param"](variable_27);
    // history.pushState("","",variable_28);
    variable_28 = $["param"](variable_27, true);
    window["location"]["hash"] = variable_28;

    $["ajax"]({
        url: urljs + 'utils/getFilterList',
        type: "POST",
        dataType: "json",
        data: {
            "\x70\x61\x67\x65": variable_6,
            "\x66\x69\x6C\x74\x65\x72\x5F\x64\x61\x74\x61": variable_26,
            "module":variable_1e
        },
        beforeSend: function() {
            //ajaxloading('Loading...');
        },
        success: function(variable_1d) {
            variable_1f(variable_1d);
            closeajax()
        },
        error: function() {}
    })
}


function ajax_custom_filter(variable_1e,variable_6, variable_1f) {

    var variable_26 = [];
    var variable_27 = [];
    jQuery(".refine_filter option:selected,input.refine_filter")["each"](function() {
        //console.log(GetURLParameter($(this)["attr"]("data-type")));
        if($(this)["val"]()!=''){
            variable_26["push"]({
                "\x74\x79\x70\x65": $(this)["attr"]("data-type"),
                "\x76\x61\x6C\x75\x65": $(this)["val"]()
            });
            variable_27["push"]({
                "\x6E\x61\x6D\x65": $(this)["attr"]("data-type"),
                "\x76\x61\x6C\x75\x65": $(this)["val"]()
            });
        }
    });

    var variable_28 = "?"; 
    variable_28 = $["param"](variable_27, true);
    window["location"]["hash"] = variable_28;

    $["ajax"]({
        url: urljs + variable_1e,
        type: "POST",
        dataType: "json",
        data: {
            "\x70\x61\x67\x65": variable_6,
            "\x66\x69\x6C\x74\x65\x72\x5F\x64\x61\x74\x61": variable_26
        },
        beforeSend: function() {
            //ajaxloading('Loading...');
        },
        success: function(variable_1d) {
            variable_1f(variable_1d);
            closeajax()
        },
        error: function() {}
    })
}

function filterdata() {
    $(".filter").unbind().on("click", function(e) {
        e.preventDefault();
        getdatas();
    });
    $("#page_result").unbind().on("click", ".pagination a", function(e) {
        e.preventDefault();
        var page = $(this).attr("data-page");
        $("#page").val(page);
        getdatas(page);
    });
}


function ajax_request(variable_21,variable_1e,ajax_type,ajax_text,variable_1f) {
    formdata = new FormData($(variable_21)[0]);
    //$(".error")["remove"]();
    $(variable_21).find('.div_res').html('');
    $["ajax"]({
        url: urljs + variable_1e,
        type: "POST",
        dataType: "json",
        data: formdata,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function() {
            if(ajax_type=='button'){
               button_load(variable_21,ajax_text,'');
            }else{
                ajaxloading(ajax_text);
            }
        },
        success: function(variable_1d) {
            if(ajax_type=='button'){
                end_button_load(variable_21,'');
            }else{
                closeajax();
            }
            variable_1f(variable_1d);
        },
        error: function() {}
    })
}

function isEmailValid(email){
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;    
    return regex.test(email);
    
}

function setURIParam_old(key,value) {
    //var searchParams = new URLSearchParams(window.location.search);
    //searchParams.set(key, value);
    var id=getUrlParameter('id');
    //var newUrl = window.location+'&'+key+'='+value;
    var newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?id='+id+'&'+key+'='+value; 
    //console.log(window.location);
    window.history.pushState({ path: newUrl }, '',newUrl);
}

//update URL Parameter
function setURIParam(key,val){
    var url = window.location.href;
    var reExp = new RegExp("[\?|\&]"+key + "=[0-9a-zA-Z\_\+\-\|\.\,\;]*");

    if(reExp.test(url)) {
        // update
        var reExp = new RegExp("[\?&]" + key + "=([^&#]*)");
        var delimiter = reExp.exec(url)[0].charAt(0);
        url = url.replace(reExp, delimiter + key + "=" + val);
    } else {
        // add
        var newParam = key + "=" + val;
        if(!url.indexOf('?')){url += '?';}

        if(url.indexOf('#') > -1){
            var urlparts = url.split('#');
            url = urlparts[0] +  "&" + newParam +  (urlparts[1] ?  "#" +urlparts[1] : '');
        } else {
            url += "&" + newParam;
        }
    }
    window.history.pushState(null, document.title, url);
}

//remove URL parameter
function removeURIParam(parameter) {
    var url = window.location.href;
    //prefer to use l.search if you have a location/link object
    var urlparts= url.split('?');   
    if (urlparts.length>=2) {

        var prefix= encodeURIComponent(parameter)+'=';
        var pars= urlparts[1].split(/[&;]/g);

        //reverse iteration as may be destructive
        for (var i= pars.length; i-- > 0;) {    
            //idiom for string.startsWith
            if (pars[i].lastIndexOf(prefix, 0) !== -1) {  
                pars.splice(i, 1);
            }
        }

        url= urlparts[0]+'?'+pars.join('&');
        window.history.pushState(null, document.title, url);
    } else {
        // return url;
    }
}

function createJsonArray(jsonElem){
    var jsonObj = [];
    // $(jsonElem).each(function() {
    //     var key = $(this).attr("name");
    //     var value = $(this).val();

    //     item = {}
    //     item ["'"+key+"'"] = value;

    //     jsonObj.push(item);
    // });

    $(jsonElem).each(function () {
      jsonObj.push({'name':$(this).attr('data-type'),'value':$(this).val()});
    });

    //return JSON.stringify(jsonObj);
    return jsonObj;
}

function IsJsonString(str) {
  try {
    var json = JSON.parse(str);
    return (typeof json === 'object');
  } catch (e) {
    return false;
  }
}


function api_get_request(form,apiUrl,method,data_fields,ajax_type,ajax_text,appendElement) {
    if(ajax_type=='button'){
        button_load(form,ajax_text,'');
    }else{
        ajaxloading(ajax_text);
    }
    $.post(urljs + 'utils/api_get_request',{'apiUrl':apiUrl,'data_fields':data_fields,'method':method},function(returnData){
        if(ajax_type=='button'){
            end_button_load(form,'');
        }else{
            closeajax();
        }
        if(returnData.status == "success"){
            if(appendElement!='' && returnData.data_list!=''){
                var data = $.parseJSON(returnData.data_list);
                $.each(data, function(k, v) {
                    //console.log(k + ' is ' + v);
                    if(v!=''){
                        $(appendElement + "[data-type='" + k + "']").val(v);
                    }
                });
            }else{
                show_toast();
            }
        }else{
          show_toast('error',returnData.message);
        }
    },"json"); 
}

function ajax_modal(buttonElement, url, mod_title,modal_size,submit_func) {
    $(buttonElement).unbind().click(function() {
        var id=$(this).attr('data-id');
        var mid=$(this).attr('data-mid');
        var modal_className="";
        var mod_size='';
        console.log(modal_size);
        if(modal_size==="fullLarge"){
            mod_size="extra-large";
            modal_className="largeWidth";
        }else{
            mod_size=modal_size;
            modal_className="";
        }
        var dataModal = bootbox.dialog({
          title: mod_title,
          message: '<div class="spinner-border spinner-border-sm text-primary" role="status"></div> Loading, Please wait...',
          closeButton: true,
          size: mod_size,
          animate:true,
          centerVertical:true,
          className: "userModalView "+modal_className,
        });
        // dataModal.init(function() {
        //     dataModal.attr("id", "userModalView")
        // });
        var url_params=getUrlDatas();
        $(".bootbox.userModalView").removeAttr('tabindex');
        $["ajax"]({
            url: urljs + url,
            type: "POST",
            dataType: "json",
            data: {
                "\x69\x64": id,
                "mid":mid,
                "ui_url":url,
                "filter_data":url_params
            },
            beforeSend: function() {
                
            },
            success: function(returnData) {
                dataModal.find('.bootbox-body').html(returnData.message);
                if(submit_func!=''){
                    submit_func();
                }
            },
            error: function() {}
        })
    });
}

function ajax_export(buttonElement,data_module) {
    $(buttonElement).unbind().click(function() {
        var url_params=getUrlDatas();
        $["ajax"]({
            url: urljs + 'utils/export_data',
            type: "POST",
            dataType: "json",
            data: {
                "module":data_module,
                "filter_data":url_params
            },
            beforeSend: function() {
                button_load('','Processing...',buttonElement);
            },
            success: function(returnData) {
                end_button_load('',buttonElement);
                if(returnData.status=='success' && returnData.file_url!=''){
                    swal({
                        title: returnData.message,
                        type: "success",
                        showCancelButton: true,
                        cancelButtonClass: 'btn-secondary waves-effect',
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "Download",
                        cancelButtonText: "Close",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    }).then (function (isConfirm) { 
                        if (isConfirm.value) { 
                            window.location.href = returnData.file_url;
                        }
                    });
                }else{
                    show_toast('error',returnData.message);
                }
            },
            error: function() {}
        })
    });
}

function modal_alert(mod_title,mod_content,modal_size) {
    var dataModal = bootbox.dialog({
      title: mod_title,
      message: mod_content,
      closeButton: true,
      size: modal_size,
      animate:true,
      centerVertical:true,
      className: "userModalView modal-content-alert",
      buttons: {
            ok: {
                label: "OK",
                className: 'btn-success',
                callback: function(){
                }
            }
        }
    });
}

function ajax_import(buttonElement, module_name, mod_title,modal_size,submit_func) {
    $(buttonElement).unbind().click(function() {
        var dataModal = bootbox.dialog({
          title: mod_title,
          message: '<div class="spinner-border spinner-border-sm text-primary" role="status"></div> Loading, Please wait...',
          closeButton: true,
          size: modal_size,
          animate:true,
          centerVertical:true,
          className: "userModalView",
        });
        var url_params=getUrlDatas();
        $(".bootbox.userModalView").removeAttr('tabindex');
        $["ajax"]({
            url: urljs + "excelimport/get_import",
            type: "POST",
            dataType: "json",
            data: {
                "filter_data":url_params,
                "module":module_name
            },
            beforeSend: function() {
            },
            success: function(returnData) {
                dataModal.find('.bootbox-body').html(returnData.message);
                init_excel_dropify();
                save_import(submit_func);
            },
            error: function() {}
        })
    });
}

function save_import(render_function){
    $('#importForm').validate({
        errorClass: 'error',
        validClass: 'valid',
        rules:{
            "file[]":{required:true}
        },
        messages:{
            "file[]":{required:"Please select the excel file to import"}
        },
      submitHandler: function(e){
            //event.preventDefault();
            var formdata = new FormData($('#importForm')[0]);
            var module_name=$('#importForm').find('[name=module]').val();
            $["ajax"]({
            url: urljs+"excelimport/import_datas",
            type: "POST",
            dataType: "json",
            data: formdata,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $('#importForm').find('.div_res').html('');
                button_load('#importForm','Please wait...','');
                excel_data_timer();
            },
            success: function(data) {
                end_button_load('#importForm','');
                delete_timer();
                if(data.status=='success'){
                    $('.bootbox.modal.userModalView').find('.bootbox-close-button').trigger('click');
                     var dataModal = bootbox.dialog({
                      title: "Success",
                      message: data.message,
                      closeButton: true,
                      size: "medium",
                      animate:true,
                      centerVertical:true,
                      className: "userModalView modal-content-alert",
                      buttons: {
                            noclose: {
                                label: "Download Report",
                                className: 'btn-info',
                                callback: function(){
                                    if(data.datas.file_url!=''){
                                        window.location.href=data.datas.file_url;
                                    }else{                           
                                        WriteToFile('.bootbox-body',module_name+'-import-reports');
                                    }
                                }
                            },
                            ok: {
                                label: "OK",
                                className: 'btn-success',
                                callback: function(){
                                }
                            }
                        }
                    });

                    if(render_function!=''){
                        render_function();
                    }else{
                        getdatas();
                    }
                }else{
                    $('.excel_progress').html('');                  
                    failureResult('#importForm',data.message,false);
                }
            }
        })
        }
  });
}

function getUrlDatas() {
    var arrayFilter=[];
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('#') + 1);
    //console.log('hashes: '+hashes);
    var sPageURL = window["location"]["hash"]["substring"](1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;
    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');
        if(sParameterName[0] === undefined || sParameterName[0]!=''){            
            // variable_26["push"]({
            //     "\x74\x79\x70\x65":sParameterName[0],
            //     "\x76\x61\x6C\x75\x65":sParameterName[1]
            // });
            arrayFilter["push"]({
                "\x74\x79\x70\x65":sParameterName[0],
                "\x76\x61\x6C\x75\x65":decodeURIParam(sParameterName[1])
            });
        }
        
    }
    return arrayFilter;
}

function delete_data(del_module,del_title,display_name,fun_name) {
    $('.deleteData').click(function(){
        var mappingId=$(this).attr('data-id');
        var mainId=$(this).attr('data-mid');
        swal({
            title: "You are about to delete "+del_title,
            text: "It cannot be restored at a later time! Continue?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: 'btn-secondary waves-effect',
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            closeOnConfirm: false,
            closeOnCancel: false
        }).then (function (isConfirm) {   
        if (isConfirm.value) { 
                ajaxloading('Processing...');
                $.post(urljs+'utils/delete_data',{'id':mappingId,'mainId':mainId,'module':del_module,'display_name':display_name},function(data){
                    closeajax();
                    if(data.status=='success'){
                        if(fun_name!=''){
                            fun_name(data);
                        }else{
                            getdatas();
                            show_toast('success',data.message);
                        }     
                    }else { 
                        swal("Failed!", data.message,'warning');
                    }
                },"json");
            } 
        });
    });
}


function delete_ajax(element,url,del_title,return_func) {
    $(element).click(function(){
        var mappingId=$(this).attr('data-id');
        var mainId=$(this).attr('data-mid');
        swal({
            title: "You are about to delete "+del_title,
            text: "It cannot be restored at a later time! Continue?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: 'btn-secondary waves-effect',
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            closeOnConfirm: false,
            closeOnCancel: false
        }).then (function (isConfirm) {   
        if (isConfirm.value) { 
                ajaxloading('Processing...');
                $.post(urljs+url,{'id':mappingId,'mid':mainId},function(data){
                    closeajax();
                    if(data.status=='success'){
                        if(return_func!=''){
                            return_func(data);
                        }else{
                            show_toast('success',data.message);
                        }     
                    }else { 
                        swal("Failed!", data.message,'warning');
                    }
                },"json");
            } 
        });
    });
}



//toggle password
function pwd_showHide(){
    $(".password-toggle .pwd-btn").on('click', function(event) {
        event.preventDefault();
        var elem=$(this).parent();
        if($(elem).find('input').attr("type") == "password"){
            $(elem).find('input').attr('type', 'text');
            $(this).find('i').toggleClass("mdi-eye-off").toggleClass("mdi-eye-outline");
        }else{
            $(elem).find('input').attr('type', 'password');
            $(this).find('i').toggleClass("mdi-eye-outline").toggleClass("mdi-eye-off");
        }
    });
}

if($('.password-toggle').length>0){
    pwd_showHide();
}

function download_import_sample(param){
    var dataType=$(param).attr('data-type');
    var fileurl='downloads/import-samples/'+dataType+'.xlsx';
    window.location.href = urljs+fileurl;
}

function WriteToFile(textElement,fileName)
{
    var regex = /<br\s*[\/]?>/gi;
    var textToSave = $(textElement).html();
    textToSave=textToSave.replace(regex, "\n");
    textToSave=textToSave.replace(/(<([^>]+)>)/ig,"");
    //var textToSave = "Hi hello";
    var textToSaveAsBlob = new Blob([textToSave], {type:"text/plain"});
    var textToSaveAsURL = window.URL.createObjectURL(textToSaveAsBlob);
    var fileNameToSaveAs = fileName+'.txt';
 
    var downloadLink = document.createElement("a");
    downloadLink.download = fileNameToSaveAs;
    downloadLink.innerHTML = "Download File";
    downloadLink.href = textToSaveAsURL;
    downloadLink.onclick = destroyClickedElement;
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);
 
    downloadLink.click();
}
 
function destroyClickedElement(event)
{
    document.body.removeChild(event.target);
}
 
function loadFileAsText()
{
    var fileToLoad = document.getElementById("fileToLoad").files[0];
 
    var fileReader = new FileReader();
    fileReader.onload = function(fileLoadedEvent) 
    {
        var textFromFileLoaded = fileLoadedEvent.target.result;
        document.getElementById("inputTextToSave").value = textFromFileLoaded;
    };
    fileReader.readAsText(fileToLoad, "UTF-8");
}

function init_accordian() {
    $('.head_panel').click(function(e) {
      e.preventDefault();    
      let $this = $(this);
      if ($this.next().hasClass('active')) {
         $this.next().removeClass('active');
         $(this).find('.mdi').addClass('mdi-chevron-up');
      } else {
          $this.next().addClass('active');
         $this.next().addClass('active');
         $(this).find('.mdi').removeClass('mdi-chevron-up');
         $(this).find('.mdi').addClass('mdi-chevron-down');
      }
  });
}

function init_dropify(){
 $('.dropify').dropify({
        messages: {
            'default': 'Drag and drop a file here or click',
            'replace': 'Drag and drop or click to replace',
            'remove': 'Remove',
            'error': 'Ooops, something wrong appended.'
        },
        error: {
            'fileSize': 'The file size is too big'
        }
    });
}

function init_datepicker(start_date,end_date){
    var today= new Date();
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true,
        startDate:start_date,
        endDate:end_date
    });
}

function btn_url_load(){
    $('.btn-url-load').on('click',function(){
        button_load('','Loading',$(this));
    });
}

function ajax_modal_report(title,data,file_name){
    var dataModal = bootbox.dialog({
      title: title,
      message: data.message,
      closeButton: false,
      size: "medium",
      animate:true,
      centerVertical:true,
      className: "userModalView modal-content-alert",
      buttons: {
            noclose: {
                label: "Download Report",
                className: 'btn-info',
                callback: function(){
                    if(typeof data.datas === 'undefined'){
                        WriteToFile('.bootbox-body',file_name);
                    }else{
                        if(typeof data.datas.file_url === 'undefined'){
                            WriteToFile('.bootbox-body',file_name);
                        }else{
                            window.location.href=data.datas.file_url;
                        }
                    }
                }
            },
            ok: {
                label: "OK",
                className: 'btn-success',
                callback: function(){
                }
            }
        }
    });
}


function initHideShowPassword(){
    $("[data-password]").on("click", function() {
        "false" == $(this).attr("data-password") ? ($(this).siblings("input").attr("type", "text"), $(this).attr("data-password", "true"), $(this).addClass("show-password")) : ($(this).siblings("input").attr("type", "password"), $(this).attr("data-password", "false"), $(this).removeClass("show-password"))
    });
}


function init_daterange(){
    $('.input-limit-datepicker').daterangepicker({
        autoUpdateInput: false,
        minDate: '01/03/2022',
        maxDate: new Date(),
        buttonClasses: ['btn', 'btn-sm'],
        applyClass: 'btn-primary',
        cancelClass: 'btn-secondary',
        dateLimit: {
            months: 2
        },
        // opens: 'left',
        // drops: 'down',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        locale: {
            applyLabel: 'Submit',
            cancelLabel: 'Cancel',
            fromLabel: 'From',
            toLabel: 'To',
            customRangeLabel: 'Custom',
            daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            firstDay: 1,
            format: 'MM/DD/YYYY'
        }
    });
    $('.input-limit-datepicker').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });
}


function decodeURIParam(p) {
  return decodeURIComponent(p.replace(/\+/g, ' '));
}

function encodeURIParam(str) {
  return encodeURIComponent(str).replace(/[!'()*]/g, function(c) {
    return '%' + c.charCodeAt(0).toString(16).toUpperCase();
  });
}


function JSONToCSVConvertor(JSONData, ReportTitle, ShowLabel) {
    //If JSONData is not an object then JSON.parse will parse the JSON string in an Object
    var arrData = typeof JSONData != 'object' ? JSON.parse(JSONData) : JSONData;
    
    var CSV = 'sep=,' + '\r\n\n';

    //This condition will generate the Label/Header
    if (ShowLabel) {
        var row = "";
        
        //This loop will extract the label from 1st index of on array
        for (var index in arrData[0]) {
            var column_data=arrData[0];
            column_data=column_data.replace(/[^a-zA-Z ]/g, "");
            
            //Now convert each value to string and comma-seprated
            row += column_data + ',';
        }

        row = row.slice(0, -1);
        
        //append Label row with line break
        CSV += row + '\r\n';
    }
    
    //1st loop is to extract each row
    for (var i = 0; i < arrData.length; i++) {
        var row = "";
        
        //2nd loop will extract each column and convert it in string comma-seprated
        for (var index in arrData[i]) {
            var row_data=arrData[i][index];
            row_data=row_data.toString().replace(/[^a-zA-Z ]/g, "");
            row += '"' + row_data + '",';
        }

        row.slice(0, row.length - 1);
        
        //add a line break after each row
        CSV += row + '\r\n';
    }

    if (CSV == '') {        
        alert("Invalid data");
        return;
    }   
    
    //Generate a file name
    var fileName = "CRM_Excel_";
    //this will remove the blank-spaces from the title and replace it with an underscore
    fileName += ReportTitle.toString().replace(/ /g,"_");   
    
    //Initialize file format you want csv or xls
    var uri = 'data:text/csv;charset=utf-8,' + escape(CSV);
    
    // Now the little tricky part.
    // you can use either>> window.open(uri);
    // but this will not work in some browsers
    // or you will not get the correct file extension    
    
    //this trick will generate a temp <a /> tag
    var link = document.createElement("a");    
    link.href = uri;
    
    //set the visibility hidden so it will not effect on your web-layout
    link.style = "visibility:hidden";
    link.download = fileName + ".csv";
    
    //this part will append the anchor tag and remove it after automatic click
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}



