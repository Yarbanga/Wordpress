<?php
/**
 * Theme functions and definitions
 *
 * @package BusiUp
 */

if ( ! function_exists( 'busiup_enqueue_styles' ) ) :

	/**
	 * Load assets.
	 *
	 * @since 1.0.0
	 */
	function busiup_enqueue_styles() {

		wp_enqueue_style( 'consultup-style-parent', get_template_directory_uri() . '/style.css' );
		wp_enqueue_style( 'busiup-style', get_stylesheet_directory_uri() . '/style.css', array( 'consultup-style-parent' ), '1.0' );
		wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.css');
		wp_enqueue_style( 'busiup-default-css', get_stylesheet_directory_uri()."/css/colors/default.css" );
		wp_dequeue_style( 'default',get_template_directory_uri() .'/css/colors/default.css');
	}

endif;

add_action( 'wp_enqueue_scripts', 'busiup_enqueue_styles', 99 );


function busiup_customizer_rid_values($wp_customize) {

  $wp_customize->remove_section('header_widget_one');
  $wp_customize->remove_section('header_widget_two');
  $wp_customize->remove_section('header_widget_three');
}

add_action( 'customize_register', 'busiup_customizer_rid_values', 1000 );