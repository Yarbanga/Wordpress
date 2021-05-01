<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

wp_enqueue_script( 'jquery' );
wp_enqueue_script( 'jquery-ui-sortable' );
wp_enqueue_script('media-upload');
wp_enqueue_media();
wp_enqueue_style( 'wp-color-picker' );
wp_enqueue_script('wp-color-picker');
wp_enqueue_script('sf-uploader-js', plugin_dir_url( __FILE__ ) . 'assets/js/sf-uploader.js', array('jquery'), '1.0.0' );

global $wpdb;
$sf_table_name = $wpdb->prefix . 'sf_sliders';

if(isset($_GET['sf-slider-action']) && isset($_GET['sf-slider-layout'])){
	
	$sf_slider_action = sanitize_text_field($_GET['sf-slider-action']);
	$sf_slider_layout = sanitize_text_field($_GET['sf-slider-layout']);
	
	//creating new slider start
	if($sf_slider_action == "create"){
		$sf_slider_heading = __("Creating Slider With Layout", 'slider-factory')." ".$sf_slider_layout;
		$sf_slider_button_text = __("Save Slider", 'slider-factory');
		//generate slider id
		$sf_slider_id = get_sf_slider_id();
		$sf_slider_title = "";
		$slider = array();
	}
	//creating new slider end
	
	//editing existing slider start
	if($sf_slider_action == "edit"){
		$sf_slider_id = sanitize_text_field($_GET['sf-slider-id']);
		$shortcode = '[sf id='.$sf_slider_id.' layout='.$sf_slider_layout.']';
		//load slider content
		$slider = get_option("sf_slider_".$sf_slider_id);
		$sf_slider_title = $slider['sf_slider_title'];
		//print_r($slider);
		$sf_slider_heading = __("Editing Slider Shortcode")." <code>$shortcode</code> ".__( "Build With Layout", 'slider-factory')." <code>".$sf_slider_layout."</code>";
		$sf_slider_button_text = __("Update Slider", 'slider-factory');
	}
	//editing existing slider start
	
	include("slider-panel.php");
	
} else {
	echo '<div class="alert alert-danger m-3">'.__("Sorry! There is none of the slider layout or shortcode selected to create or manage.", 'slider-factory').'</div>';
}
?>