(function($) {

	$(document).ready(function($){

		/**
		 * Initialize Gridster.js
		 */
		var grid = function () {
			var gridster_size       = $( '.gridster' ).width();
			var gridster_cols       = $( '.gridster' ).attr( 'data-columns' );
			var gridster_max_size_x = $( '.gridster' ).attr( 'data-max-size-x' );
			var gridster_col_width  = Math.floor( gridster_size / gridster_cols ) - ( ( gridster_cols - 1 ) * 5 );
			var gridster_col_height = $( '.gridster ul li' ).height();

			$( '.gridster ul' ).gridster({
				widget_base_dimensions: [ gridster_col_width, gridster_col_height ],
				widget_margins: [ 5, 5 ],
				min_cols: gridster_cols,
				resize: {
					enabled: true,
					max_size: [ gridster_max_size_x, 1 ],
					min_size: [ 1, 1 ],
					stop: function( e, ui, $widget ) {
						var gridster_data = serialize_grid( '.gridster ul li' );
						store_grid(gridster_data);
					}
				},
				draggable: {
					stop: function( e, ui, $widget ) {
						var gridster_data = serialize_grid( '.gridster ul li' );
						store_grid( gridster_data );
					}
				}
			});

			/*
			 * Stores current grid properties. This is important as grid is
			 * otherwise is not saved when users picks different layout
			 * option.
			 */
			setTimeout(function() {
				var gridster_data = serialize_grid( '.gridster ul li' );
				store_grid( gridster_data );
			}, 1000);

		}();

		/**
		 * Parses gridster widget data and returns simplified/useful array.
		 * @param {Array} selector
		 * @return {Array} data
		 */
		var serialize_grid = function( selector ){
			var data = new Array();

			if ( $( '.gridster ul' ).parent().length !== 0 ) {
				var gridster_data = $( '.gridster ul' ).data('gridster').serialize();
				$.each(gridster_data, function(index, value){
					var widget = $('li[data-row="' + value.row + '"][data-col="' + value.col + '"][data-sizex="' + value.size_x + '"] div');

					var tmp = {
						row: value.row,
						col: value.col,
						sizex: value.size_x,
						field_id: widget.attr( 'data-field-id' )
					};

					data.push( tmp );
				});
			}

			return data;
		};

		/**
		 * Executes AJAX request to store current widget layout
		 * @param {Array} gridster_data
		 */
		var store_grid = function( gridster_data ) {
			var data = {
				action: 'bti_layout_master_update_fields',
				gridster_data: gridster_data
			};

			$.post( ajaxurl, data, function( response ) {} );
		};

		/**
		 * Initialize iris color picker for picker fields
		 */
		$( '.bti-layer-master-color' ).wpColorPicker();

		/**
		 * Store info about open metaboxes.
		 */
		$( '.ninja-forms-save-data' ).on( 'click', function() {
			if ( $('.ninja_forms_style_metaboxes' ).parent().length !== 0 ) {
				var open = new Array();

				// Find all open tabs
				$('.ninja_forms_style_metaboxes>div').each(function(index){
					if ( $(this).find('.inside').css('display') == 'block' ) {
						open.push($(this).attr('id'));
					}
				});

				// Create TMP cookie
				if ( open.length > 0 ) {
					create_cookie( 'nf_lm_session', open );
				}
			}
		});

		var create_cookie = function(name, value) {
			var expires;
			var date = new Date();
			date.setTime( date.getTime() + ( 60 * 1000 ) );
			expires = "; expires=" + date.toGMTString();
			document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
		}

	});

})(jQuery);
