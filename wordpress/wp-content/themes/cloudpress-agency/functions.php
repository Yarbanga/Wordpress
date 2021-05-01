<?php
// Global variables define
define('CLOUDPRESS_AGENCY_PARENT_TEMPLATE_DIR_URI', get_template_directory_uri());
define('CLOUDPRESS_AGENCY_TEMPLATE_DIR_URI', get_stylesheet_directory_uri());
define('CLOUDPRESS_AGENCY_TEMPLATE_DIR', trailingslashit(get_stylesheet_directory()));

if (!function_exists('wp_body_open')) {

    function wp_body_open() {
        /**
         * Triggered after the opening <body> tag.
         */
        do_action('wp_body_open');
    }

}

// Enqueue Script
add_action('wp_enqueue_scripts', 'cloudpress_agency_enqueue_styles',999);

function cloudpress_agency_enqueue_styles() {

    wp_enqueue_style('cloudpress-agency-parent-style', CLOUDPRESS_AGENCY_PARENT_TEMPLATE_DIR_URI . '/style.css', array('bootstrap'));

    if (get_theme_mod('custom_color_enable') == true) {
        cloudpress_agency_custom_light();
    }
    else{
        wp_enqueue_style('cloudpress-agency-default-style', CLOUDPRESS_AGENCY_TEMPLATE_DIR_URI . '/assets/css/default.css');
    }

   
    
}

//Setup theme
add_action('after_setup_theme', 'cloudpress_agency_setup');

function cloudpress_agency_setup() {
    load_theme_textdomain('cloudpress-agency', CLOUDPRESS_AGENCY_TEMPLATE_DIR . '/languages');

    require(CLOUDPRESS_AGENCY_TEMPLATE_DIR . '/inc/customizer/footer-options.php');
    require( CLOUDPRESS_AGENCY_TEMPLATE_DIR . '/inc/customizer/customizer_theme_style.php');

    //About Theme
    $theme = wp_get_theme(); // gets the current theme
    if ('CloudPress Agency' == $theme->name) {
        if (is_admin()) {
            require CLOUDPRESS_AGENCY_TEMPLATE_DIR . '/admin/admin-init.php';
        }
    }
}

// Add footer hook
add_action('cloudpress_agency_footer_section_hook', 'cloudpress_agency_footer_section_hook');

function cloudpress_agency_footer_section_hook() {?>
 <footer class="site-footer">    
 <?php 
    if(is_active_sidebar('footer-sidebar-1') || is_active_sidebar('footer-sidebar-2') || is_active_sidebar('footer-sidebar-3') || is_active_sidebar('footer-sidebar-4')): ?>
        <div class="row footer-sidebar">
            <!--Footer Widgets-->           
            <div class="container">
              <div class="row">
                <?php get_template_part('sidebar','footer');?>  
              </div>
            </div>
            <!--/Footer Widgets--> 
        </div>  
    <?php endif;?> 
    <!--Site Info-->  
    <?php if(get_theme_mod('footer_enable',true)===true):?>      
            <div class="site-info">
                <div class="site-branding">
                 <?php $cloudpress_agency_footer_copyright = get_theme_mod('footer_copyright','<p>'.__( 'Proudly powered by <a href="https://wordpress.org"> WordPress</a> | Theme: <a href="https://spicethemes.com" rel="nofollow">CloudPress Agency</a> by SpiceThemes', 'cloudpress-agency' ).'</p>'); ?>
                <?php echo wp_kses_post($cloudpress_agency_footer_copyright);?> 
            </div>
        </div>
    <?php endif;?>    
    <!--/Site Info-->
 </footer>     
<?php
}

//Add custom color function
function cloudpress_agency_custom_light() {
    $cloudpress_agency_link_color = get_theme_mod('link_color','#ce1b28');
    list($r, $g, $b) = sscanf($cloudpress_agency_link_color, "#%02x%02x%02x");
    $r = $r - 50;
    $g = $g - 25;
    $b = $b - 40;

    if ($cloudpress_agency_link_color != '#ff0000') :?>
    <style type="text/css">
        .header-sidebar {
            background: <?php echo esc_html($cloudpress_agency_link_color); ?> !important;
        }
        .header-sidebar {
            background-color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .widget .custom-social-icons li > a
        {
            color: <?php echo esc_html($cloudpress_agency_link_color); ?>; 
        }
        /*.search-box-outer .dropdown-menu {
            border-top: solid 1px <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }*/
        .search-form input[type="submit"] {
            background: <?php echo esc_html($cloudpress_agency_link_color); ?> none repeat scroll 0 0;
            border: 1px solid <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .woocommerce ul.products li.product .onsale, .products span.onsale, .woocommerce span.onsale {
            background: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .woocommerce ul.products li.product .onsale, .products span.onsale {
            background: <?php echo esc_html($cloudpress_agency_link_color); ?>;
            border: 2px solid <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .woocommerce-loop-product__title:hover {
            color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .woocommerce ul.products li.product .button, .owl-item .item .cart .add_to_cart_button {
            background: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .woocommerce nav.woocommerce-pagination ul li a:focus, .woocommerce nav.woocommerce-pagination ul li a:hover, .woocommerce nav.woocommerce-pagination ul li span.current {
            background-color: <?php echo esc_html($cloudpress_agency_link_color); ?> !important;
        }
        .woocommerce ul.products li.product .onsale, .woocommerce span.onsale {
            background: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        button, input[type="button"], input[type="submit"] {
            background-color: <?php echo esc_html($cloudpress_agency_link_color); ?> !important;
        }
        .checkout-button.button.alt.wc-forward
        {
          background-color: <?php echo esc_html($cloudpress_agency_link_color); ?> !important;
        }
        .navbar-custom .nav > li > a:focus,
        .navbar-custom .nav > li > a:hover,
        .navbar-custom .nav .open > a,
        .navbar-custom .nav .open > a:focus,
        .navbar-custom .nav .open > a:hover,
        .navbar-custom .dropdown-menu > li > a:focus,
        .navbar-custom .dropdown-menu > li > a:hover,
        .dropdown-menu>.active> li>a:focus, 
        .dropdown-menu>.active> li>a:hover, 
        .navbar-custom .nav .dropdown-menu>.active>a{
          color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .btn-default:focus{background: <?php echo esc_html($cloudpress_agency_link_color); ?>;}
        /*.search-box-outer .dropdown-menu {
            border-top: solid 1px <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }*/
        .btn-animate.slidbtn.btn-small.btn-light {
            background: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .slider-caption .btn-combo .btn-default:hover {
            background-color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
            border: unset;
        }
        .btn-animate.border:before, .btn-animate.border:after {
            background: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .owl-carousel .owl-prev:hover, .owl-carousel .owl-prev:focus {
            background-color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .owl-carousel .owl-next:hover, .owl-carousel .owl-next:focus {
            background-color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .call-to-action, .call-to-action-one {
            background-color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .services .post-thumbnail a {
            color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .services .post:before {
            border-bottom-color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .text-default {
            color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .btn-animate.border {
            border: 2px solid <?php echo esc_html($cloudpress_agency_link_color); ?>  !important;
        }
        .portfolio-filters li.active a:before, .portfolio-filters li a:before {
            background-color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .portfolio .post {
            background-color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .bg-default {
            background-color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .products .onsale {
            background: <?php echo esc_html($cloudpress_agency_link_color); ?>;
            border: 2px solid <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .owl-theme .owl-dots .owl-dot.active span, .owl-theme .owl-dots .owl-dot:hover span {
            background: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .team-grid .social-links li a:hover, 
        .team-grid .social-links li a:focus {
          color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .pricing-title-bg.default {
            background-color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .pricing-plans .price {
            color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .btn-bg-default {
            background: <?php echo esc_html($cloudpress_agency_link_color); ?> !important;
        }
        .entry-meta .cat-links a, .entry-meta .tag-links a {
            color: <?php echo esc_html($cloudpress_agency_link_color); ?> !important;
        }
        .site-info {
            background-color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .scroll-up a:hover, .scroll-up a:focus {
            background: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .cart-header > a .cart-total {
            background-color: transparent;
            color: #fff;
            position: absolute;
            top: 5px;
        }
        .woocommerce p.stars a {
            color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .woocommerce .star-rating::before {
            color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .woocommerce .star-rating span::before {
            color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .woocommerce-message, .woocommerce-info {
            border-top-color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .woocommerce-message::before, .woocommerce-info::before {
            color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        body .woocommerce #respond input#submit, body .woocommerce a.button, body .woocommerce button.button, body .woocommerce input.button {
            background-color: <?php echo esc_html($cloudpress_agency_link_color); ?> ;
            color: #fff !important;
            line-height: 1.4
        }
        .page-breadcrumb.text-center span a:hover {
            color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .page-breadcrumb.text-center .breadcrumb_last {
            color: <?php echo esc_html($cloudpress_agency_link_color); ?> !important;
        }
        .widget a:hover, .widget a:focus, .widget .post .entry-title a:hover, .widget .post .entry-title a:focus, .sidebar .entry-meta .cat-links a:hover, .sidebar .entry-meta .cat-links a:focus, .sidebar .entry-meta .tag-links a:hover, .sidebar .entry-meta .tag-links a:focus {
            color: <?php echo esc_html($cloudpress_agency_link_color); ?> !important;
        }
        .entry-meta a:hover, .entry-meta a:focus, .item-meta a:hover, .item-meta a:focus {
            color: <?php echo esc_html($cloudpress_agency_link_color); ?> !important;
        }
        .btn-default, .btn-animate.light, .btn-animate.dark {
            background: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .pagination a:hover, .pagination a.active { background-color: <?php echo esc_html($cloudpress_agency_link_color); ?> !important; color: #fff !important;  }
        .entry-header .entry-title a:hover {
            color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }

        /*404 page*/
        .error-404 h1 > i {
            color: <?php echo esc_html($cloudpress_agency_link_color); ?>; 
        }

        /*comments*/
        .reply a {
            background-color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
            border: 1px solid <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }

        .navbar-custom .open .nav li.active a, .navbar-custom .open .nav li.active a:hover, .navbar-custom .open .nav li.active a:focus, .navbar-custom .open .nav li a:hover {
            color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .navbar .nav .nav-item:hover .nav-link, .navbar .nav .nav-item.active .nav-link {
            color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .navbar .search-box-outer .dropdown-menu
        {
          border-top: solid 1px <?php echo esc_html($cloudpress_agency_link_color); ?>;  
        }

        /*contact template*/
        .contact .subtitle {
            color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }

        .contact-form {
            border-top: 4px solid <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }

        .contact-icon {
            background-color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .testimonial .testmonial-block .name a:hover
        {
            color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        blockquote {
            border-left: 3px solid <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .portfolio-filters .nav-item .active, .portfolio-filters .nav-item.active a
        {
            color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .dropdown-item.active, .dropdown-item:active
        {
            background-color: <?php echo esc_html($cloudpress_agency_link_color); ?> !important;
        }
        .woocommerce .widget_price_filter .ui-slider .ui-slider-range
        {
            background-color: <?php echo esc_html($cloudpress_agency_link_color); ?> !important;
        }
        .woocommerce .widget_price_filter .ui-slider .ui-slider-handle
        {
            background-color: <?php echo esc_html($cloudpress_agency_link_color); ?> !important;
        }
        .dropdown-item:hover
        {
            background-color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
           color: #fff;
        }

        #shop #shop-carousel .product-price a:hover
        {
            color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }

        .site-footer .footer-sidebar .entry-meta .cat-links a, .site-footer .footer-sidebar .entry-meta .tag-links a {
            color: <?php echo esc_html($cloudpress_agency_link_color); ?> !important;
        }
        .navbar a.bg-light:hover,.dropdown-item:hover
        {
            background-color: transparent !important;
            color:<?php echo esc_html($cloudpress_agency_link_color); ?> !important;
        }
        .services2 .post::before {background-color: <?php echo esc_html($cloudpress_agency_link_color); ?>;}
        .services2 .post-thumbnail i.fa {color: <?php echo esc_html($cloudpress_agency_link_color); ?>;}
        .navbar6.navbar ul li > a:hover:after {
             background:<?php echo esc_html($cloudpress_agency_link_color); ?>; 
        }
        .woocommerce span.onsale {
            background-color: <?php echo esc_html($cloudpress_agency_link_color); ?> !important;
        }
        .woocommerce-message {
            border-top-color: <?php echo esc_html($cloudpress_agency_link_color); ?> !important;
        }
        .woocommerce-message::before {
            color: <?php echo esc_html($cloudpress_agency_link_color); ?> !important;
        }
        .entry-content a:hover, .entry-content a:focus {
            color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .navbar6.navbar .navbar-nav > li.active > a:after, 
        .navbar6.navbar ul li > a:hover:after {
            background:<?php echo esc_html($cloudpress_agency_link_color); ?>; 
        }
        .pagination a:hover, .pagination a.active, .page-numbers.current {
            background-color: <?php echo esc_html($cloudpress_agency_link_color); ?>!important;
        }
        .index6 .custom-logo-link-url .site-title a:hover {color:  <?php echo esc_html($cloudpress_agency_link_color); ?>;}
        .page-content .entry-content a:hover, .entry-content a:focus {
            color: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        .cart-header > a .cart-total {
            background: <?php echo esc_html($cloudpress_agency_link_color); ?>;
        }
        body .woocommerce #respond .form-submit input[type="submit"], 
        body .woocommerce a.button, 
        body .woocommerce button.button, 
        body .woocommerce input.button {
            background-color: <?php echo esc_html($cloudpress_agency_link_color); ?> !important;
            color: #fff !important;
            line-height: 1.4
        }
    </style>
<?php
endif;
}

//Add Script for Search 
function cloudpress_agency_search_toggle(){?>
    <script type="text/javascript">
        jQuery(document).ready(function() {
             jQuery('.index6 .header-module .search-box-outer a.search-icon').on('click',function(e) {
               jQuery('.index6 .header-module .search-box-outer ul.dropdown-menu').slideToggle();
                e.stopPropagation();
             });
              jQuery(".search-icon").click(function(e){
                e.preventDefault();
             });
             jQuery('a,button').bind('focus', function() {
                 if(!jQuery(this).closest(".search-box-outer").length){
                   jQuery(".search-panel").css('display','none');
                } 
            });
        });
    </script>
    <?php
    if ( class_exists( 'WooCommerce' ) ) {?>
    <style type="text/css">
        .index6 .header-module .nav-search {border-right: 1px solid rgb(255 255 255 / 40%);}
        .index6 .header-module .cart-header {display: inline-block;}
    </style>
    <?php
    }else{?>
    <style type="text/css">
        @media (max-width: 600px){
        .index6 .header-module .nav-search {margin: 0;padding: 0;}
    }
    </style>
    <?php

    }

}
add_action('wp_head','cloudpress_agency_search_toggle');