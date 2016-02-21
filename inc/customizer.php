<?php
/**
 * ColouRandom Customizer functionality
 *
 * @package WordPress
 * @subpackage ColourLovers
 * @since ColourLovers 1.0
 */

/**
 * Adds postMessage support for site title and description for the Customizer.
 *
 * @since ColouRandom 1.0
 *
 * @param WP_Customize_Manager $wp_customize The Customizer object.
 */
	$color_scheme = twentysixteen_get_color_scheme();
	
	class Customize_Button_Control extends WP_Customize_Control {
		public $type = 'button';
	 
		public function render_content() {
			?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<button type="button">Go</button>
			</label>
			<?php
		}
	}
	
function colourandom_customize_register( $wp_customize ) {
	$wp_customize->add_setting( 'randomize_color_scheme', array(
		//'default'        => $color_scheme,
		'transport'      => 'postMessage',
	) );
	
	/*
	$wp_customize->add_control( new Customize_Button_Control( $wp_customize, 'randomize_color_scheme', array(
		'label'   => __( 'Randomize Color Scheme', 'colourlovers' ),
		'section' => 'colors',
		'settings'   => 'randomize_color_scheme',
		'priority' => 1
	) ) );
	*/
	
	$wp_customize->add_control(
		'your_control_id', 
		array(
			'label'    => __( 'Randomize Color Scheme', 'colourlovers' ),
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

/**
 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
 *
 * @since Twenty Sixteen 1.0
 */
function colourlovers_customize_preview_js() {
	wp_enqueue_script( 'colourlovers-customize-preview', get_stylesheet_directory_uri() . '/js/customize-preview.js', array( 'customize-preview' ), '20150922', true );
}
add_action( 'customize_preview_init', 'colourlovers_customize_preview_js' );
