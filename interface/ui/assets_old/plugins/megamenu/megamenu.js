	
			
			function mega_add_column(){
			grid.on("click", ".mega-add-column", function() {
                console.log('add column');
                var button = $(this);
                var row = button.parent().parent();
                var used_cols = parseInt(row.attr('data-used-cols'));
                var available_cols = parseInt(row.attr('data-available-cols'));

                row.find(".mega-row-is-full").hide();

                if ( used_cols + 1 > available_cols ) {
                    row.find(".mega-row-is-full").slideDown().delay(2000).slideUp();
                    return;
                }
                ajaxurl = urljs+"admin/categories/mega_menu_action";
                var space_left_on_row = available_cols - used_cols;

                var data = {
                    action: "mm_get_empty_grid_column",                   
                };

                $.post(ajaxurl, data, function(response) {
                    var column = $(response.data);
                    if (space_left_on_row < 3) {
                        column.attr('data-span', space_left_on_row);
                        column.find('.mega-num-cols').html(space_left_on_row);
                    }
                    button.parent().parent().append(column);
                    grid.trigger("make_columns_sortable");
                    grid.trigger("make_widgets_sortable");
                    //grid.trigger("save_grid_data");
					grid.trigger("make_rows_sortable");
                    grid.trigger("update_row_column_count");
                    grid.trigger("update_column_block_count");
                },"json");
            });
			
			}	
			
			grid.on("make_columns_sortable", function() {
                // sortable columns
                var rows = grid.find(".mega-row");

                rows.sortable({
                    connectWith: ".mega-row",
                    forcePlaceholderSize: false,
                    items: ".mega-col",
                    placeholder: "drop-area",
                    tolerance: "pointer",
                    handle: ".mega-col-header > .mega-col-description > .dashicons-move",
                    start: function(event, ui) {
                        ui.placeholder.height(ui.helper[0].scrollHeight);
                        ui.placeholder.width(ui.item.width() - 1);
                        $(".widget").removeClass("open");
                    },
                    sort: function(event, ui) {
                        grid.trigger("update_row_column_count");
                    },
                    stop: function(event, ui) {
                       // grid.trigger("save_grid_data");

                        // clean up
                        ui.item.removeAttr("style");

                        grid.trigger("update_row_column_count");
                    }
                });
            });
		
			
			grid.on("make_widgets_sortable", function() {
                // sortable widgets
                var cols = grid.find(".mega-col-widgets");

                cols.sortable({
                    connectWith: ".mega-col-widgets",
                    forcePlaceholderSize: true,
                    items: ".widget",
                    placeholder: "drop-area",
                    handle: ".widget-top",
                    helper: "clone",
                    tolerance: "pointer",
                    start: function(event, ui) {
                        $(".widget").removeClass("open");
                        ui.item.css("margin-top", $(window).scrollTop());

                    },
                    stop: function(event, ui) {
                        // clean up
                        ui.item.removeAttr("style");

                      //  grid.trigger("save_grid_data");
                        grid.trigger("update_column_block_count");
                    }
                });
            });



            ajaxurl = urljs+"admin/categories/mega_menu_action";
            grid.on("save_grid_data", function() {
               // start_saving();

                var rows = [];
                var cols = [];

                $(".mega-row", grid).each(function() {
                    var row_index = $(this).index();
                    var row_hide_on_desktop = $(this).find("input[name='mega-hide-on-desktop']").val();
                    var row_hide_on_mobile = $(this).find("input[name='mega-hide-on-mobile']").val();
                    var row_class = $(this).find("input.mega-row-class").val();
                    var row_columns = $(this).find("select.mega-row-columns").val();

                    rows[row_index] = {
                        "meta": {
                            "class": row_class,
                            "hide-on-desktop": row_hide_on_desktop,
                            "hide-on-mobile": row_hide_on_mobile,
                            "columns": row_columns
                        },
                        "columns": []
                    };
                });

                $(".mega-col", grid).each(function() {
                    var col_index = $(this).parent().children(".mega-col").index($(this));
                    var row_index = $(this).parent(".mega-row").index();
                    var col_span = $(this).attr("data-span");
                    var col_hide_on_desktop = $(this).find("input[name='mega-hide-on-desktop']").val();
                    var col_hide_on_mobile = $(this).find("input[name='mega-hide-on-mobile']").val();
                    var col_class = $(this).find("input.mega-column-class").val();

                    rows[row_index]["columns"][col_index] = {
                        "meta": {
                            "span": col_span,
                            "class": col_class,
                            "hide-on-desktop": col_hide_on_desktop,
                            "hide-on-mobile": col_hide_on_mobile
                        },
                        "items": []
                    };
                });

					$(".widget", grid).each(function() {
						var block_index = $(this).index();
						var id = $(this).attr("data-id");
						var type = $(this).attr("data-type");
						var row_index = $(this).closest(".mega-row").index();
						var col = $(this).closest(".mega-col");
						var col_index = col.parent().children(".mega-col").index(col);

						var widget = {
							"id": id,
							"type": type
						};

						rows[row_index]["columns"][col_index]["items"].push(widget);
					});
				$this = $('#save_mega_menu');
				var textclone = $this.html();
				button_loading($this);				
                $.post(ajaxurl, {
                    action: "mm_save_grid_data",
                    grid: rows,
                    parent_menu_item: $('#mm_parent_menu').val(),
                }, function(data) {
					
                    button_reset($this,textclone);
					
					 if (data.status == "success") {
					
					swal({type: 'success', title:"Success!",html: data.message});	
					
                }else{					
				
					swal({type: 'error', title:"Fail",html:data.message});	
				}
					
					
                },"json");

                grid.trigger("update_row_column_count");

            });






grid.on("update_row_column_count", function() {
                grid.trigger("update_total_columns_in_row");

                $(".mega-row", grid).each(function() {
					
                    var row = $(this);
				
                    var used_cols = 0;
                    var available_cols = row.attr("data-available-cols");

                    $(".mega-col", row).not(".ui-sortable-helper").each(function() {
                        var col = $(this);
                        used_cols = used_cols + parseInt(col.attr("data-span"), 10);
                    });

                    row.attr("data-used-cols", used_cols);

                    row.removeAttr("data-too-many-cols");
                    row.removeAttr("data-row-is-full");

                    if ( used_cols > available_cols ) {
                        row.attr("data-too-many-cols", "true");
                    }

                    if ( used_cols == available_cols ) {
                        row.attr("data-row-is-full", "true");
                    }
                });
            });
			
			grid.on("update_total_columns_in_row", function() {
                $(".mega-row", grid).each(function() {
                    var row = $(this);
                    var total_cols = $(this).find("select.mega-row-columns").val();
                    $(this).attr('data-available-cols', total_cols);
                    
                    $(".mega-col", row).not(".ui-sortable-helper").each(function() {
                        var col = $(this);
                        
                        $(this).find('.mega-num-total-cols').html(total_cols);
                    });
                });
            });
			
			
grid.on("update_column_block_count", function() {
                $(".mega-col", grid).each(function() {
                    var col = $(this);
                    col.attr("data-total-blocks", $(".mega-col-widgets > .widget", col).length);
                });
            });
			
			
			
			grid.on("click", ".mega-col-expand", function() {

                var column = $(this).closest(".mega-col");
                var cols = parseInt(column.attr("data-span"), 10);

                if (cols < 12) {
                    cols = cols + 1;

                    column.attr("data-span", cols);

                    $(".mega-num-cols", column).html(cols);

                   // grid.trigger("save_grid_data");
                    grid.trigger("update_row_column_count");
                }
            });

            // Contract Column
            grid.on("click", ".mega-col-contract", function() {

                var column = $(this).closest(".mega-col");

                var cols = parseInt(column.attr("data-span"), 10);

                if (cols > 1) {
                    cols = cols - 1;

                    column.attr("data-span", cols);

                    $(".mega-num-cols", column).html(cols);

                  //  grid.trigger("save_grid_data");
                    grid.trigger("update_row_column_count");
                }

            });
			
			
		grid.on("click", ".mega-col-description > .dashicons-trash", function() {
                $(this).closest(".mega-col").remove();

                //grid.trigger("save_grid_data");
                grid.trigger("update_row_column_count");
            });	
		
grid.on("click", ".mega-add-row", function() {
                var button = $(this);
                var data = {
                    action: "mm_get_empty_grid_row",
           
                };

                $.post(ajaxurl, data, function(response) {
                    var row = $(response.data);
                    button.before(row);

                    grid.trigger("make_columns_sortable");
                    grid.trigger("make_widgets_sortable");
                   // grid.trigger("save_grid_data");
                    grid.trigger("update_row_column_count");
                    grid.trigger("update_column_block_count");

                },"json");
            });		
			
			grid.on("make_rows_sortable", function() {
                // sortable row
                grid.sortable({
                    forcePlaceholderSize: true,
                    items: ".mega-row",
                    placeholder: "drop-area",
                    handle: ".mega-row-header > .mega-row-actions > .dashicons-sort",
                    tolerance: "pointer",
                    start: function(event, ui) {
                        $(".widget").removeClass("open");
                        ui.item.data("start_pos", ui.item.index());
                    },
                    stop: function(event, ui) {
                        // clean up
                        ui.item.removeAttr("style");

                        var start_pos = ui.item.data("start_pos");

                        if (start_pos !== ui.item.index()) {
                           // grid.trigger("save_grid_data");
                        }
                    }
                });
            });
			
			grid.on("click", ".mega-row-actions > .dashicons-trash", function() {
                $(this).closest(".mega-row").remove();

                //grid.trigger("save_grid_data");
            });
			
			grid.on("click", ".mega-row-header .dashicons-admin-generic", function() {
                $(this).toggleClass('mega-settings-open');
                $(this).closest(".mega-row").find(".mega-row-settings").slideToggle();
            });

grid.on("click", ".mega-save-column-settings, .mega-save-row-settings", function() {
                grid.trigger("save_grid_data");
            });