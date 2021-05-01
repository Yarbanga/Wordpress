<?php
/*
 * Plugin Name:       Icyclub
 * Plugin URI:        
 * Description:       Icyclub plugin is comptible for Themeansar theme.
 * Version:           1.7.5
 * Author:            themeicy
 * Author URI:        https://themeicy.com
 * License:           GPL-2.0+
 * Tested up to: 	  5.5
 * Requires: 		  4.6 or higher
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       icyclub
 * Domain Path:       /languages
 */
 
define( 'ICYCP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'ICYCP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

function icycp_activate() {
	$theme = wp_get_theme();
	if ( ('Consultup' == $theme->name) || ('Busiup' == $theme->name) || ('Busiway' == $theme->name) || ( 'Listing' == $theme->name) ||
		('Consultup Child' == $theme->name) || ('Consultup Child theme of consultup' == $theme->name)){
		require_once('inc/consultup/features/customizer.php');
		require_once('inc/consultup/sections/homepage.php');
		require_once('inc/class-alpha-color-control/class-alpha-color-control.php');
	}

	
	if (( 'Shopbiz Lite' == $theme->name) || ('Spabeauty' == $theme->name)){
		require_once('inc/shopbiz/features/customizer.php');
		require_once('inc/shopbiz/sections/homepage.php');
	}

	if ( 'Agencyup' == $theme->name){
		require_once('inc/agencyup/features/customizer.php');
		require_once('inc/agencyup/features/customizer-header.php');
		require_once('inc/agencyup/sections/homepage.php');
		require_once('inc/agencyup/sections/header.php');
		require_once('inc/class-alpha-color-control/class-alpha-color-control.php');
	}

	if ( 'Busiage' == $theme->name){
		require_once('inc/agencyup/features/customizer.php');
		require_once('inc/agencyup/features/customizer-header.php');
		require_once('inc/agencyup/sections/homepage.php');
		require_once('inc/agencyup/sections/busiage-header.php');
		require_once('inc/class-alpha-color-control/class-alpha-color-control.php');
	}
}
add_action( 'init', 'icycp_activate' );

require_once('inc/consultup/control/control.php');

function icycp_enqueue(){
	wp_enqueue_style('icycp-custom-controls-css', plugin_dir_url(__FILE__) . 'assets/css/customizer.css', false, '1.0.0');

}
add_action('admin_enqueue_scripts', 'icycp_enqueue');


function icycp_customizer_section_live_preview() {
	wp_enqueue_script(
		'icycp-section-customizer-preview', plugin_dir_url(__FILE__) . 'assets/js/customizer.js', array(
			'jquery',
			'customize-preview',
		), 999, true
	);
}
add_action( 'customize_preview_init', 'icycp_customizer_section_live_preview' );

function icycp_customizer_script() {

	 wp_enqueue_style( 'icycp-customize', plugin_dir_url(__FILE__) .'assets/js/customize.css', 'screen' );
	 wp_enqueue_script( 'icycp-customizer-script', plugin_dir_url(__FILE__) .'assets/js/customizer-section.js', array("jquery"),'', true  );	
}
add_action( 'customize_controls_enqueue_scripts', 'icycp_customizer_script' );


$theme = wp_get_theme();
if (( 'Consultup' == $theme->name) || ( 'Busiup' == $theme->name) || ( 'Busiway' == $theme->name) || ( 'Listing' == $theme->name) || ('Consultup Child' == $theme->name) || ('Consultup Child theme of consultup' == $theme->name)){
		
	
register_activation_hook( __FILE__, 'icycp_page_installation_function');
function icycp_page_installation_function()
{	
$item_details_page = get_option('item_details_page'); 
    if(!$item_details_page){
	require_once('inc/consultup/pages/home.php');
	require_once('inc/consultup/pages/blog.php');
	update_option( 'item_details_page', 'Done' );
   }
}
}

if (( 'Shopbiz Lite' == $theme->name) || ( 'Spabeauty' == $theme->name)){
register_activation_hook( __FILE__, 'icycp_page_installation_function');
function icycp_page_installation_function()
{	
$item_details_page = get_option('item_details_page'); 
    if(!$item_details_page){
	require_once('inc/shopbiz/pages/home.php');
	require_once('inc/shopbiz/pages/blog.php');
	update_option( 'item_details_page', 'Done' );
   }
}
}


if ( 'Agencyup' == $theme->name){
register_activation_hook( __FILE__, 'icycp_page_installation_function');
function icycp_page_installation_function()
{	
$item_details_page = get_option('item_details_page'); 
    if(!$item_details_page){
	require_once('inc/agencyup/pages/home.php');
	require_once('inc/agencyup/pages/blog.php');
	update_option( 'item_details_page', 'Done' );
   }
}
}

// tn Limit Excerpt Length by number of Words
function excerpt( $limit ) {
$excerpt = explode(' ', get_the_excerpt(), $limit);
if (count($excerpt)>=$limit) {
array_pop($excerpt);
$excerpt = implode(" ",$excerpt).'';
} else {
$excerpt = implode(" ",$excerpt);
}
$excerpt = preg_replace('`[[^]]*]`','',$excerpt);
return $excerpt;
}


function icyclub_news_excerpt() {
    global $post;
    $excerpt = get_the_content();
    $excerpt = strip_tags(preg_replace(" (\[.*?\])", '', $excerpt));
    $excerpt = strip_shortcodes($excerpt);
    $original_len = strlen($excerpt);
    $consultup_excerpt_length = get_theme_mod('consultup_excerpt_length',180);
    $excerpt = substr($excerpt, 0, $consultup_excerpt_length);
    $len = strlen($excerpt);
    if ($original_len > 275) {
        $excerpt = $excerpt;
        return $excerpt . '<div class="news-excerpt-btn"><a href="' . esc_url(get_permalink()) . '" class="more-link">' . esc_html__("Read More", "icyclub") . '</a></div>';
    } else {
        return $excerpt;
    }
}
?>