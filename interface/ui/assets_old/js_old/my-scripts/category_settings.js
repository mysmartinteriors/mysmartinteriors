//menu settings
if ($("#megamenu-grid").length > 0) {
    load_menu_structure();
}

function load_menu_structure(){
    ajaxloading('Loading...Please wait...');
    var action='mm_get_mega_menu_column';
    $.post(urljs+"admin/categories/mega_menu_action",{'action':action},function(data){
        closeajax();
        renderdata(data);
    },"json");
}

function mega_menu_status(){
    $('#mega_menu_status').unbind().click(function() {
        var obj = {};
        //obj['parent_menu'] = $('#mm_parent_menu').val();
        obj['mega_menu_status'] = $('#mega_menu_status').val();
        obj['action'] = 'mega_menu_status';
        $.post(urljs+"admin/categories/mega_menu_action",obj,function(data){
            if (data.status == "success") {
                $('#mega_menu_status').val(data.result.mega_val);
                swal_alert('success',data.msg,'success','');
            }else{
                swal({type: 'error', title:"Fail",html:data.msg});  
            }
        },"json");
    });
}

function save_mega_menu_data(){
    $('#save_mega_menu').unbind().click(function() {
        grid.trigger("save_grid_data");
    });
}

function renderdata(data) {
    if (data.success == "false") {
        $('.mega-row-header').hide();
        $( "#megamenu-grid" ).html( data.data);
    }else{
        $( "#megamenu-grid" ).html( data.data);
        mega_add_column();
        mega_menu_status();
        save_mega_menu_data();
        grid.trigger("make_columns_sortable");
        grid.trigger("make_rows_sortable");
        grid.trigger("make_widgets_sortable");
        grid.trigger("update_row_column_count");
        grid.trigger("update_column_block_count");
    }
}

var grid = $("#megamenu-grid");