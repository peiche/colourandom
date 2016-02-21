<?php
/**
 * ColouRandom Customizer functionality
 *
 * @package WordPress
 * @subpackage ColouRandom
 * @since ColouRandom 1.0
 */

/**
 * Adds postMessage support for site title and description for the Customizer.
 *
 * @since ColouRandom 1.0
 *
 * @param WP_Customize_Manager $wp_customize The Customizer object.
 */
function colourandom_customize_register( $wp_customize ) {
	$wp_customize->add_setting( 'randomize_color_scheme', array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'colourandom_sanitize_checkbox',
	) );

	$wp_customize->add_control(
		'randomize_color_scheme',
		array(
			'label'    => __( 'Randomize Color Scheme', 'colourandom' ),
			'section'  => 'colors',
			'settings' => 'randomize_color_scheme',
			'type'     => 'checkbox',
			'priority' => 1,
		)
	);
}
add_action( 'customize_register', 'colourandom_customize_register', 11 );

/**
 * Binds the JS listener to make Customizer color_scheme control.
 *
 * Passes color scheme data as colorScheme global.
 *
 * @since ColouRandom 1.0
 */
function colourandom_customize_control_js() {
	wp_enqueue_script( 'color-scheme-control', get_stylesheet_directory_uri() . '/js/color-scheme-control.js', array( 'customize-controls', 'iris', 'underscore', 'wp-util' ), '20150926', true );
	wp_localize_script( 'color-scheme-control', 'colorScheme', twentysixteen_get_color_scheme() );
}
add_action( 'customize_controls_enqueue_scripts', 'colourandom_customize_control_js' );

function colourandom_customize_css() {
	?>
	<style>
	#customize-control-randomize_color_scheme label {
		position: relative;
		display: inline-block;
    text-decoration: none;
    font-size: 13px;
    line-height: 26px;
    height: 28px;
    margin: 0;
    padding: 0 10px 1px;
    cursor: pointer;
    border-width: 1px;
    border-style: solid;
    -webkit-appearance: none;
    -webkit-border-radius: 3px;
    border-radius: 3px;
    white-space: nowrap;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    color: #555;
    border-color: #ccc;
    background: #f7f7f7;
    -webkit-box-shadow: 0 1px 0 #ccc;
    box-shadow: 0 1px 0 #ccc;
    vertical-align: top;
	}
	#customize-control-randomize_color_scheme label.dashicons-before::before {
		position: absolute;
		left: 100%;
		margin-left: 10px;
		margin-top: 3px;
	}
	#customize-control-randomize_color_scheme input[type="checkbox"] {
		display: none;
	}
	.dashicons-before.dashicons-spin::before {
		-webkit-animation: spin 2s infinite linear;
		animation: spin 2s infinite linear;
	}
	@-webkit-keyframes spin {
	  0% {
	    -webkit-transform: rotate(0deg);
	    transform: rotate(0deg);
	  }
	  100% {
	    -webkit-transform: rotate(359deg);
	    transform: rotate(359deg);
	  }
	}
	@keyframes spin {
	  0% {
	    -webkit-transform: rotate(0deg);
	    transform: rotate(0deg);
	  }
	  100% {
	    -webkit-transform: rotate(359deg);
	    transform: rotate(359deg);
	  }
	}
	</style>
	<?php
}
add_action('customize_controls_print_styles', 'colourandom_customize_css' );

/**
 * Checkbox sanitization callback example.
 *
 * Sanitization callback for 'checkbox' type controls. This callback sanitizes `$checked`
 * as a boolean value, either TRUE or FALSE.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
function colourandom_sanitize_checkbox( $checked ) {
	// Boolean check.
	return ( ( isset( $checked ) && true == $checked ) ? true : false );
}
