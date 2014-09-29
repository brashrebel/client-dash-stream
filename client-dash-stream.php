<?php
/*
Plugin Name: Client Dash Stream
Plugin URI: http://clientdash.io
Description: Brings the power of Stream into Client Dash.
Version: 0.1
Author: Kyle Maurer
Author URI: http://kyleblog.net
License: GPL2
*/

/**
 * The function to launch our plugin.
 *
 * This entire class is wrapped in this function because we have to ensure that Client Dash has been loaded before our
 * extension.
 *
 * NOTE: This function needs to be changed to whatever your extension is. Also change it at the bottom under
 * "add_action( 'cd_stream'...".
 *
 * ALSO NOTE: You also need to change the function name "_cd_stream_notice" to something else. Both way at the
 * bottom, and also right here, under "add_action( 'admin_notices'..."
 *
 * Please and thank you.
 */
function cd_stream() {
	if ( ! class_exists( 'ClientDash' ) ) {

		// Change me! Change me to the name of the notice function at the bottom
		add_action( 'admin_notices', '_cd_stream_notice' );

		return;
	}

	class CDStream extends ClientDash {

		public $ID = 'cdstream';

		private $page = 'Reports';

		private $tab = 'Stream';

		private $settings_tab = 'Stream';

		private $section_name = 'Stream';

		/**
		 * This is the current version of your plugin. Keep it up to do date!
		 */
		public $version = '0.1';

		public static $_path;

		/**
		 * This constructor function sets up what happens when the plugin is activated. It is where you'll place all your
		 * actions, filters and other setup components.
		 *
		 * Don't worry about messing with this function.
		 */
		public function __construct() {

			// Register our styles
			add_action( 'admin_init', array( $this, 'register_styles' ) );

			// Add our styles conditionally
			add_action( 'admin_enqueue_scripts', array( $this, 'add_styles' ) );

			// Add our new content section
			$this->add_content_section(
				array(
					'name'     => $this->section_name,
					'tab'      => $this->tab,
					'page'     => $this->page,
					'callback' => array( $this, 'reports_output' )
				)
			);
			$this->add_content_section(
				array(
					'name'     => 'Your Activity',
					'tab'      => 'Activity',
					'page'     => 'Account',
					'callback' => array( $this, 'activity_output' )
				)
			);

			// Set the plugin path
			$this::$_path = plugin_dir_path( __FILE__ );
		}

		/**
		 * Register our styles.
		 *
		 * Feel free to modify or add to this example with your own.
		 */
		public function register_styles() {

			wp_register_style(
				"$this->ID-style",
				plugins_url( 'style.css', __FILE__ ),
				null,
				$this->version
			);
		}

		/**
		 * Add our styles.
		 *
		 * If you want the styles to show up on the entire back-end, simply remove all but:
		 * wp_enqueue_style( "$this->ID-style" );
		 *
		 * Feel free to modify or add to this example with your own.
		 */
		public function add_styles() {

			$current_page = isset( $_GET['page'] ) ? $_GET['page'] : null;
			$current_tab  = isset( $_GET['tab'] ) ? $_GET['tab'] : null;

			$page_ID         = $this->translate_name_to_id( $this->page );
			$tab_ID          = $this->translate_name_to_id( $this->tab );
			$settings_tab_ID = $this->translate_name_to_id( $this->settings_tab );

			// Only add style if on extension tab or on extension settings tab
			if ( ( $current_page == $page_ID && $current_tab == $tab_ID )
			     || ( $current_page == 'cd_settings' && $current_tab == $settings_tab_ID )
			     || ( $current_page == 'cd_account' && $current_tab == 'activity' )
			) {
				wp_enqueue_style( "$this->ID-style" );
			}
		}

		/**
		 * Our section output.
		 *
		 * This is where all of the content section content goes! Add anything you like to this function.
		 *
		 * Feel free to modify or add to this example with your own.
		 */
		public function reports_output() {

			// CHANGE THIS
			echo 'This is where your new content section\'s content goes.';
		}

		public function activity_output() {
			echo 'bla';
		}
	}

	// Instantiate the class
	$CDStream = new CDStream();

	// Include the file for your plugin settings. Simply remove or comment this line to disable the settings
	// Remove if you don't want settings
	//include_once( "{$CDStream::$_path}inc/settings.php" );

	// Include the file for your plugin widget. Simply remove or comment this line to disable the widget
	// Remove if you don't want widgets
	//include_once( "{$CDStream::$_path}inc/widgets.php" );

	// Include the file for your plugin menus. Simply remove or comment this line to disable the widget
	// Remove if you don't want menus
	include_once( "{$CDStream::$_path}inc/menus.php" );
}

// Change me! Change me to the name of the function at the top.
add_action( 'plugins_loaded', 'cd_stream' );

/**
 * Notices for if CD is not active.
 *
 * Change me! Change my name to something unique (and also change the add_action at the top of the file).
 */
function _cd_stream_notice() {

	?>
	<div class="error">
		<p>You have activated a plugin that requires <a href="http://w.org/plugins/client-dash">Client Dash</a>
			version 1.6 or greater.
			Please install and activate <strong>Client Dash</strong> to continue using.</p>
	</div>
<?php
}