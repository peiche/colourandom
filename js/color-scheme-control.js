/* global colorScheme, Color */
/**
 * Add a listener to the Color Scheme control to update other color controls to new values/defaults.
 * Also trigger an update of the Color Scheme CSS when a color is changed.
 */

( function( api ) {
	var cssTemplate = wp.template( 'twentysixteen-color-scheme' ),
		colorSchemeKeys = [
			'background_color',
			'page_background_color',
			'link_color',
			'main_text_color',
			'secondary_text_color'
		],
		colorSettings = [
			'background_color',
			'page_background_color',
			'link_color',
			'main_text_color',
			'secondary_text_color'
		],
		randomSettings = [
			'randomize_color_scheme'
		];

	_.each( randomSettings, function( setting ) {
		api( setting, function( setting ) {
			setting.bind( function( value ) {

				var loadingClasses = 'dashicons-before dashicons-update dashicons-spin';

				var $label = jQuery('#customize-control-randomize_color_scheme label');
				$label.addClass(loadingClasses);

				// get random color scheme in top 200 picks from ColourLovers
				var rand = Math.floor(Math.random() * (200 - 1)) + 1;
				var url = 'http://www.colourlovers.com/api/palettes/top?format=json&numResults=1&jsonCallback=_callback&resultOffset=' + rand;
				jQuery.ajax({
					url: url,
					crossDomain: true,
					dataType: 'jsonp',
					jsonpCallback: '_callback',
					complete: function(xhr, status) {
						$label.removeClass(loadingClasses)
					},
					success: function(data, status, xhr) {
						if (data.length != undefined && data.length > 0) {
							if ( data[0].colors != undefined ) {
								var colors = data[0].colors;
								parseColorArray(colors);
							}
						}
					},
					error: function(xhr, status, error) {
						alert('There was a problem getting a color palette.');
					}
				});

			} );
		} );
	} );

	api.controlConstructor.select = api.Control.extend( {
		ready: function() {
			if ( 'color_scheme' === this.id ) {
				this.setting.bind( 'change', function( value ) {
					var colors = colorScheme[value].colors;
					parseColorArray(colors);
				} );
			}
		}
	} );

	// Generate the CSS for the current Color Scheme.
	function updateCSS() {
		var scheme = api( 'color_scheme' )(),
			css,
			colors = _.object( colorSchemeKeys, colorScheme[ scheme ].colors );

		// Merge in color scheme overrides.
		_.each( colorSettings, function( setting ) {
			colors[ setting ] = api( setting )();
		} );

		// Add additional color.
		// jscs:disable
		colors.border_color = Color( colors.main_text_color ).toCSS( 'rgba', 0.2 );
		// jscs:enable

		css = cssTemplate( colors );

		api.previewer.send( 'update-color-scheme-css', css );
	}

	// Update the CSS whenever a color setting is changed.
	_.each( colorSettings, function( setting ) {
		api( setting, function( setting ) {
			setting.bind( updateCSS );
		} );
	} );

	function parseColorArray(colors) {
		// Update Background Color.
		var color = colors[0];
		api( 'background_color' ).set( color );
		api.control( 'background_color' ).container.find( '.color-picker-hex' )
			.data( 'data-default-color', color )
			.wpColorPicker( 'defaultColor', color );

		// Update Page Background Color.
		color = colors[1];
		api( 'page_background_color' ).set( color );
		api.control( 'page_background_color' ).container.find( '.color-picker-hex' )
			.data( 'data-default-color', color )
			.wpColorPicker( 'defaultColor', color );

		// Update Link Color.
		color = colors[2];
		api( 'link_color' ).set( color );
		api.control( 'link_color' ).container.find( '.color-picker-hex' )
			.data( 'data-default-color', color )
			.wpColorPicker( 'defaultColor', color );

		// Update Main Text Color.
		color = colors[3];
		api( 'main_text_color' ).set( color );
		api.control( 'main_text_color' ).container.find( '.color-picker-hex' )
			.data( 'data-default-color', color )
			.wpColorPicker( 'defaultColor', color );

		// Update Secondary Text Color.
		color = colors[4];
		api( 'secondary_text_color' ).set( color );
		api.control( 'secondary_text_color' ).container.find( '.color-picker-hex' )
			.data( 'data-default-color', color )
			.wpColorPicker( 'defaultColor', color );
	}

} )( wp.customize );
