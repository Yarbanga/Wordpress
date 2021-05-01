<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'ewdusSettings' ) ) {
/**
 * Class to handle configurable settings for Ultimate Slider
 * @since 1.0.0
 */
class ewdusSettings {

	/**
	 * Default values for settings
	 * @since 1.0.0
	 */
	public $defaults = array();

	/**
	 * Stored values for settings
	 * @since 1.0.0
	 */
	public $settings = array();

	public function __construct() {

		add_action( 'init', array( $this, 'set_defaults' ) );

		add_action( 'init', array( $this, 'load_settings_panel' ) );
	}

	/**
	 * Load the plugin's default settings
	 * @since 1.0.0
	 */
	public function set_defaults() {

		$this->defaults = array(

			'timer-bar'						=> 'bottom',
			'autoplay-delay'				=> 6,
			'autoplay-interval'				=> 6,
			'transition-time'				=> 1,
			'aspect-ratio'					=> '16_7',
			'mobile-aspect-ratio'			=> '16_7',
			'arrow'							=> 'a',
			'hide-from-slider'				=> array(),
			'hide-on-mobile'				=> array(),
		);

		$this->defaults = apply_filters( 'ewd_us_defaults', $this->defaults );
	}

	/**
	 * Get a setting's value or fallback to a default if one exists
	 * @since 1.0.0
	 */
	public function get_setting( $setting ) { 

		if ( empty( $this->settings ) ) {
			$this->settings = get_option( 'ewd-us-settings' );
		}
		
		if ( ! empty( $this->settings[ $setting ] ) or isset( $this->settings[ $setting ] ) ) {
			return apply_filters( 'ewd-us-settings-' . $setting, $this->settings[ $setting ] );
		}

		if ( ! empty( $this->defaults[ $setting ] ) or isset( $this->defaults[ $setting ] ) ) { 
			return apply_filters( 'ewd-us-settings-' . $setting, $this->defaults[ $setting ] );
		}

		return apply_filters( 'ewd-us-settings-' . $setting, null );
	}

	/**
	 * Set a setting to a particular value
	 * @since 1.0.0
	 */
	public function set_setting( $setting, $value ) {

		$this->settings[ $setting ] = $value;
	}

	/**
	 * Save all settings, to be used with set_setting
	 * @since 1.0.0
	 */
	public function save_settings() {
		
		update_option( 'ewd-us-settings', $this->settings );
	}

	/**
	 * Load the admin settings page
	 * @since 1.0.0
	 * @sa https://github.com/NateWr/simple-admin-pages
	 */
	public function load_settings_panel() {
		global $ewd_us_controller;

		require_once( EWD_US_PLUGIN_DIR . '/lib/simple-admin-pages/simple-admin-pages.php' );
		$sap = sap_initialize_library(
			$args = array(
				'version'       => '2.4.0',
				'lib_url'       => EWD_US_PLUGIN_URL . '/lib/simple-admin-pages/',
			)
		);

		$sap->add_page(
			'submenu',
			array(
				'id'            => 'ewd-us-settings',
				'title'         => __( 'Settings', 'ultimate-slider' ),
				'menu_title'    => __( 'Settings', 'ultimate-slider' ),
				'parent_menu'	=> 'edit.php?post_type=ultimate_slider',
				'description'   => '',
				'capability'    => 'manage_options',
				'default_tab'   => 'ewd-us-basic-tab',
			)
		);

		$sap->add_section(
			'ewd-us-settings',
			array(
				'id'            => 'ewd-us-basic-tab',
				'title'         => __( 'Basic', 'ultimate-slider' ),
				'is_tab'		=> true,
			)
		);

		$sap->add_section(
			'ewd-us-settings',
			array(
				'id'            => 'ewd-us-basic-options',
				'title'         => __( 'Basic Options', 'ultimate-slider' ),
				'tab'	        => 'ewd-us-basic-tab',
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-basic-options',
			'warningtip',
			array(
				'id'			=> 'shortcodes-reminder',
				'title'			=> __( 'REMINDER:', 'ultimate-slider' ),
				'placeholder'	=> __( 'To display the slider, place the [ultimate-slider] shortcode on a page' )
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-basic-options',
			'textarea',
			array(
				'id'			=> 'custom-css',
				'title'			=> __( 'Custom CSS', 'ultimate-slider' ),
				'description'	=> __( 'You can add custom CSS styles to your slider in the box above.', 'ultimate-slider' ),			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-basic-options',
			'toggle',
			array(
				'id'			=> 'autoplay-slideshow',
				'title'			=> __( 'Autoplay Slideshow', 'ultimate-slider' ),
				'description'	=> __( 'Should the slider automatically toggle through slides?', 'ultimate-slider' )
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-basic-options',
			'count',
			array(
				'id'			=> 'autoplay-delay',
				'title'			=> __( 'Autoplay Delay', 'ultimate-slider' ),
				'description'	=> __( 'If autoplay is on, how many seconds should the timer wait before starting the slideshow?', 'ultimate-slider' ),
				'default'		=> $this->defaults['autoplay-delay'],
				'blank_option'	=> false,
				'min_value'		=> 1,
				'max_value'		=> 60,
				'increment'		=> 1
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-basic-options',
			'count',
			array(
				'id'			=> 'autoplay-interval',
				'title'			=> __( 'Autoplay Interval', 'ultimate-slider' ),
				'description'	=> __( 'If autoplay is on, how many seconds should the slideshow wait between each slide?', 'ultimate-slider' ),
				'default'		=> $this->defaults['autoplay-interval'],
				'blank_option'	=> false,
				'min_value'		=> 1,
				'max_value'		=> 60,
				'increment'		=> 1
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-basic-options',
			'toggle',
			array(
				'id'			=> 'autoplay-pause-hover',
				'title'			=> __( 'Pause Autoplay on Hover', 'ultimate-slider' ),
				'description'	=> __( 'Should the slider autoplay automatically pause when you hover over it?', 'ultimate-slider' )
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-basic-options',
			'count',
			array(
				'id'			=> 'transition-time',
				'title'			=> __( 'Slide Transition Time', 'ultimate-slider' ),
				'description'	=> __( 'How many seconds should each transition take to complete?', 'ultimate-slider' ),
				'default'		=> $this->defaults['transition-time'],
				'blank_option'	=> false,
				'min_value'		=> 1,
				'max_value'		=> 10,
				'increment'		=> 1
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-basic-options',
			'select',
			array(
				'id'            => 'aspect-ratio',
				'title'         => __( 'Aspect Ratio', 'ultimate-slider' ),
				'description'   => '',
				'blank_option'	=> false,
				'default' 		=> $this->defaults['aspect-ratio'],
				'options'       => array(
					'3_1'			=> '3:1',
					'16_7' 			=> '16:7' . __( '(default)', 'ultimate-slider' ),
					'2_1'			=> '2:1',
					'16_9'			=> '16:9',
					'3_2'			=> '3:2',
					'4_3'			=> '4:3',
					'1_1'			=> '1:1',
				)
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-basic-options',
			'toggle',
			array(
				'id'			=> 'carousel',
				'title'			=> __( 'Carousel', 'ultimate-slider' ),
				'description'	=> __( 'Display a carousel slider instead of the default. The "Slide Transition Effect" setting has to be set to "Default".', 'ultimate-slider' )
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-basic-options',
			'radio',
			array(
				'id'			=> 'carousel-columns',
				'title'			=> __( 'Carousel Columns', 'ultimate-slider' ),
				'description'	=> __( 'Set the number of slides that should be displayed at once in carousel mode', 'ultimate-slider' ),
				'options'		=> array(
					2			=> 2,
					3			=> 3,
					4			=> 4
				)
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-basic-options',
			'radio',
			array(
				'id'			=> 'timer-bar',
				'title'			=> __( 'Timer Bar', 'ultimate-slider' ),
				'description'	=> __( 'Display a timer bar at the top or bottom of your slider.', 'ultimate-slider' ),
				'options'		=> array(
					'top'			=> __( 'Top', 'ultimate-slider' ),
					'bottom'		=> __( 'Bottom', 'ultimate-slider' ),
					'off'			=> __( 'Off', 'ultimate-slider' )
				)
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-basic-options',
			'radio',
			array(
				'id'			=> 'slide-indicators',
				'title'			=> __( 'Slide Indicators', 'ultimate-slider' ),
				'description'	=> __( 'Display navigation controls to jump between slides.', 'ultimate-slider' ),
				'options'		=> array(
					'none'				=> __( 'None', 'ultimate-slider' ),
					'dots'				=> __( 'Dots', 'ultimate-slider' ),
					'thumbnails'		=> __( 'Thumbnails', 'ultimate-slider' ),
					'sidethumbnails'	=> __( 'Side Thumbnails', 'ultimate-slider' )
				)
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-basic-options',
			'radio',
			array(
				'id'			=> 'link-action',
				'title'			=> __( 'Button Link Action', 'ultimate-slider' ),
				'description'	=> __( 'Should button links open in the same or new windows? "Smart" opens external links in new windows and links on your site in the same window.', 'ultimate-slider' ),
				'options'		=> array(
					'same'			=> __( 'Same Window', 'ultimate-slider' ),
					'new'			=> __( 'New Window', 'ultimate-slider' ),
					'smart'			=> __( 'Smart', 'ultimate-slider' )
				)
			)
		);

		if ( ! $ewd_us_controller->permissions->check_permission( 'premium' ) ) {
			$ewd_us_premium_permissions = array(
				'disabled'		=> true,
				'disabled_image'=> '#',
				'purchase_link'	=> 'https://www.etoilewebdesign.com/plugins/ultimate-slider/'
			);
		}
		else { $ewd_us_premium_permissions = array(); }

		$sap->add_section(
			'ewd-us-settings',
			array(
				'id'            => 'ewd-us-premium-tab',
				'title'         => __( 'Premium', 'ultimate-slider' ),
				'is_tab'		=> true,
			)
		);

		$sap->add_section(
			'ewd-us-settings',
			array_merge(
				array(
					'id'            => 'ewd-us-premium-options',
					'title'         => __( 'Premium Options', 'ultimate-slider' ),
					'tab'	        => 'ewd-us-premium-tab',
				),
				$ewd_us_premium_permissions
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-premium-options',
			'radio',
			array(
				'id'			=> 'slide-transition-effect',
				'title'			=> __( 'Slide Transition Effect', 'ultimate-slider' ),
				'description'	=> __( 'Which effect should be used to transition between slides?', 'ultimate-slider' ),
				'options'		=> array(
					'slide'			=> __( 'Default', 'ultimate-slider' ),
					'fade'			=> __( 'Fade', 'ultimate-slider' ),
					'slide-up'		=> __( 'Slide Up', 'ultimate-slider' ),
					'slide-down'	=> __( 'Slide Down', 'ultimate-slider' ),
					'stretch-right'	=> __( 'Stretch Right', 'ultimate-slider' ),
					'stretch-left'	=> __( 'Stretch Left', 'ultimate-slider' ),
					'grow'			=> __( 'Grow', 'ultimate-slider' ),
					'expand'		=> __( 'Expand', 'ultimate-slider' ),
				)
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-premium-options',
			'toggle',
			array(
				'id'			=> 'wc-product-image-slider',
				'title'			=> __( 'WooCommerce Product Image Slider', 'ultimate-slider' ),
				'description'	=> __( 'Should the WooCommerce product page image be converted into a slider when there\'s more than one image? (Might require changing the "Aspect Ratio" setting for the slider, depending on the theme you\'re using)', 'ultimate-slider' )
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-premium-options',
			'select',
			array(
				'id'            => 'mobile-aspect-ratio',
				'title'         => __( 'Mobile Aspect Ratio', 'ultimate-slider' ),
				'description'   => __( 'What should the aspect ratio of the slider be on smaller screens?', 'ultimate-slider' ),
				'blank_option'	=> false,
				'default' 		=> $this->defaults['mobile-aspect-ratio'],
				'options'       => array(
					'3_1'			=> '3:1',
					'16_7' 			=> '16:7' . __( '(default)', 'ultimate-slider' ),
					'2_1'			=> '2:1',
					'16_9'			=> '16:9',
					'3_2'			=> '3:2',
					'4_3'			=> '4:3',
					'1_1'			=> '1:1',
				)
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-premium-options',
			'checkbox',
			array(
				'id'			=> 'hide-from-slider',
				'title'			=> __( 'Hide Elements from Slider', 'ultimate-slider' ),
				'description'	=> __( 'Hide specific elements of the slider.', 'ultimate-slider' ),
				'options'		=> array(
					'title' 		=> 'Title',
					'body' 			=> 'Body',
					'buttons'		=> 'Buttons',
					'arrows'		=> 'Arrows',
				)
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-premium-options',
			'checkbox',
			array(
				'id'			=> 'hide-on-mobile',
				'title'			=> __( 'Hide Elements from Mobile View', 'ultimate-slider' ),
				'description'	=> __( 'Hide elements just from the mobile view.', 'ultimate-slider' ),
				'options'		=> array(
					'title' 		=> 'Title',
					'body' 			=> 'Body',
					'buttons'		=> 'Buttons',
					'arrows'		=> 'Arrows',
					'thumbnails'	=> 'Thumbnails',
				)
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-premium-options',
			'toggle',
			array(
				'id'			=> 'mobile-link-to-full',
				'title'			=> __( 'Mobile Link to Full Post?', 'ultimate-slider' ),
				'description'	=> __( 'Should clicking on a slide bring up the individual slide post on mobile?', 'ultimate-slider' )
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-premium-options',
			'radio',
			array(
				'id'			=> 'title-animate',
				'title'			=> __( 'Title Animation', 'ultimate-slider' ),
				'description'	=> '',
				'options'		=> array(
					'none'				=> __( 'None', 'ultimate-slider' ),
					'slidefromleft'		=> __( 'Slide From Left', 'ultimate-slider' ),
					'slidefromright'	=> __( 'Slide From Right', 'ultimate-slider' ),
					'fadein'			=> __( 'Fade In', 'ultimate-slider' ),
					'scrolldown'		=> __( 'Scroll Down', 'ultimate-slider' ),
				)
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-premium-options',
			'toggle',
			array(
				'id'			=> 'force-full-width',
				'title'			=> __( 'Force Full Width', 'ultimate-slider' ),
				'description'	=> __( 'Force the slider to go the full width of the window, regardless of the container it\'s in.', 'ultimate-slider' )
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-premium-options',
			'toggle',
			array(
				'id'			=> 'add-watermark',
				'title'			=> __( 'Add Watermark', 'ultimate-slider' ),
				'description'	=> __( 'Should a watermark be added to each image? Requires GD PHP module to be installed on your server.', 'ultimate-slider' )
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-premium-options',
			'toggle',
			array(
				'id'			=> 'lightbox',
				'title'			=> __( 'Lightbox on Image Click', 'ultimate-slider' ),
				'description'	=> __( 'Should a lightbox be opened when an image is clicked on? Particularly useful if you\'re using carousel mode. Want to customize this lightbox? Install the "Ultimate Lightbox" plugin , and you can switch the lightbox colors, controls, behaviour and more. It\'s free!', 'ultimate-slider' )
			)
		);

		if ( ! $ewd_us_controller->permissions->check_permission( 'youtube' ) ) {
			$ewd_us_youtube_permissions = array(
				'disabled'		=> true,
				'disabled_image'=> '#',
				'purchase_link'	=> 'https://www.etoilewebdesign.com/plugins/ultimate-slider/'
			);
		}
		else { $ewd_us_youtube_permissions = array(); }

		$sap->add_section(
			'ewd-us-settings',
			array_merge(
				array(
					'id'            => 'ewd-us-youtube-options',
					'title'         => __( 'YouTube Slide Options', 'ultimate-slider' ),
					'tab'	        => 'ewd-us-premium-tab',
				),
				$ewd_us_youtube_permissions
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-youtube-options',
			'toggle',
			array(
				'id'			=> 'youtube-autoplay-video',
				'title'			=> __( 'Autoplay Video', 'ultimate-slider' ),
				'description'	=> __( 'Should the video automatically start playing on page load?', 'ultimate-slider' )
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-youtube-options',
			'toggle',
			array(
				'id'			=> 'youtube-show-content',
				'title'			=> __( 'Show Slide Content', 'ultimate-slider' ),
				'description'	=> __( 'Enabling this will overlay the slide content on top of the video. Disable it to only show the video.', 'ultimate-slider' )
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-youtube-options',
			'text',
			array(
				'id'            => 'youtube-video-opacity',
				'title'         => __( 'Video Opacity', 'ultimate-slider' ),
				'description'	=> __( 'This lets you set the opacity of the video, which can help if you have the content enabled above. (Examples would be 0, 0.5 or 1.)', 'ultimate-slider' ),
				'small'			=> true
			)
		);

		if ( ! $ewd_us_controller->permissions->check_permission( 'styling' ) ) {
			$ewd_us_styling_permissions = array(
				'disabled'		=> true,
				'disabled_image'=> '#',
				'purchase_link'	=> 'https://www.etoilewebdesign.com/plugins/ultimate-slider/'
			);
		}
		else { $ewd_us_styling_permissions = array(); }

		$sap->add_section(
			'ewd-us-settings',
			array(
				'id'            => 'ewd-us-styling-tab',
				'title'         => __( 'Styling', 'ultimate-slider' ),
				'is_tab'		=> true,
			)
		);

		$sap->add_section(
			'ewd-us-settings',
			array_merge(
				array(
					'id'            => 'ewd-us-slide-title-options',
					'title'         => __( 'Slide Title Options', 'ultimate-slider' ),
					'tab'	        => 'ewd-us-styling-tab',
				),
				$ewd_us_styling_permissions
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-slide-title-options',
			'colorpicker',
			array(
				'id'			=> 'styling-slide-title-font-color',
				'title'			=> __( 'Color', 'ultimate-slider' )
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-slide-title-options',
			'text',
			array(
				'id'            => 'styling-slide-title-font',
				'title'         => __( 'Font Family', 'ultimate-slider' ),
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-slide-title-options',
			'text',
			array(
				'id'            => 'styling-slide-title-font-size',
				'title'         => __( 'Font Size', 'ultimate-slider' ),
				'small'			=> true
			)
		);

		$sap->add_section(
			'ewd-us-settings',
			array_merge(
				array(
					'id'            => 'ewd-us-slide-text-options',
					'title'         => __( 'Slide Text Options', 'ultimate-slider' ),
					'tab'	        => 'ewd-us-styling-tab',
				),
				$ewd_us_styling_permissions
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-slide-text-options',
			'colorpicker',
			array(
				'id'			=> 'styling-slide-text-font-color',
				'title'			=> __( 'Color', 'ultimate-slider' )
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-slide-text-options',
			'text',
			array(
				'id'            => 'styling-slide-text-font',
				'title'         => __( 'Font Family', 'ultimate-slider' ),
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-slide-text-options',
			'text',
			array(
				'id'            => 'styling-slide-text-font-size',
				'title'         => __( 'Font Size', 'ultimate-slider' ),
				'small'			=> true
			)
		);

		$sap->add_section(
			'ewd-us-settings',
			array_merge(
				array(
					'id'            => 'ewd-us-slide-button-options',
					'title'         => __( 'Slide Button Options', 'ultimate-slider' ),
					'tab'	        => 'ewd-us-styling-tab',
				),
				$ewd_us_styling_permissions
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-slide-button-options',
			'colorpicker',
			array(
				'id'			=> 'styling-button-background-color',
				'title'			=> __( 'Background Color', 'ultimate-slider' )
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-slide-button-options',
			'colorpicker',
			array(
				'id'			=> 'styling-button-background-hover-color',
				'title'			=> __( 'Background Hover Color', 'ultimate-slider' )
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-slide-button-options',
			'colorpicker',
			array(
				'id'			=> 'styling-button-border-color',
				'title'			=> __( 'Border Color', 'ultimate-slider' )
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-slide-button-options',
			'colorpicker',
			array(
				'id'			=> 'styling-button-border-hover-color',
				'title'			=> __( 'Border Hover Color', 'ultimate-slider' )
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-slide-button-options',
			'colorpicker',
			array(
				'id'			=> 'styling-button-text-color',
				'title'			=> __( 'Text Color', 'ultimate-slider' )
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-slide-button-options',
			'colorpicker',
			array(
				'id'			=> 'styling-button-text-hover-color',
				'title'			=> __( 'Text Hover Color', 'ultimate-slider' )
			)
		);

		if ( ! $ewd_us_controller->permissions->check_permission( 'controls' ) ) {
			$ewd_us_controls_permissions = array(
				'disabled'		=> true,
				'disabled_image'=> '#',
				'purchase_link'	=> 'https://www.etoilewebdesign.com/plugins/ultimate-slider/'
			);
		}
		else { $ewd_us_controls_permissions = array(); }

		$sap->add_section(
			'ewd-us-settings',
			array(
				'id'            => 'ewd-us-controls-tab',
				'title'         => __( 'Controls', 'ultimate-slider' ),
				'is_tab'		=> true,
			)
		);

		$sap->add_section(
			'ewd-us-settings',
			array_merge(
				array(
					'id'            => 'ewd-us-control-options',
					'title'         => __( 'Control Options', 'ultimate-slider' ),
					'tab'	        => 'ewd-us-controls-tab',
				),
				$ewd_us_controls_permissions
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-control-options',
			'radio',
			array(
				'id'			=> 'arrow',
				'title'			=> __( 'Arrows', 'ultimate-slider' ),
				'columns'		=> '3',
				'options'		=> array(
					'none'			=> __( 'No Arrow', 'ultimate-slider' ),
					'a'				=> '<span class="ewd-us-arrow">b</span>',
					'c'				=> '<span class="ewd-us-arrow">d</span>',
					'e'				=> '<span class="ewd-us-arrow">f</span>',
					'g'				=> '<span class="ewd-us-arrow">h</span>',
					'i'				=> '<span class="ewd-us-arrow">j</span>',
					'k'				=> '<span class="ewd-us-arrow">l</span>',
					'm'				=> '<span class="ewd-us-arrow">n</span>',
					'o'				=> '<span class="ewd-us-arrow">p</span>',
					'q'				=> '<span class="ewd-us-arrow">r</span>',
					'A'				=> '<span class="ewd-us-arrow">B</span>',
					'E'				=> '<span class="ewd-us-arrow">D</span>',
					'G'				=> '<span class="ewd-us-arrow">F</span>',
					'I'				=> '<span class="ewd-us-arrow">J</span>',
					'K'				=> '<span class="ewd-us-arrow">L</span>',
					'M'				=> '<span class="ewd-us-arrow">N</span>',
					'O'				=> '<span class="ewd-us-arrow">P</span>',
					'Q'				=> '<span class="ewd-us-arrow">R</span>',
				)
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-control-options',
			'radio',
			array(
				'id'			=> 'arrow-background-shape',
				'title'			=> __( 'Background Shape', 'ultimate-slider' ),
				'description'	=> '',
				'options'		=> array(
					'none'			=> __( 'No Background', 'ultimate-slider' ),
					'square'		=> __( 'Square', 'ultimate-slider' ),
					'circle'		=> __( 'Circle', 'ultimate-slider' ),
					'diamond'		=> __( 'Diamond', 'ultimate-slider' ),
				)
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-control-options',
			'colorpicker',
			array(
				'id'			=> 'styling-arrow-color',
				'title'			=> __( 'Arrow Color', 'ultimate-slider' )
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-control-options',
			'colorpicker',
			array(
				'id'			=> 'styling-arrow-background-color',
				'title'			=> __( 'Arrow Background Color', 'ultimate-slider' )
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-control-options',
			'colorpicker',
			array(
				'id'			=> 'styling-clickable-area-background-color',
				'title'			=> __( 'Clickable Area Color', 'ultimate-slider' )
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-control-options',
			'text',
			array(
				'id'            => 'styling-arrow-font-size',
				'title'         => __( 'Arrow Size', 'ultimate-slider' ),
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-control-options',
			'text',
			array(
				'id'            => 'styling-arrow-background-size',
				'title'         => __( 'Arrow Background Size', 'ultimate-slider' ),
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-control-options',
			'text',
			array(
				'id'            => 'styling-clickable-area-size',
				'title'         => __( 'Clickable Area Size', 'ultimate-slider' ),
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ewd-us-settings',
			'ewd-us-control-options',
			'text',
			array(
				'id'            => 'styling-arrow-line-height',
				'title'         => __( 'Line Height of Arrow Within Background (ex. "1.25")', 'ultimate-slider' ),
				'small'			=> true
			)
		);

		$sap = apply_filters( 'ewd_us_settings_page', $sap );

		$sap->add_admin_menus();

	}

}
} // endif;
