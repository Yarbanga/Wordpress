<?php
/**
 * Plugin Name: Widget Click to Chat
 * Plugin URI: https://widgetwhats.com/?utm_source=wp-plugins&utm_campaign=plugin-uri&utm_medium=wp-dash
 * Description: Free Chat Widget for WhatsApp with page targeting and floating button style. Fully Customizable!
 * Version: 1.1
 * Author: Creame
 * Author URI: https://crea.me/
 */


if ( ! class_exists( 'WidgetWhats_Plugin' ) ) {
	class WidgetWhats_Plugin {

		/**
		 * Construct the plugin object
		 */
		public function __construct() {
			add_action( 'admin_init', array( &$this, 'admin_init' ) );
			add_action( 'admin_menu', array( &$this, 'add_menu' ) );
			add_action( 'wp_footer', array( &$this, 'insert_snippet' ) );
		} // END public function __construct

		/**
		 * Activate the plugin
		 */
		public static function activate() {
			// Do nothing
		} // END public static function activate

		/**
		 * Deactivate the plugin
		 */
		public static function deactivate() {
			// Do nothing
		} // END public static function deactivate

		/**
		 * hook into WP's admin_init action hook
		 */
		public function admin_init() {
			// Set up the settings for this plugin
			$this->init_settings();
			// Possibly do additional admin_init tasks
		} // END public static function activate

		/**
		 * Initialize some custom settings
		 */
		public function init_settings() {
			// register the settings for this plugin
			register_setting( 'widgetwhats-group', 'widgetwhatsID' );
			register_setting( 'widgetwhats-group', 'widgetwhatsCheckAll' );
			register_setting( 'widgetwhats-group', 'widgetwhatsCheckHomepage' );
			register_setting( 'widgetwhats-group', 'widgetwhatsCheckFrontpage' );
			register_setting( 'widgetwhats-group', 'widgetwhatsCheckPosts' );
			register_setting( 'widgetwhats-group', 'widgetwhatsCheckPages' );
			register_setting( 'widgetwhats-group', 'widgetwhatsCheckProducts' );
			register_setting( 'widgetwhats-group', 'widgetwhatsCheckArchive' );
			register_setting( 'widgetwhats-group', 'widgetwhatsInclude' );
			register_setting( 'widgetwhats-group', 'widgetwhatsExclude' );
		} // END public function init_custom_settings()

		/**
		 * add a menu
		 */
		public function add_menu() {
			add_menu_page(
				'WidgetWA - Integration',
				'WidgetWA',
				'manage_options',
				'widgetwhats',
				array( $this, 'plugin_settings_page' ),
				'dashicons-format-chat'
			);
		} // END public function add_menu()

		/**
		 * Menu Callback
		 */
		public function plugin_settings_page() {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}

			// Render the settings template
			include sprintf( '%s/settings.php', dirname( __FILE__ ) );
		} // END public function plugin_settings_page()

		function insert_snippet() {
			if ( get_option( 'widgetwhatsID' ) ) {
				$included_ids = explode( ',', get_option( 'widgetwhatsInclude' ) );
				$included_ids = is_array( $included_ids ) ? $included_ids : array();

						$excluded_ids = explode( ',', get_option( 'widgetwhatsExclude' ) );
				$excluded_ids         = is_array( $excluded_ids ) ? $excluded_ids : array();

				if ( is_singular() && in_array( get_the_ID(), $included_ids ) ) {
					echo '<script async data-id="' . get_option( 'widgetwhatsID' ) . '" src="https://cdn.widgetwhats.com/script.min.js"></script>' . PHP_EOL;
					return;
				}

				if ( get_option( 'widgetwhatsCheckAll' ) ) {
					if ( ! in_array( get_the_ID(), $excluded_ids ) ) {
						echo '<script async data-id="' . get_option( 'widgetwhatsID' ) . '" src="https://cdn.widgetwhats.com/script.min.js"></script>' . PHP_EOL;
					}
				} else {
					if ( ( is_front_page() && is_home() ) && ! get_option( 'widgetwhatsCheckHomepage' ) ) {
						return;
					}
					if ( is_front_page() && ! get_option( 'widgetwhatsCheckHomepage' ) ) {
						return;
					}
					if ( is_home() && ! get_option( 'widgetwhatsCheckFrontpage' ) ) {
						return;
					}
					if ( ( is_search() || is_archive() ) && ! get_option( 'widgetwhatsCheckArchive' ) ) {
						return;
					}
					if ( ! ( is_front_page() && is_home() ) && ! is_front_page() && is_singular( 'page' ) && ! get_option( 'widgetwhatsCheckPages' ) ) {
						return;
					}
					if ( get_post_type() === 'product' && ! get_option( 'widgetwhatsCheckProducts' ) ) {
						return;
					}
					if ( is_singular() && in_array( get_the_ID(), $excluded_ids ) ) {
						return;
					}
					if ( is_singular( 'post' ) && ! get_option( 'widgetwhatsCheckPosts' ) ) {
						return;
					}
					echo '<script async data-id="' . get_option( 'widgetwhatsID' ) . '" src="https://cdn.widgetwhats.com/script.min.js"></script>' . PHP_EOL;
				}
			}
		}
	}
}

if ( class_exists( 'WidgetWhats_Plugin' ) ) {
	// Installation and uninstallation hooks
	register_activation_hook( __FILE__, array( 'WidgetWhats_Plugin', 'activate' ) );
	register_deactivation_hook( __FILE__, array( 'WidgetWhats_Plugin', 'deactivate' ) );

	// instantiate the plugin class
	$widgetwhats = new WidgetWhats_Plugin();
}

// Add a link to the settings page onto the plugin page
if ( isset( $widgetwhats ) ) {
	// Add the settings link to the plugins page
	function plugin_settings_link( $links ) {
		$settings_link = '<a href="options-general.php?page=widgetwhats-app">Settings</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}

	$plugin = plugin_basename( __FILE__ );
	add_filter( "plugin_action_links_$plugin", 'plugin_settings_link' );
}

/**
 * Adds a simple WordPress pointer to Settings menu
 */
function widgetwhats_enqueue_pointer_script_style( $hook_suffix ) {
	// Assume pointer shouldn't be shown
	$enqueue_pointer_script_style = false;
	// Get array list of dismissed pointers for current user and convert it to array
	$dismissed_pointers = explode( ',', get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
	// Check if our pointer is not among dismissed ones
	if ( ! in_array( 'widgetwhats_settings_pointer', $dismissed_pointers ) ) {
		$enqueue_pointer_script_style = true;
		// Add footer scripts using callback function
		add_action( 'admin_print_footer_scripts', 'widgetwhats_pointer_print_scripts' );
	}
	// Enqueue pointer CSS and JS files, if needed
	if ( $enqueue_pointer_script_style ) {
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );
	}
}
add_action( 'admin_enqueue_scripts', 'widgetwhats_enqueue_pointer_script_style' );
function widgetwhats_pointer_print_scripts() {
	$pointer_content  = '<h3>First time installation?</h3>';
	$pointer_content .= '<p>Click this button to sign up first and start create your widget. You will be redirected to widgetwhats.com.</p>'; ?>
	<script type="text/javascript">
	//<![CDATA[
	jQuery(document).ready( function($) {
		$('.create-widget').pointer({
			content:		'<?php echo $pointer_content; ?>',
			position:		{
								edge:	'left', // arrow direction
								align:	'center' // vertical alignment
							},
			pointerWidth:	350,
			close:			function() {
								$.post( ajaxurl, {
										pointer: 'widgetwhats_settings_pointer', // pointer ID
										action: 'dismiss-wp-pointer'
								});
							}
		}).pointer('open');
	});
	//]]>
	</script>
<?php
}
