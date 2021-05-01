<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Plugin Name:       Slider Factory
 * Plugin URI:        https://wpfrank.com/
 * Description:       Slider factory provides multiple slider layouts in single dashboard. 
 * Version:           1.1.0
 * Requires at least: 4.0
 * Requires PHP:      7.2
 * Author:            FARAZFRANK
 * Author URI:        https://profiles.wordpress.org/farazfrank/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       slider-factory
 * Domain Path:       /languages
 
Slider Factory Premium is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Slider Factory Premium is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Slider Factory Premium. If not, see https://wpfrank.com/.
*/

// SF activation
function wpfrank_sf_activation(){
	//add plugin version
	add_option('slider-factory-version', '1.1.0');
}
register_activation_hook( __FILE__, 'wpfrank_sf_activation' );

// SF deactivation
function wpfrank_sf_deactivation(){
}
register_deactivation_hook( __FILE__, 'wpfrank_sf_deactivation' );

// SF uninstall
function wpfrank_sf_uninstall(){
}
register_uninstall_hook(__FILE__, 'wpfrank_sf_uninstall');

// load translation
function wpfrank_sf_load_translation() {
	load_plugin_textdomain( 'slider-factory', false, dirname( plugin_basename(__FILE__) ) .'/languages' );
}
add_action( 'plugins_loaded', 'wpfrank_sf_load_translation');

// SF  
function wpfrank_sf_menu_page() {
	// add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
	add_menu_page(__( 'Slider Factory', 'slider-factory' ), __( 'Slider Factory', 'slider-factory' ), 'manage_options', 'sf-slider-factory', 'wpfrank_sf_main', 'dashicons-format-gallery', 65);
	//add_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', int $position )
	add_submenu_page( 'sf-slider-factory', __( 'Manage Slider', 'slider-factory' ), __( 'Manage Slider', 'slider-factory' ), 'manage_options', 'sf-manage-slider', 'wpfrank_sf_manage_slider' );
}
add_action( 'admin_menu', 'wpfrank_sf_menu_page' );

// SF main page body
function wpfrank_sf_main(){
	require 'admin/all-sliders.php';
}

// SF sub menu for managing slider create and update
function wpfrank_sf_manage_slider(){
	require 'admin/manage-slider.php';
}

// sf load admin scripts (CSS/JS) only on pages
function wpfrank_sf_admin_scripts(){
	if(isset($_GET['page'])){
		//load plugin required CSS and JS only on plugin pages
		$sf_current_page_slug = $_GET['page'];
		if (strpos($sf_current_page_slug, 'sf-') !== false) {
			//CSS
			wp_enqueue_style( 'sf-admin-style-css', plugin_dir_url(__FILE__). 'admin/assets/css/style.css');
			wp_enqueue_style( 'sf-bootstrap-admin-css', plugin_dir_url(__FILE__). 'admin/assets/bootstrap-5.0.0/css/bootstrap-admin.css');
			wp_enqueue_style( 'sf-fontawesome-css', plugin_dir_url(__FILE__). 'admin/assets/fontawesome-free-5.15.1-web/css/all.css');
			
			//JS
			//wp_enqueue_script('sf-color-picker-js', plugin_dir_url( __FILE__ ) . 'admin/assets/js/sf-color-picker.js', array('jquery'), '' );
			wp_enqueue_script('sf-bootstrap-js', plugin_dir_url( __FILE__ ) . 'admin/assets/bootstrap-5.0.0/js/bootstrap.js', array('jquery'), '4.5.3' );
			wp_enqueue_script('sf-bootstrap-bundle-js', plugin_dir_url( __FILE__ ) . 'admin/assets/bootstrap-5.0.0/js/bootstrap.bundle.js', array('jquery'), '4.5.3' );
		}
	}
}
add_action( 'admin_enqueue_scripts', 'wpfrank_sf_admin_scripts' );

// 1. Get / Create next slider id
function get_sf_slider_id(){
	global $wpdb;
	$sf_slider_id = 1;
	$options_table_name = "{$wpdb->prefix}options";
	$slider_key = "sf_slider_";
	$slider_count_res = $wpdb->get_row(
		$wpdb->prepare("SELECT option_name FROM `$options_table_name` WHERE `option_name` LIKE %s ORDER BY option_id DESC LIMIT 1", '%'.$slider_key.'%'), ARRAY_N
	);
	
	if($wpdb->num_rows) {
		$last_slider_key = $slider_count_res[0];
		$sf_underscore_pos = strrpos($last_slider_key, '_');
		$last_slider_id = (int) substr($last_slider_key, ($sf_underscore_pos + 1));
		return ($last_slider_id + 1);
	} else {
		return 1;
	}
}

// 2. add slide images to the slider start
function wpfrank_sf_li_generate_ajax_callback() {
	if ( isset($_POST['sf_attachment_id']) && isset($_POST['sf_slider_id']) ) {
		//defaults
		$sf_slide_title = $sf_slide_alt = $sf_slide_descs = $sf_slide_thumbnail = "";
		//load values
		$attachment_id = $_POST['sf_attachment_id'];
		$sf_slide_title = get_the_title($attachment_id);
		$sf_slide_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', TRUE);
		//wp_get_attachment_image_src ( int $attachment_id, string|array $size = 'thumbnail', bool $icon = false )
		//thumb, thumbnail, medium, large, post-thumbnail
		$sf_slide_thumbnail = wp_get_attachment_image_src($attachment_id, 'large', true); // attachment medium URL
		$attachment = get_post( $attachment_id );
		$sf_slide_descs = $attachment->post_content; // attachment description
		?>
		<div class="sf-slide-column col-md-4 col-lg-3 col-xl-2 my-2 sf_slide_<?php echo $attachment_id; ?>" data-position="<?php echo $attachment_id; ?>">
			<div id="sf-slide-box" class="p-2 text-center shadow">
				<img class="img-fluid" src="<?php echo $sf_slide_thumbnail[0];?>" style="height: 200px;">
				<input type="text" class="form-control mt-1 sf_slide_id" name="sf_slide_id[<?php echo $attachment_id; ?>]" value="<?php echo $attachment_id; ?>" readonly>
				<input type="text" class="form-control mt-1 sf_slide_title" name="sf_slide_title[<?php echo $attachment_id; ?>]" placeholder="<?php _e('Slide Title', 'slider-factory'); ?>" value="<?php echo $sf_slide_title; ?>">
				<textarea class="form-control mt-1 sf_slide_desc" name="sf_slide_desc[<?php echo $attachment_id; ?>]" placeholder="<?php _e('Slide Description', 'slider-factory'); ?>"><?php echo $sf_slide_descs; ?></textarea>
				<input type="text" class="form-control mt-1 sf_slide_alt_text" name="sf_slide_alt_text[<?php echo $attachment_id; ?>]" placeholder="<?php _e('Slide Image SEO Text', 'slider-factory'); ?>" value="<?php echo $sf_slide_alt; ?>">
				<button type="button" class="form-control btn btn-danger mt-1" style="background-color: #e76f51; border-color: #e76f51;" onclick="return WpfrankSFremoveSlide('<?php echo $attachment_id; ?>');" name="sf_slide_remove"><?php _e('Remove Slide', 'slider-factory'); ?></button>
			</div>
		</div>
		<?php
		wp_die(); // this is required to terminate immediately and return a proper response
	}
}
add_action( 'wp_ajax_sf_image_id', 'wpfrank_sf_li_generate_ajax_callback' );
// 2. add slide images to the slider end

// 3. save slider start
function wpfrank_sf_save_slider_callback(){
	if ( isset($_POST['nonce']) && ! wp_verify_nonce( $_POST['nonce'], 'save-slider' ) ){
		echo "Nonce not verified action.";
		die;
	} else {
		
		// verified action
		/* echo "<pre>";
		print_r($_POST);
		echo "</pre>"; */
		
		//slider info
		$sf_slider_id = isset( $_POST['sf_slider_id'] ) ? sanitize_text_field($_POST['sf_slider_id']) : NULL;
		$sf_slider_layout = isset( $_POST['sf_slider_layout'] ) ? sanitize_text_field($_POST['sf_slider_layout']) : "";
		$sf_slider_title = isset( $_POST['sf_slider_title'] ) ? sanitize_text_field($_POST['sf_slider_title']) : "";
		$sf_slider_info = array(
			'sf_slider_id' => $sf_slider_id, 
			'sf_slider_layout' => $sf_slider_layout,
			'sf_slider_title' => $sf_slider_title
		);
		
		//slides - any of the WordPress data sanitization functions can be used here
		$sf_slide_ids = isset( $_POST['sf_slide_ids'] ) ? (array) array_map('sanitize_text_field', $_POST['sf_slide_ids']) : array();
		$sf_slide_titles = isset( $_POST['sf_slide_titles'] ) ? (array) array_map('sanitize_text_field', $_POST['sf_slide_titles']) : array();
		$sf_slide_descs = isset( $_POST['sf_slide_descs'] ) ? (array) array_map('sanitize_text_field', $_POST['sf_slide_descs']) : array();
		
		parse_str($_POST['sf_slide_ids'], $sf_slide_ids);
		parse_str($_POST['sf_slide_titles'], $sf_slide_titles);
		parse_str($_POST['sf_slide_descs'], $sf_slide_descs);

		//update attachment meta - title, alt, description
		$i = 0;
		if(count($sf_slide_ids)) {
			foreach($sf_slide_ids['sf_slide_id'] as $id) {
				$title = $sf_slide_titles['sf_slide_title'][$id];
				$description = $sf_slide_descs['sf_slide_desc'][$id];
				$alt = $sf_slide_alts_text['sf_slide_alt_text'][$id];
				$sf_slide_update = array(
					'ID'           => $id,
					'post_title'   => $title,
					'post_content' => $description,
				);
				wp_update_post( $sf_slide_update );
				update_post_meta( $id, '_wp_attachment_image_alt', sanitize_text_field( $alt ) );
				$i++;
			}
		}
		
		//settings
		// 1 start
		if($sf_slider_layout == 1) {
			$sf_1_width = isset( $_POST['sf_1_width'] ) ? sanitize_text_field($_POST['sf_1_width']) : "100%";
			$sf_1_height = isset( $_POST['sf_1_height'] ) ? sanitize_text_field($_POST['sf_1_height']) : "100%";
			$sf_1_auto_play = isset( $_POST['sf_1_auto_play'] ) ? sanitize_text_field($_POST['sf_1_auto_play']) : "true";
			$sf_1_sorting = isset( $_POST['sf_1_sorting'] ) ? sanitize_text_field($_POST['sf_1_sorting']) : 0;

			$sf_slider_settings = array(
				'sf_1_width' => $sf_1_width,
				'sf_1_height' => $sf_1_height,
				'sf_1_auto_play' => $sf_1_auto_play,
				'sf_1_sorting' => $sf_1_sorting,
			);
		}
		// 1 end
		
		
		// 2 start
		if($sf_slider_layout == 2) {
			$sf_2_width = isset( $_POST['sf_2_width'] ) ? sanitize_text_field($_POST['sf_2_width']) : "100%";
			$sf_2_height = isset( $_POST['sf_2_height'] ) ? sanitize_text_field($_POST['sf_2_height']) : "100%";
			$sf_2_sorting = isset( $_POST['sf_2_sorting'] ) ? sanitize_text_field($_POST['sf_2_sorting']) : 0;
			
			$sf_slider_settings = array(
				'sf_2_width' => $sf_2_width,
				'sf_2_height' => $sf_2_height,
				'sf_2_sorting' => $sf_2_sorting,
			);
		}
		// 3 end
		
		
		// 3 start
		if($sf_slider_layout == 3) {
			$sf_3_width = isset( $_POST['sf_3_width'] ) ? sanitize_text_field($_POST['sf_3_width']) : "100%";
			$sf_3_height = isset( $_POST['sf_3_height'] ) ? sanitize_text_field($_POST['sf_3_height']) : "500";
			$sf_3_auto_play = isset( $_POST['sf_3_auto_play'] ) ? sanitize_text_field($_POST['sf_3_auto_play']) : "true";
			$sf_3_sorting = isset( $_POST['sf_3_sorting'] ) ? sanitize_text_field($_POST['sf_3_sorting']) : 0;
			
			$sf_slider_settings = array(
				'sf_3_width' => $sf_3_width,
				'sf_3_height' => $sf_3_height,
				'sf_3_auto_play' => $sf_3_auto_play,
				'sf_3_sorting' => $sf_3_sorting,
			);
		}
		// 3 end
		
		// 4 start
		if($sf_slider_layout == 4) {
			$sf_4_width = isset( $_POST['sf_4_width'] ) ? sanitize_text_field($_POST['sf_4_width']) : "100%";
			$sf_4_height = isset( $_POST['sf_4_height'] ) ? sanitize_text_field($_POST['sf_4_height']) : "100%";
			$sf_4_auto_play = isset( $_POST['sf_4_auto_play'] ) ? sanitize_text_field($_POST['sf_4_auto_play']) : "true";
			$sf_4_sorting = isset( $_POST['sf_4_sorting'] ) ? sanitize_text_field($_POST['sf_4_sorting']) : 0;
			
			$sf_slider_settings = array(
				'sf_4_width' => $sf_4_width,
				'sf_4_height' => $sf_4_height,
				'sf_4_auto_play' => $sf_4_auto_play,
				'sf_4_sorting' => $sf_4_sorting,
			);
		}
		// 4 end
		
		// 5 start
		if($sf_slider_layout == 5) {
			$sf_5_width = isset( $_POST['sf_5_width'] ) ? sanitize_text_field($_POST['sf_5_width']) : "500px";
			$sf_5_height = isset( $_POST['sf_5_height'] ) ? sanitize_text_field($_POST['sf_5_height']) : "400px";
			$sf_5_auto_play = isset( $_POST['sf_5_auto_play'] ) ? sanitize_text_field($_POST['sf_5_auto_play']) : "false";
			$sf_5_sorting = isset( $_POST['sf_5_sorting'] ) ? sanitize_text_field($_POST['sf_5_sorting']) : 0;
			
			$sf_slider_settings = array(
				'sf_5_width' => $sf_5_width,
				'sf_5_height' => $sf_5_height,
				'sf_5_auto_play' => $sf_5_auto_play,
				'sf_5_sorting' => $sf_5_sorting,
			);
		}
		// 5 end
		
		// 6 start
		if($sf_slider_layout == 6) {
			$sf_6_width = isset( $_POST['sf_6_width'] ) ? sanitize_text_field($_POST['sf_6_width']) : "100%";
			$sf_6_height = isset( $_POST['sf_6_height'] ) ? sanitize_text_field($_POST['sf_6_height']) : "100%";
			$sf_6_auto_play = isset( $_POST['sf_6_auto_play'] ) ? sanitize_text_field($_POST['sf_6_auto_play']) : "true";
			$sf_6_sorting = isset( $_POST['sf_6_sorting'] ) ? sanitize_text_field($_POST['sf_6_sorting']) : 0;
			
			$sf_slider_settings = array(
				'sf_6_width' => $sf_6_width,
				'sf_6_height' => $sf_6_height,
				'sf_6_auto_play' => $sf_6_auto_play,
				'sf_6_sorting' => $sf_6_sorting,
			);
		}
		// 6 end
		
		// 7 start
		if($sf_slider_layout == 7) {
			echo $sf_7_width = isset( $_POST['sf_7_width'] ) ? sanitize_text_field($_POST['sf_7_width']) : "100%";
			echo "<br>";
			echo $sf_7_height = isset( $_POST['sf_7_height'] ) ? sanitize_text_field($_POST['sf_7_height']) : "100%";
			echo "<br>";
			echo $sf_7_slide_circle_size = isset( $_POST['sf_7_slide_circle_size'] ) ? sanitize_text_field($_POST['sf_7_slide_circle_size']) : 360;
			echo "<br>";
			echo $sf_7_inner_circle_size = isset( $_POST['sf_7_inner_circle_size'] ) ? sanitize_text_field($_POST['sf_7_inner_circle_size']) : 480;
			echo "<br>";
			echo $sf_7_auto_play = isset( $_POST['sf_7_auto_play'] ) ? sanitize_text_field($_POST['sf_7_auto_play']) : "true";
			echo "<br>";
			echo $sf_7_sorting = isset( $_POST['sf_7_sorting'] ) ? sanitize_text_field($_POST['sf_7_sorting']) : 0;
			echo "<br>";
						
			$sf_slider_settings = array(
				'sf_7_width' => $sf_7_width,
				'sf_7_height' => $sf_7_height,
				'sf_7_slide_circle_size' => $sf_7_slide_circle_size,
				'sf_7_inner_circle_size' => $sf_7_inner_circle_size,
				'sf_7_auto_play' => $sf_7_auto_play,
				'sf_7_sorting' => $sf_7_sorting,
			);
		}		
		// 7 end
		
		// 8 start
		if($sf_slider_layout == 8) {
			echo $sf_8_width = isset( $_POST['sf_8_width'] ) ? sanitize_text_field($_POST['sf_8_width']) : "400px";
			echo "<br>";
			echo $sf_8_height = isset( $_POST['sf_8_height'] ) ? sanitize_text_field($_POST['sf_8_height']) : "400px";
			echo "<br>";
			echo $sf_8_responsive = isset( $_POST['sf_8_responsive'] ) ? sanitize_text_field($_POST['sf_8_responsive']) : "true";
			echo "<br>";
			echo $sf_8_sorting = isset( $_POST['sf_8_sorting'] ) ? sanitize_text_field($_POST['sf_8_sorting']) : 0;
			echo "<br>";
			
			$sf_slider_settings = array(
				'sf_8_width' => $sf_8_width,
				'sf_8_height' => $sf_8_height,
				'sf_8_responsive' => $sf_8_responsive,
				'sf_8_sorting' => $sf_8_sorting,
			);
		}
		// 8 end
		
		// 9 start
		if($sf_slider_layout == 9) {
			echo $sf_9_width = isset( $_POST['sf_9_width'] ) ? sanitize_text_field($_POST['sf_9_width']) : "100%";
			echo "<br>";
			echo $sf_9_height = isset( $_POST['sf_9_height'] ) ? sanitize_text_field($_POST['sf_9_height']) : "700px";
			echo "<br>";
			echo $sf_9_auto_play = isset( $_POST['sf_9_auto_play'] ) ? sanitize_text_field($_POST['sf_9_auto_play']) : "true";
			echo "<br>";
			echo $sf_9_sorting = isset( $_POST['sf_9_sorting'] ) ? sanitize_text_field($_POST['sf_9_sorting']) : 0;
			echo "<br>";
			
			
			$sf_slider_settings = array(
				'sf_9_width' => $sf_9_width,
				'sf_9_height' => $sf_9_height,
				'sf_9_auto_play' => $sf_9_auto_play,
				'sf_9_sorting' => $sf_9_sorting,
			);
		}
		// 9 end
		
		// 10 start
		if( $sf_slider_layout == 10 ) {
			echo $sf_10_width = isset( $_POST['sf_10_width'] ) ? sanitize_text_field($_POST['sf_10_width']) : "100%";
			echo "<br>";
			echo $sf_10_height = isset( $_POST['sf_10_height'] ) ? sanitize_text_field($_POST['sf_10_height']) : "100%";
			echo "<br>";
			echo $sf_10_sorting = isset( $_POST['sf_10_sorting'] ) ? sanitize_text_field($_POST['sf_10_sorting']) : 0;
			echo "<br>";
			
			$sf_slider_settings = array(
				'sf_10_width' => $sf_10_width,
				'sf_10_height' => $sf_10_height,
				'sf_10_sorting' => $sf_10_sorting,
			);
		}
		// 10 end
		
		// 11 start
		if($sf_slider_layout == 11) {
			echo $sf_11_width = isset( $_POST['sf_11_width'] ) ? sanitize_text_field($_POST['sf_11_width']) : "100%";
			echo "<br>";
			echo $sf_11_height = isset( $_POST['sf_11_height'] ) ? sanitize_text_field($_POST['sf_11_height']) : "750px";
			echo "<br>";
			echo $sf_11_sorting = isset( $_POST['sf_11_sorting'] ) ? sanitize_text_field($_POST['sf_11_sorting']) : 0;
			echo "<br>";
			
			$sf_slider_settings = array(
				'sf_11_width' => $sf_11_width,
				'sf_11_height' => $sf_11_height,
				'sf_11_sorting' => $sf_11_sorting,
			);
		}
		// 11 end
		
		// 12 start
		if( $sf_slider_layout == 12 ) {
			echo $sf_12_width = isset( $_POST['sf_12_width'] ) ? sanitize_text_field($_POST['sf_12_width']) : "100%";
			echo "<br>";
			echo $sf_12_height = isset( $_POST['sf_12_height'] ) ? sanitize_text_field($_POST['sf_12_height']) : "auto";
			echo "<br>";
					
			$sf_slider_settings = array(
				'sf_12_width' => $sf_12_width,
				'sf_12_height' => $sf_12_height,
			);
		}
		// 12 end
		
		$sf_slider_array = array_merge(
			$sf_slider_info,
			$sf_slide_ids, $sf_slide_titles,
			$sf_slider_settings
		);
		
		//print_r($sf_slider_array);
		update_option("sf_slider_".$sf_slider_id, $sf_slider_array);
		wp_die(); // this is required to terminate immediately and return a proper response
	}
}
add_action( 'wp_ajax_sf_save_slider', 'wpfrank_sf_save_slider_callback' );
// 3. save slider end


// 4. clone slider start
function wpfrank_sf_clone_slider_callback(){
	if ( isset($_POST['nonce']) && ! wp_verify_nonce( $_POST['nonce'], 'sf-clone-slider' ) ){
		echo "Nonce not verified action.";
		die;
	} else {
		// verified action
		if(isset( $_POST['sf_slider_id'] ) && isset( $_POST['sf_slider_counter'] )){
			$sf_slider_id = sanitize_text_field($_POST['sf_slider_id']);
			$sf_slider_counter = sanitize_text_field($_POST['sf_slider_counter']);
			//get cloning slider data
			$sf_cloning_slider = get_option('sf_slider_'.$sf_slider_id);
			//print_r($sf_cloning_slider);
			//echo "<br>";
			
			//generate new slider id for clone
			$new_sf_slider_id = get_sf_slider_id();
			$new_sf_slider_layout = $sf_cloning_slider['sf_slider_layout'];
			$new_sf_slider_title = $sf_cloning_slider['sf_slider_title'].' - cloned';
			
			// update clone id into slider data
			foreach($sf_cloning_slider as $key => $value){
				$sf_cloning_slider['sf_slider_id'] = $new_sf_slider_id;
				$sf_cloning_slider['sf_slider_title'] = $new_sf_slider_title;
			}
			//print_r($sf_cloning_slider);
			
			if(add_option('sf_slider_'.$new_sf_slider_id, $sf_cloning_slider)){
				$do_action = "'single'";
				echo '
				<tr id='.$new_sf_slider_id.'>
					<td>'.$new_sf_slider_title.'</td>
					<td>
						<input type="text" id="sf-slider-shortcode-'.$new_sf_slider_id.'" class="btn btn-info btn-sm" value="[sf id='.$new_sf_slider_id.' layout='.$new_sf_slider_layout.']">
						<button type="button" id="sf-copy-shortcode-'.$new_sf_slider_id.'" class="btn btn-info btn-sm" title="Click To Copy Slider Shortcode" onclick="return WpfrankSFCopyShortcode('.$new_sf_slider_id.');">'.__('Copy', 'slider-factory').'</button>
						<button class="btn btn-sm btn-success d-none sf-copied-'.$new_sf_slider_id.'">Copied</button>
					</td>
					<td>
						<button type="button" id="sf-clone-slider" class="btn btn-warning btn-sm" title="Clone Slider" value="'.$new_sf_slider_id.'" onclick="return WpfrankSFCloneSlider('.$new_sf_slider_id.', '.$sf_slider_counter.');"><i class="fas fa-copy"></i></button>
						<a href="admin.php?page=sf-manage-slider&amp;sf-slider-action=edit&amp;sf-slider-id='.$new_sf_slider_id.'&amp;sf-slider-layout='.$new_sf_slider_layout.'" id="sf-edit-slider" class="btn btn-warning btn-sm" title="Edit Slider"><i class="fas fa-edit"></i></a>
						<button id="sf-delete-slider" class="btn btn-warning btn-sm" title="Delete Slider" value="'.$new_sf_slider_id.'" onclick="return WpfrankSFremoveSlider('.$new_sf_slider_id.', '.$do_action.');"><i class="fas fa-trash-alt"></i></button>
					</td>
					<td class="text-center">
						<input type="checkbox" id="sf-slider-id" name="sf-slider-id" value="'.$new_sf_slider_id.'" title="Select Slider Shortcode">
					</td>
				</tr>
				';
			}
		}
		wp_die();
	}
}
add_action( 'wp_ajax_sf_clone_slider', 'wpfrank_sf_clone_slider_callback' );
// 4. clone slider end


// 5. remove slider/sliders start
function wpfrank_sf_remove_slider_callback(){
	if ( isset($_POST['nonce']) && ! wp_verify_nonce( $_POST['nonce'], 'sf-remove-slider' ) ){
		echo "Nonce not verified action.";
		die;
	} else {
		// verified action
		if(isset($_POST['sf_slider_id']) && isset($_POST['do_action'])){
			
			$sf_slider_id = $_POST['sf_slider_id'];
			$sf_do_action = $_POST['do_action'];
			
			//single slider delete
			if($sf_do_action == 'single') {
				delete_option('sf_slider_'.$sf_slider_id);
			}
			
			//multiple slider delete
			if($sf_do_action == 'multiple') {
				foreach($sf_slider_id as $sf_single_id) {
					delete_option('sf_slider_'.$sf_single_id);
				}
			}
		}
		wp_die();
	}
}
add_action( 'wp_ajax_sf_remove_slider', 'wpfrank_sf_remove_slider_callback' );
// 5. remove slider/sliders end

// register sf scripts
function wpfrank_sf_register_scripts(){
	wp_enqueue_script('jquery');
	wp_register_style( 'fontawesome-css', plugin_dir_url(__FILE__). 'admin/assets/fontawesome-free-5.15.1-web/css/all.css');
	
	// layout 1 CSS and JS start
	wp_register_style('sf-1-flickity-css', plugin_dir_url(__FILE__). 'layouts/assets/1/css/flickity.css'); // v2.2.1
	wp_register_script('sf-1-flickity-pkgd-min-js', plugin_dir_url( __FILE__ ) . 'layouts/assets/1/js/flickity.pkgd.js', array('jquery'), '2.2.1', true);
	// layout 1 CSS and JS end
	
	// layout 2 CSS and JS start
	wp_register_style('sf-2-photoroller-css', plugin_dir_url(__FILE__). 'layouts/assets/2/css/photoroller.css'); // v1.4.0
	wp_register_script('sf-2-photoroller-js', plugin_dir_url( __FILE__ ) . 'layouts/assets/2/js/jquery.photoroller.js', array('jquery'), '1.4.0');
	// layout 2 CSS and JS end
	
	// layout 3 CSS and JS start
	wp_register_script('sf-3-accordion-carousel-blue-slider-js', plugin_dir_url( __FILE__ ) . 'layouts/assets/3/js/accordion-carousel-blue-slider.js', array('jquery'), '1.0.0');
	// layout 3 CSS and JS end
	
	// layout 4 CSS and JS start
	wp_register_style('sf-4-camera-css', plugin_dir_url(__FILE__). 'layouts/assets/4/css/camera.css'); // v1.0.0
	wp_register_script('sf-4-camera-js', plugin_dir_url( __FILE__ ) . 'layouts/assets/4/js/camera.js', array('jquery'), '1.0.0');
	// layout 4 CSS and JS end
	
	// layout 5 CSS and JS start
	wp_register_style( 'sf-5-cover-flow-flipster-slider-css', plugin_dir_url(__FILE__). 'layouts/assets/5/css/jquery.flipster.css'); // v1.0.0
	wp_register_script('sf-5-cover-flow-flipster-slider-js', plugin_dir_url( __FILE__ ) . 'layouts/assets/5/js/jquery.flipster.js', array('jquery'), '1.0.0');
	// layout 5 CSS and JS end
	
	// layout 6 CSS and JS start
	wp_register_style( 'sf-6-wipeslider-css', plugin_dir_url(__FILE__). 'layouts/assets/6/css/wipeSlider.css');
	wp_register_script('sf-6-wipeslider-js', plugin_dir_url( __FILE__ ) . 'layouts/assets/6/js/jquery.wipeSlider.js', array('jquery'), '1.0.0');
	// layout 6 CSS and JS end
	
	// layout 7 CSS and JS start
	wp_register_style( 'sf-7-rotating-slider-css', plugin_dir_url(__FILE__). 'layouts/assets/7/css/rotating-slider.css');
	wp_register_script('sf-7-jquery.rotating-slider-js', plugin_dir_url( __FILE__ ) . 'layouts/assets/7/js/jquery.rotating-slider.js', array('jquery'), '1.0.0');
	// layout 7 CSS and JS end
	
	// layout 8 CSS and JS start
	wp_register_script('sf-8-infinite-slider-js', plugin_dir_url( __FILE__ ) . 'layouts/assets/8/js/infiniteslidev2.js', array('jquery'), '1.0.0');
	// layout 8 CSS and JS end
	
	// layout 9 CSS and JS start
	// layout 9 CSS and JS end
	
	// layout 10 CSS and JS start
	// layout 10 CSS and JS end
	
	// layout 11 CSS and JS start
	wp_register_style('sf-11-product-slider-style-css', plugin_dir_url(__FILE__). 'layouts/assets/11/css/test-style.css'); 
	wp_register_script('sf-11-product-slider-mordenizer-js', plugin_dir_url( __FILE__ ) . 'layouts/assets/11/js/modernizr.custom.js', array('jquery'), '1.0.0');
	wp_register_script('sf-11-product-slider-js', plugin_dir_url( __FILE__ ) . 'layouts/assets/11/js/slider.js', array('jquery'), '1.0.0');
	// layout 11 CSS and JS end
	
	// layout 12 CSS and JS start
	wp_register_style('sf-12-twentytwenty-css', plugin_dir_url(__FILE__). 'layouts/assets/12/css/twentytwenty.css'); 
	wp_register_script('sf-12-jquery-twentytwenty-js', plugin_dir_url( __FILE__ ) . 'layouts/assets/12/js/jquery.twentytwenty.js', array('jquery'), '1.0.0');
	wp_register_script('sf-12-jquery-event-move-js', plugin_dir_url( __FILE__ ) . 'layouts/assets/12/js/jquery.event.move.js', array('jquery'), '2.0.0');
	// layout 12 CSS and JS end
}
add_action( 'wp_enqueue_scripts', 'wpfrank_sf_register_scripts' );

include('shortcode.php');
// Slider Text Widget Support
add_filter( 'widget_text', 'do_shortcode' );
?>