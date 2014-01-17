<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Pootlepress_Align_Right Class
 *
 * Base class for the Pootlepress Right Align.
 *
 * @package WordPress
 * @subpackage Pootlepress_Align_Right
 * @category Core
 * @author Pootlepress
 * @since 1.0.0
 *
 * TABLE OF CONTENTS
 *
 * public $token
 * public $version
 * private $_menu_style
 * 
 * - __construct()
 * - add_theme_options()
 * - load_localisation()
 * - check_plugin()
 * - load_plugin_textdomain()
 * - activation()
 * - register_plugin_version()
 * - load_align_right()
 */
class Pootlepress_Align_Right {
	public $token = 'pootlepress-align-right';
	public $version;
	private $file;
	private $_menu_style;

	/**
	 * Constructor.
	 * @param string $file The base file of the plugin.
	 * @access public
	 * @since  1.0.0
	 * @return  void
	 */
	public function __construct ( $file ) {
		$this->file = $file;
		$this->load_plugin_textdomain();
		add_action( 'init','check_main_heading', 0 );
		add_action( 'init', array( &$this, 'load_localisation' ), 0 );

		// Run this on activation.
		register_activation_hook( $file, array( &$this, 'activation' ) );

		// Add the custom theme options.
		add_filter( 'option_woo_template', array( &$this, 'add_theme_options' ) );

		// Lood for a method/function for the selected style and load it.
		add_action('init', array( &$this, 'load_align_right' ) );
	} // End __construct()

	/**
	 * Add theme options to the WooFramework.
	 * @access public
	 * @since  1.0.0
	 * @param array $o The array of options, as stored in the database.
	 */	
	public function add_theme_options ( $o ) {
		$o[] = array(
				'name' => 'Align Menu Right', 
				'type' => 'subheading'
				);
		$o[] = array(
				'name' => 'Full Width',
				'desc' => '',
				'id' => 'pootlepress-align-right-notice',
				'std' => sprintf(("The Align Right plugin does not currently work with the full-width button. Find more plugins here <a href=\"%s\" target=\"_blank\">http://www.pootlepress.com/shop</a>" ), "http://www.pootlepress.com/shop" ),
				'type' => 'info'
				);
		$o[] = array(
				'id' => 'pootlepress-align-right-option', 
				'name' => __( 'Enable or Disable the Right Align', 'pootlepress-align-right' ), 
				'desc' => __( 'Click here to enable or disable the Pootlepress sticky nav', 'pootlepress-align-right' ), 
				'std' => 'true',
				'type' => 'checkbox'
				);
		return $o;
	} // End add_theme_options()
	
	/**
	 * Load the plugin's localisation file.
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function load_localisation () {
		load_plugin_textdomain( $this->token, false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_localisation()
	
	/**
	 * Load the plugin textdomain from the main WordPress "languages" folder.
	 * @access public
	 * @since  1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain () {
	    $domain = $this->token;
	    // The "plugin_locale" filter is also used in load_plugin_textdomain()
	    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );
	 
	    load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
	    load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_plugin_textdomain()

	/**
	 * Run on activation.
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function activation () {
		$this->register_plugin_version();
	} // End activation()

	/**
	 * Register the plugin's version.
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	private function register_plugin_version () {
		if ( $this->version != '' ) {
			update_option( $this->token . '-version', $this->version );
		}
	} // End register_plugin_version()

	/**
	 * Load the right align files
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_align_right () {
		$_rightalignenabled  = get_option('pootlepress-align-right-option');
		if ($_rightalignenabled == '') $enabled = 'true';
		if ($_rightalignenabled == 'true') {
			wp_enqueue_style(esc_attr('rightalign'), esc_url(plugins_url('styles/rightalign.css', $this->file)));
			remove_action('woo_header_after','woo_nav', 10);
			add_action('woo_header_inside', 'woo_nav', 10);
		}
	} // End load_align_right()
	

} // End Class


