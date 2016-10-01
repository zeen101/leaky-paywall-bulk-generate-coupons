<?php 

/**
* Load the base class
*/
class Bulk_Generate_Coupons {
	
	function __construct()	{
		
	}

	/**
	 * Kick it off
	 * 
	 */
	public function run() {

		self::setup_constants();
		self::includes();

		add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts' ) );

	}

	public function load_scripts() {
		
		wp_enqueue_script( 'bulk-generate-script', BULK_GENERATE_COUPONS_PLUGIN_URL . 'js/script.js', array('jquery-ui-datepicker') );
		wp_enqueue_style( 'jquery-ui-css', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css' );
		
	}

	/**
	 * Setup plugin constants.
	 *
	 * @access private
	 * @since 1.0
	 * @return void
	 */
	private function setup_constants() {

		// Plugin version.
		if ( ! defined( 'BULK_GENERATE_COUPONS_VERSION' ) ) {
			define( 'BULK_GENERATE_COUPONS_VERSION', '1.0.0' );
		}

		// Plugin Folder Path.
		if ( ! defined( 'BULK_GENERATE_COUPONS_PLUGIN_DIR' ) ) {
			define( 'BULK_GENERATE_COUPONS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}

		// Plugin Folder URL.
		if ( ! defined( 'BULK_GENERATE_COUPONS_PLUGIN_URL' ) ) {
			define( 'BULK_GENERATE_COUPONS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

		// Plugin Root File.
		if ( ! defined( 'BULK_GENERATE_COUPONS_PLUGIN_FILE' ) ) {
			define( 'BULK_GENERATE_COUPONS_PLUGIN_FILE', __FILE__ );
		}

	}

	/**
	 * Include required files.
	 *
	 * @access private
	 * @since 1.0
	 * @return void
	 */
	private function includes() {
		global $bulk_generate_coupons_options;

		require_once BULK_GENERATE_COUPONS_PLUGIN_DIR . '/admin/settings/register-settings.php';

		$admin = new Bulk_Generate_Coupons_Admin();  

		// $bulk_generate_coupons_options = this_plugin_get_settings();

	}

}