function prdLoader(texts){
    jQuery('#ajax_loadprd').html('<div class="load-prd"><div class="prd-box"><img src="'+urljs+'ui/loaders/throbber_12.gif" style="height:50px;margin: auto;"><div id="Ajaxprd_text"></div></div></div>');
    jQuery('#ajax_loadprd').css({
    'width': '100%',
    'height': '100px',
    'text-align':'center'
    });
    jQuery('#Ajaxprd_text').css({
        'padding-top': '10px'
    })
    jQuery('#Ajaxprd_text').html(texts);
}

function closeprdLoader(){
    jQuery('#ajax_loadprd').fadeOut(300);
}


deparamPrd = (function(d,x,params,pair,i) {
	return function (qs) {
		// start bucket; can't cheat by setting it in scope declaration or it overwrites
		params = {};
		// remove preceding non-querystring, correct spaces, and split
		qs = qs.substring(qs.indexOf('#')+1).replace(x,' ').split('&');
		// march and parse
		for (i = qs.length; i > 0;) {
			pair = qs[--i].split('=');
			params[d(pair[0])] = d(pair[1]);
		}

		return params;
	};//--  fn  deparam
})(decodeURIComponent, /\+/g);

function assignValueToPrdFilter(){
	var page = window.location.hash.substr(1);
	var q = deparamPrd(page);
	var msg = JSON.stringify(q);
	var result = $.parseJSON(msg);
	$.each(result, function(k, v) {
		var id = $('[data-type='+k+']').attr('data-id') ;

		$('#'+id).val(v);
	});
}
function emptyValueToPrdFilter(){
	var page = window.location.hash.substr(1);
	var q = deparamPrd(page);
	var msg = JSON.stringify(q);
	var result = $.parseJSON(msg);
	$.each(result, function(k, v) {
		var id = $('[data-type='+k+']').attr('data-id') ;
		$('#'+id).val("");
	});
}

function prdFilter(){
	$(".filter").on("click",function(e){
		e.preventDefault();
		var page=1;
		$("#pagenumber").val(page);
		getCommonProducts(page);
	});
	$(".prdorderby").on("change",function(e){
		e.preventDefault();
		var page=1;
		$("#pagenumber").val(page);
		getCommonProducts(page);
	});
	$(".priceFilter").on("click",function(e){
		e.preventDefault();
		var page=1;
		$("#pagenumber").val(page);
		getCommonProducts(page);
	});
}

function updatePriceFilter(rs) {
	$('#prd-minPrice').val(rs[0]); 
	$('#prd-maxPrice').val(rs[1]);
}

if($('#productsListTbl').length>0){
	getCommonProducts(1);
	addcart();
}
function getCommonProducts(page){
	ajaxloading('Please wait...Loading products...');	
	var refine_filter_arr = [];
	var parameter = [];
	$("#currentshopping ol").html('');
	jQuery('input.refine_filter,.refine_filter option:selected').each(function () {
		refine_filter_arr.push({'type':$(this).attr("data-type"),'value':$(this).val()});
		parameter.push({'name':$(this).attr('data-type'),'value':$(this).val()});
		if(jQuery(this).val()!=""){
			$("#currentshopping ol").append('<li><span class="label">' + $(this).attr('data-type') + ' : </span> <span class="value">'+ $(this).val() +'</span></li>');
		}
	});
	$("#currentshopping").show();

	var recursiveEncoded = $.param( parameter, true  );
	//alert(recursiveEncoded);
	//window.location.hash = recursiveEncoded;
	$.post(urljs+"products/get_products",{"page":page,'filter_data':refine_filter_arr,},function(data){
		if(data.str!=''){
			$("#productsListTbl").html(data.str);
			$(".prd_pagination").html(data.paginate);
			$(".prd_counts").html(data.counts);
			quickview_prd();
			addcart();
			prdFilter();
			prdPagination();
			closeajax();
			remove_filters();
		}
	},"json");
}


function prdPagination(){
	$(".prd_pagination").on( "click",".pagination a", function (e){
		e.preventDefault();
	
		var page = $(this).attr("data-page");
		$("#pagenumber").val(page);
		window.location = "#"+page;
		getCommonProducts(page);
	});

}

function remove_filters() {
	jQuery(".btn-rm-filter").on("click", function () {
		var url = document.URL;
		var key = jQuery(this).attr('data-type'); var val = jQuery(this).attr('data-value');
		var cururl = removeURLParameter(url, key,val)
		window.location.href= cururl;
	});
}

function removeURLParameter(url, key, val) {
	//prefer to use l.search if you have a location/link object
	var urlparts= url.split('?');
	if (urlparts.length>=2) {

		var prefix= encodeURIComponent(key)+'='+val;

		var pars= urlparts[1].split(/[&;]/g);
		//reverse iteration as may be destructive
		for (var i= pars.length; i-- > 0;) {
			//idiom for string.startsWith
			if (pars[i].lastIndexOf(prefix, 0) !== -1) {
				pars.splice(i, 1);
			}
		}

		url= urlparts[0]+'?'+pars.join('&');
		return url;
	} else {
		return url;
	}
}