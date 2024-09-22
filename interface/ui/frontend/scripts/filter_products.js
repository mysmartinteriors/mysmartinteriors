function deparam(params, coerce) {
	var obj = {},
		coerce_types = { 'true': !0, 'false': !1, 'null': null };

	// Iterate over all name=value pairs.
	$.each(params.replace(/\+/g, ' ').split('&'), function (j, v) {
		var param = v.split('='),
			key = decodeURIComponent(param[0]),
			val,
			cur = obj,
			i = 0,

			// If key is more complex than 'foo', like 'a[]' or 'a[b][c]', split it
			// into its component parts.
			keys = key.split(']['),
			keys_last = keys.length - 1;

		// If the first keys part contains [ and the last ends with ], then []
		// are correctly balanced.
		if (/\[/.test(keys[0]) && /\]$/.test(keys[keys_last])) {
			// Remove the trailing ] from the last keys part.
			keys[keys_last] = keys[keys_last].replace(/\]$/, '');

			// Split first keys part into two parts on the [ and add them back onto
			// the beginning of the keys array.
			keys = keys.shift().split('[').concat(keys);

			keys_last = keys.length - 1;
		} else {
			// Basic 'foo' style key.
			keys_last = 0;
		}

		// Are we dealing with a name=value pair, or just a name?
		if (param.length === 2) {
			val = decodeURIComponent(param[1]);

			// Coerce values.
			if (coerce) {
				val = val && !isNaN(val) ? +val              // number
					: val === 'undefined' ? undefined         // undefined
						: coerce_types[val] !== undefined ? coerce_types[val] // true, false, null
							: val;                                                // string
			}

			if (keys_last) {
				// Complex key, build deep object structure based on a few rules:
				// * The 'cur' pointer starts at the object top-level.
				// * [] = array push (n is set to array length), [n] = array if n is
				//   numeric, otherwise object.
				// * If at the last keys part, set the value.
				// * For each keys part, if the current level is undefined create an
				//   object or array based on the type of the next keys part.
				// * Move the 'cur' pointer to the next level.
				// * Rinse & repeat.
				for (; i <= keys_last; i++) {
					key = keys[i] === '' ? cur.length : keys[i];
					cur = cur[key] = i < keys_last ? cur[key] || (keys[i + 1] && isNaN(keys[i + 1]) ? {} : []) : val;
				}

			} else {
				// Simple key, even simpler rules, since only scalars and shallow
				// arrays are allowed.

				if ($.isArray(obj[key])) {
					// val is already an array, so push on the next value.
					obj[key].push(val);

				} else if (obj[key] !== undefined) {
					// val isn't an array, but since a second value has been specified,
					// convert val into an array.
					obj[key] = [obj[key], val];

				} else {
					// val is a scalar.
					obj[key] = val;
				}
			}

		} else if (key) {
			// No value was defined, so set something meaningful.
			obj[key] = coerce ? undefined : '';
		}
	});

	return obj;
}

(function () {
	'use strict';
	var queryString = {};

	queryString.parse = function (str) {
		if (typeof str !== 'string') {
			return {};
		}

		str = str.trim().replace(/^\?/, '');

		if (!str) {
			return {};
		}

		return str.trim().split('&').reduce(function (ret, param) {
			var parts = param.replace(/\+/g, ' ').split('=');
			var key = parts[0];
			var val = parts[1];

			key = decodeURIComponent(key);
			// missing `=` should be `null`:
			// http://w3.org/TR/2012/WD-url-20120524/#collect-url-parameters
			val = val === undefined ? null : decodeURIComponent(val);

			if (!ret.hasOwnProperty(key)) {
				ret[key] = val;
			} else if (Array.isArray(ret[key])) {
				ret[key].push(val);
			} else {
				ret[key] = [ret[key], val];
			}

			return ret;
		}, {});
	};

	queryString.stringify = function (obj) {
		return obj ? Object.keys(obj).map(function (key) {
			var val = obj[key];

			if (Array.isArray(val)) {
				return val.map(function (val2) {
					return encodeURIComponent(key) + '=' + encodeURIComponent(val2);
				}).join('&');
			}

			return encodeURIComponent(key) + '=' + encodeURIComponent(val);
		}).join('&') : '';
	};

	queryString.push = function (key, new_value) {
		var params = queryString.parse(location.search);
		params[key] = new_value;
		var new_params_string = queryString.stringify(params)
		history.pushState({}, "", window.location.pathname + '?' + new_params_string);
	}

	if (typeof module !== 'undefined' && module.exports) {
		module.exports = queryString;
	} else {
		window.queryString = queryString;
	}
})();

function doRefineFilter() {
	$(".refine_filter").on("change", function () {
		common_refine_filter();
	});
}


if ($("#productsListTbl").length > 0) {
	price_slider();
	assignValueToFilter();
	doRefineFilter();

}

function assignValueToFilter() {
	//var page = window.location.hash.substr(1);
	var page = document.URL.split("?");

	page = page[1];

	if (page != undefined) {

		var q = deparam(page);

		var msg = JSON.stringify(q);
		var result = $.parseJSON(msg);

		$.each(result, function (k, v) {
			if (typeof v == 'object') {
				v = v;
			} else {
				v = $.makeArray(v);
			}
			for (var a = 0; a < v.length; a++) {
				$('[data-type=' + k + ']').each(function () {
					// console.log(this);
					// log
					// console.table(this)
					// console.log(v);
					var val = $(this).val();
					// console.log(val);
					// console.log(jQuery.inArray(val, v));
					if (jQuery.inArray(val, v) >= 0) {
						$(this).prop("checked", true);
						$(this).attr("selected", true);
					}
				});
			}

		});

		//var carat = GetURLParameter("prd_diamondcarat");
		var search = GetURLParameter("q");
	}
	if (search != undefined) {
		$('#search').val(search); $('#prd-search').val(search);
	}
	/* $(".slider-range").slider('option',"value", [0,3]);
	if(carat!=undefined) {
		var splitcarat = carat.split("-");

			$(".slider-range").slider('values', splitcarat[1], splitcarat[0]);
		$("#minDiamond").val(splitcarat[0]);
		$("#maxDiamond").val(splitcarat[1]);
	} */
	common_refine_filter();
}

function price_slider() {
	var newMinDiamond, newMaxDiamond, url, temp;
	var categoryMinPrice = 0;
	var categoryMaxPrice = jQuery("#maxamount").val();
	$(".priceFilter").val('Rs.' + categoryMinPrice + " - Rs." + categoryMaxPrice);

	function isNumber(n) {
		return !isNaN(parseFloat(n)) && isFinite(n);
	}

	jQuery(".priceTextBox").focus(function () {
		temp = jQuery(this).val();
	});

	jQuery(".priceTextBox").keyup(function () {
		var value = jQuery(this).val();
		if (value != "" && !isNumber(value)) {
			jQuery(this).val(temp);
		}
	});

	jQuery(".priceTextBox").keypress(function (e) {
		if (e.keyCode == 13) {
			var value = jQuery(this).val();
			if (value < categoryMinPrice || value > categoryMaxPrice) {
				jQuery(this).val(temp);
			}
			var minPrice = jQuery(".minPrice").val();
			var maxPrice = jQuery(".maxPrice").val();
			jQuery(".priceFilter").val('Rs.' + minPrice + " - Rs." + maxPrice);
			filteration(1);
		}
	});

	jQuery(".priceTextBox").blur(function () {
		var value = jQuery(this).val();
		if (value < categoryMinPrice || value > categoryMaxPrice) {
			jQuery(this).val(temp);
		}

	});

	jQuery(".price-slide-range").slider({
		range: true,
		min: categoryMinPrice,
		max: categoryMaxPrice,
		values: [0, jQuery("#maxamount").val()],
		slide: function (event, ui) {
			newMinDiamond = ui.values[0];
			newMaxDiamond = ui.values[1];

			jQuery(".priceFilter").val('Rs.' + newMinDiamond + " - Rs." + newMaxDiamond);


			// Update TextBox Price
			jQuery(".minPrice").val(newMinDiamond);
			jQuery(".maxPrice").val(newMaxDiamond);

		},
		stop: function (event, ui) {

			// Current Min and Max Price
			var newMinDiamond = ui.values[0];
			var newMaxDiamond = ui.values[1];

			// Update Text Price
			jQuery(".priceFilter").val('Rs.' + newMinDiamond + " - Rs." + newMaxDiamond);

			filteration(1);

		}
	});
}


function emptyValueToFilter() {
	var page = document.URL.split("?");
	var page = page[1];
	var q = deparam(page);
	var msg = JSON.stringify(q);
	var result = $.parseJSON(msg);
	$.each(result, function (k, v) {
		var id = $('[data-type=' + k + ']').attr('id');
		$('#' + id).val("");
	});
}

function GetURLParameter(sParam) {
	var page = document.URL.split("?");
	var sPageURL = page[1];
	if (sPageURL != undefined) {
		//var sPageURL = window.location.hash.substring(1);
		var sURLVariables = sPageURL.split('&');
		for (var i = 0; i < sURLVariables.length; i++) {
			var sParameterName = sURLVariables[i].split('=');
			if (sParameterName[0] == sParam) {
				return sParameterName[1];
			}
		}
	}
}

function removeURLParameter(url, key, val) {
	//prefer to use l.search if you have a location/link object
	val = val.replace(/\ /g, '+');
	var urlparts = url.split('?');
	if (urlparts.length >= 2) {

		var prefix = encodeURIComponent(key) + '=' + val;

		var pars = urlparts[1].split(/[&;]/g);
		//reverse iteration as may be destructive
		for (var i = pars.length; i-- > 0;) {
			//idiom for string.startsWith
			if (pars[i].lastIndexOf(prefix, 0) !== -1) {
				pars.splice(i, 1);
			}
		}

		url = urlparts[0] + '?' + pars.join('&');
		return url;
	} else {
		return url;
	}
}

function common_refine_filter() {
	filteration(1);
}

function filteration(page) {
	$("#currentshopping ul").empty();
	var parameter = [];
	var refine_filter_arr = [];
	$('input.refine_filter:checkbox:checked,input.refine_filter:radio:checked,.refine_filter option:selected,.refine_pfilter option:selected,#prd-search,.span2,#minamount,#maxamount').each(function () {
		// console.log(this);
		if ($(this).val() != "") {
			var dtype = $(this).attr("data-type")
			var value = $(this).val();
			var kk = $(this).attr('data-name');
			var name = value;
			// console.table([dtype, value, kk, name]);
			if (dtype == "q" && value != '') { kk = "Search"; name = value }
			if (dtype == "min-price") { kk = "Min-Price"; name = 'Rs.' + value }
			if (dtype == "max-price") { kk = "Max-Price"; name = 'Rs.' + value }
			if (dtype == "cat_type") { name = $(this).attr('data-text'); }
			if (dtype == "scat_type") { name = $(this).attr('data-text'); }
			if (kk != "" && value != '') {
				$("#currentshopping ul").append('<li class="btn-fltRmv" data-type="' + $(this).attr('data-type') + '" data-value="' + $(this).val() + '"><span class="filterSpan"><span class="label"> ' + kk + ' : </span> <span class="value"> ' + name + '</span></span></li>');
			}
			refine_filter_arr.push({ 'type': dtype, 'value': value });
			parameter.push({ 'name': dtype, 'value': value });
		}
	});

	var recursiveEncoded = "?";
	recursiveEncoded += $.param(parameter, true);
	// console.log(recursiveEncoded);
	history.pushState("", "", recursiveEncoded);
	//jQuery.param.querystring(window.location.href,recursiveEncoded)
	//window.location.hash= recursiveEncoded;
	if (refine_filter_arr.length === 0) {
		$("#currentshopping").hide();
	} else {
		$("#currentshopping").show();
	}
	ajaxloading('Loading products...');
	$.post(urljs + "products/get_products", { 'page': page, "filter_data": refine_filter_arr }, function (data) {
		closeajax();
		// console.log(data);
		$("#productsListTbl").html(data.str);
		// console.log(data.paginate);
		// $("#page_result").html(data.paginate);
		// $("#result").html(data.paginate);

		// $(".prd_counts").html(data.counts);
		quickview_prd();
		addcart();
		doRefineFilter();
		filter_pagination();
		remove();
		clearallshopping();

	}, 'json');

}

$("#result").unbind().on("click", ".pagination a", function(e) {
	console.log("HELLO");
	e.preventDefault();
	var page = $(this).attr("data-page");
	$("#pagenumber").val(page);
	filteration(page);
});

function filter_pagination() {
	$("#result").on("click", ".pagination a", function (e) {
		// console.log("HERE");
		e.preventDefault();
		var page = $(this).attr("data-page");
		console.log(page);
		//window.location = "#"+page;
		filteration(page);
	});

}

function clearallshopping() {
	jQuery(".clearallshopping").on("click", function () {
		var url = document.URL;
		var urlparts = url.split('?');
		window.location.href = urlparts[0];
	});
}
function remove() {
	jQuery(".btn-fltRmv").on("click", function () {
		var url = document.URL;
		var key = jQuery(this).attr('data-type'); var val = jQuery(this).attr('data-value');
		var cururl = removeURLParameter(url, key, val)
		window.location.href = cururl;
	});
}

function goToByScroll(id) {
	// Remove "link" from the ID
	id = id.replace("link", "");
	// Scroll
	jQuery('html,body').animate({
		scrollTop: jQuery(id).offset().top
	},
		'slow');
}