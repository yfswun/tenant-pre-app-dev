<?php

/*** Constants ***/
define( 'THEME', 'sg-window' );

/*** Styles ***/

add_action( 'wp_enqueue_scripts', 'load_styles' );

function load_styles() {

	// http://justintadlock.com/archives/2014/11/03/loading-parent-styles-for-child-themes

	/*** If using a child theme, auto-load the parent theme style. ***/
	if ( is_child_theme() ) {
		wp_enqueue_style( 'parent-style', trailingslashit( get_template_directory_uri() ) . '/style.css' );
	}

	/*** Always load active (child) theme's style.css. ***/
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'parent-style' ), false, 'all' );
	wp_enqueue_style( 'child-print-style', get_stylesheet_directory_uri() . '/print.css', array( 'parent-style' ), false, 'print' );
}

//enqueue CDN font awesome stylesheet
function TPA_font_awesome_enqueue(){
	wp_enqueue_script( 'font-awesome', 'https://use.fontawesome.com/985700562c.js' );
}
add_action('wp_enqueue_scripts', 'TPA_font_awesome_enqueue');

/*** Web Site Footer Text ***/
set_theme_mod( 'footer_text', '<a href="http://www.asianinc.org/" target="_blank">ASIAN, Inc.</a> | Empowering Diversity by Creating Opportunities' );

/*** Set logo on header (logotype) to use relative path ***/
set_theme_mod( 'logotype_url', site_url() . '/wp-content/uploads/2015/09/ASIANIncLogoPicL200_88.png' );

/*** Turn on cropping for all image sizes ***/
$crop_sizes = array(
				'thumbnail_crop',
				'medium_crop',
				'large_crop',
				);
foreach ( $crop_sizes as $cs ) {
	if ( false === get_option( $cs ) )
		add_option( $cs, '1' );
	else
		update_option( $cs, '1' );
}
?>