(function( $, document ) {

	var ajax = {

		cache: function() {
			ajax.vars = {};
			ajax.els = {};

			ajax.vars.count = [];
			ajax.vars.index_settings = {};

			ajax.els.process_overlay = $( '.process-overlay' );
			ajax.els.process = $( '.process' );
			ajax.els.process_content = $( '.process__content--processing' );
			ajax.els.process_loading = $( '.process__content--loading' );
			ajax.els.process_complete = $( '.process__content--complete' );
			ajax.els.process_from = $( '.process__count-from' );
			ajax.els.process_to = $( '.process__count-to' );
			ajax.els.process_total = $( '.process__count-total' );
			ajax.els.process_loading_bar_fill = $( '.process__loading-bar-fill' );
		},

		on_ready: function() {
			ajax.cache();
			ajax.watch_triggers();
		},

		/**
		 * Watch AJAX triggers.
		 */
		watch_triggers: function() {
			$( '[data-iconic-wssv-ajax]' ).on( 'click', function() {
				var action = $( this ).data( 'iconic-wssv-ajax' );

				if ( null == ajax[ action ] ) {
					return false;
				}

				ajax[ action ].run();
			} );

			$( '.process__close' ).on( 'click', function() {
				ajax.process.hide();

				if ( $( this ).data( 'reload' ) ) {
					location.reload();
				}
			} );

			$( document.body ).on( 'click', '[data-iconic-wssv-process-screen]', function( e ) {
				e.preventDefault();

				var type = $( this ).data( 'iconic-wssv-process-screen' );

				ajax.process.show( type );

				$( document.body ).trigger( 'iconic_wssv_trigger_process_' + type );
			} );

			$( document.body ).on( 'iconic_wssv_trigger_process_start', function() {
				ajax.process.show( 'loading' );
				ajax.process_product_visibility.start();
			} );
		},

		/**
		 * Process product visibility.
		 */
		process_product_visibility: {
			run: function() {
				ajax.process.show( 'open' );
			},

			/**
			 * Start indexing products.
			 */
			start: function() {
				var limit = 10,
					options = ajax.process_product_visibility.get_settings();

				ajax.process_product_visibility.clear_settings();

				ajax.get_count( 'product', function( count ) {
					ajax.process.update_count( 1, limit, count );
					ajax.process.show( 'processing' );

					ajax.batch( 'process_product_visibility', count, limit, 0, function( processing, new_offset ) {
						if ( !processing ) {
							ajax.process.show( 'complete' );
							ajax.process.set_percentage( count, count );
						} else {
							var to = new_offset + limit;

							to = to >= count ? count : to;

							ajax.process.update_count( new_offset, to, count );
							ajax.process.set_percentage( new_offset, count );
						}
					}, options );
				} );
			},

			/**
			 * CLear settings.
			 */
			clear_settings: function() {
				ajax.vars.index_settings = {};
			},

			/**
			 * Get process settings.
			 *
			 * @returns {{}}
			 */
			get_settings: function() {
				if ( ! $.isEmptyObject( ajax.vars.index_settings ) ) {
					return ajax.vars.index_settings;
				}

				var $forms = $( '.process__form' ),
					fields = $forms.serializeArray();

				if ( fields.length <= 0 ) {
					return ajax.vars.index_settings;
				}

				$.each( fields, function( index, field_data ) {
					if ( '' === field_data.value ) {
						return;
					}

					var field_type = typeof ajax.vars.index_settings[ field_data.name ];

					if ( 'undefined' === field_type ) {
						ajax.vars.index_settings[ field_data.name ] = field_data.value;
					} else if ( 'object' === field_type ) {
						ajax.vars.index_settings[ field_data.name ].push( field_data.value );
					} else {
						var current_value = ajax.vars.index_settings[ field_data.name ];

						ajax.vars.index_settings[ field_data.name ] = [];
						ajax.vars.index_settings[ field_data.name ].push( current_value );
						ajax.vars.index_settings[ field_data.name ].push( field_data.value );
					}
				} );

				return ajax.vars.index_settings;
			}
		},

		/**
		 * Process modal.
		 */
		process: {
			/**
			 * Show.
			 */
			show: function( type ) {
				type = typeof type === "undefined" ? "content" : type;

				ajax.els.process_overlay.show();
				ajax.els.process.show();

				$( '.process__content' ).hide();
				$( '.process__content--' + type ).show();

				$( document.body ).trigger( 'iconic_wssv_show_process_' + type );
			},

			/**
			 * Hide.
			 */
			hide: function() {
				ajax.els.process_overlay.hide();
				ajax.els.process.hide();
				ajax.els.process_loading.show();
				ajax.els.process_complete.hide();
				ajax.els.process_content.hide();
				ajax.process.reset_percentage();

				$( document.body ).trigger( 'iconic_wssv_hide_process' );
			},

			/**
			 * Update count.
			 *
			 * @param int count_from
			 * @param int count_to
			 * @param int count_total
			 */
			update_count: function( count_from, count_to, count_total ) {
				ajax.els.process_from.text( count_from );
				ajax.els.process_to.text( count_to );
				ajax.els.process_total.text( count_total );
			},

			/**
			 * Set percentage.
			 *
			 * @param int complete
			 * @param int total
			 */
			set_percentage: function( complete, total ) {
				var percentage = (complete / total) * 100;

				ajax.els.process_loading_bar_fill.css( 'width', percentage + '%' );
			},

			/**
			 * Reset percentage.
			 */
			reset_percentage: function() {
				ajax.els.process_loading_bar_fill.css( 'width', '0%' );
			}

		},

		/**
		 * Batch process.
		 */
		batch: function( action, total, limit, offset, callback, options ) {
			options = options || {};

			var processing = true,
				data = {
					'action': 'iconic_wssv_' + action,
					'iconic_wssv_limit': limit,
					'iconic_wssv_offset': offset
				};

			$.extend( data, options );

			$.post( ajaxurl, data, function( response ) {
				var new_offset = offset + limit;

				if ( new_offset < total ) {
					ajax.batch( action, total, limit, new_offset, callback, options );
				} else {
					processing = false;
				}

				if ( typeof callback === 'function' ) {
					callback( processing, new_offset );
				}
			} );
		},

		/**
		 * Get count of products.
		 *
		 * @return int
		 */
		get_count: function( type, callback ) {
			if ( null != ajax.vars.count[ type ] ) {
				if ( typeof callback === 'function' ) {
					callback( ajax.vars.count[ type ] );
				}
				return;
			}

			var data = {
				'action': 'iconic_wssv_get_' + type + '_count'
			};

			jQuery.post( ajaxurl, data, function( response ) {

				if ( typeof callback === 'function' ) {
					callback( response.count );
				}

				ajax.vars.count[ type ] = response.count;

			} );

			return;
		}

	};

	$( document ).ready( ajax.on_ready() );

}( jQuery, document ));