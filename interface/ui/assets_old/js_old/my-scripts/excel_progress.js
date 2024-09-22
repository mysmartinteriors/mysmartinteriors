

function init_excel_dropify(){
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

function excel_progress_show(){
    var progress_html = '<div class="progress-info">\
                  <div class="progress-label">\
                    <span>Start</span>\
                  </div>\
                  <div class="progress-percentage">\
                    <span>0%</span>\
                  </div>\
                </div>\
                <div class="progress">\
                  <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 00%;"></div>\
                </div>';
    $('.excel_progress').html(progress_html);
}

function excel_data_timer(){
    excel_progress_show();
    window.myTimer  = window.setInterval(function(){
        var obj  = {};
        url = "lib/get_excel_progress.php"; 
         ajax_excel_request(obj, url, render_excel_count);
    }, 1000);
    
}

function ajax_excel_request(variable_1d, variable_1e, variable_1f) {
    variable_1d["csrf_test_name"] = $["cookie"]("csrf_cookie_name");
    $["ajax"]({
        url: urljs + variable_1e,
        type: "post",
        dataType: "json",
        data: variable_1d,
        beforeSend: function() {
            
            $('.progress-bar').removeClass('bg-success');
            $('.progress-bar').addClass('bg-primary');
        },
        success: function(variable_1d) {
            variable_1f(variable_1d);
        },
        error: function() {}
    })
}

//$('.excel_progress').hide();      
function render_excel_count(str){
    var percent = str.percent.toFixed(2);   
    var texts = percent + " %";
        $('.progress-info .progress-label span').text(str.msgs[0]+" - "+ str.msgs[2]+"/"+str.msgs[1]);
        $('.progress-info .progress-percentage span').text(texts);              
        $('.progress-bar').css('width', percent + '%').attr('aria-valuenow', percent);
        $('.progress-bar').css('width', percent + '%')
    
     if (percent == 100) {
        $('.progress-info .progress-label span').text("Completed");
        delete_timer();
        $('.progress-bar').removeClass('bg-primary');
        $('.progress-bar').addClass('bg-success');
        $('.progress-bar').css('width', percent + '%')
        setTimeout(function(){ $('.excel_progress').html(''); },2000);
    }       
    
}

        
        
function delete_timer(){
    window.clearInterval(window.myTimer);
/*var obj  = {};
url = "admin/excelimport/cleardata";    
ajax_post_request(obj, url, render_excel_count);
*/
    
}

function download_sample(param){
    var dataType=$(param).attr('data-type');
    var fileurl='downloads/sample/'+dataType+'-sample-data.xlsx';
    window.location.href = urljs+fileurl;
}