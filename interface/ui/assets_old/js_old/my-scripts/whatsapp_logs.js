//Vendors Challan History
function filterCustomers() {
    $(".filter").unbind().on("click", function(e) {
        e.preventDefault();
        var page = 1;
        $("#pagenumber").val(page);
        getCustomers(page);
    });
    $("#clearFilter").on("click",function(e){
        e.preventDefault();
        $('#adv-search').find('input.clearAbleFilt,select.clearAbleFilt').val('');
        $('#adv_filter_form').find('input.clearAbleFilt,select.clearAbleFilt').val('');
        $(".filter").click();
    });
    $("#page_result").unbind().on("click", ".pagination a", function(e) {
        e.preventDefault();
        var page = $(this).attr("data-page");
        $("#pagenumber").val(page);
        getCustomers(page);
    });
}

if($("#customersTbl").length>0){
    assignValueToFilter();    
    var pageNumber = (GetURLParameter("perpage"));
    getCustomers(pageNumber);
}
       
function getCustomers(page){
    ajaxloading('Loading...');
    var url="admin/whatsapp_logs/get_logs";
    ajax_filter(url, page, renderCustomers,false) 
}

function renderCustomers(datas) {
    $("#customersTbl").html(datas.str);
    filterCustomers();

}
