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

    private $enabled;

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

        add_action( 'wp_enqueue_scripts', array( &$this, 'poo_hookup_scripts'), 100);
        add_action( 'wp_head', array( &$this, 'poo_inline_javascript'), 10 );

		// Lood for a method/function for the selected style and load it.
		add_action('init', array( &$this, 'load_align_right' ) );

//        add_action('wp_head', array($this, 'option_css'));

        $this->enabled = get_option('pootlepress-align-right-option', 'true') == 'true';

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
				'name' => 'Support',
				'desc' => '',
				'id' => 'pootlepress-align-right-notice',
				'std' => sprintf(("Thanks for using this free Canvas Extension. Please email support@pootlpress.com if you experience any problems. Find more Canvas Extensions here <a href=\"%s\" target=\"_blank\">http://www.pootlepress.com/shop</a>" ), "http://www.pootlepress.com/shop" ),
				'type' => 'info'
				);
		$o[] = array(
				'id' => 'pootlepress-align-right-option', 
				'name' => __( 'Align Menu Right', 'pootlepress-align-right' ), 
				'desc' => __( 'Enable Align Menu Right', 'pootlepress-align-right' ), 
				'std' => 'true',
				'type' => 'checkbox'
				);
		return $o;
	} // End add_theme_options()

    public function poo_hookup_scripts() {

        if ($this->enabled) {
            wp_enqueue_script('align-menu-right',
                plugins_url( '../align-menu-right.js' , __FILE__ ),
                array( 'jquery' ),
                false, false
            );
        }
    }

    // generate plugin custom inline javascript - driven by theme options
    public function poo_inline_javascript() {
        if ($this->enabled) {
            echo "\n" . '<!-- Sticky Header Inline Javascript -->' . "\n";
            echo '<script>' . "\n";
            echo "	/* set global variable for pootlepress common component area  */\n";
            echo '	if (typeof pootlepress === "undefined") { var pootlepress = {} }' . "\n";
            echo '	jQuery(document).ready(function($) {' . "\n";
            echo $this->poo_inline_styling_javascript();
            echo '	});' . "\n";
            echo '</script>' . "\n";
        }
    }

    /**
     * Generate the javascript statement to invoke the Sticky Header jQuery function
     * with the options based on the settings of the theme options.
     * @access private
     * @since  2.0.1
     * @return  (String) javascript command to embed in the HTML document
     */
    private function poo_inline_styling_javascript() {
        global $woo_options;
        $output = '    ';
        /* ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- */
        /* ----- Sticky Header Options   ----- ----- ----- ----- ----- ----- ----- ----- ----- */
        $_stickyenabled  	= 'false';
        $_wpadminbarhide 	= 'false';
        $_alignr = $this->enabled;
//        $_stickyMobileEnabled = get_option('pootlepress-sticky-header-sticky-mobile', 'false');
        $_stickyMobileEnabled = 'false';
        $_fixed_mobile_layout = get_option('woo_remove_responsive', 'false');
        if ($_fixed_mobile_layout == 'true')				// if responsive disabled
            $_responsive = 'false'; else $_responsive = 'true';
        $_opacity = 100;

        $borderTop = get_option('woo_border_top');
        if ($borderTop && isset($borderTop['width']) && $borderTop['width'] > 0) {
            $borderTopJson = json_encode($borderTop);
        } else {
            $borderTopJson = json_encode(false);
        }

        $layoutWidth = (int)get_option('woo_layout_width');


        $output .= "    $('#header').alignMenuRight( { stickyhdr : $_stickyenabled";
        $output .= ", stickynav : $_stickyenabled";
        $output .= ", alignright : $_alignr";
        $output .= ", mobile : $_stickyMobileEnabled";
        $output .= ", responsive : $_responsive";
        $output .= ", opacity : $_opacity";
        $output .= ", wpadminbar : $_wpadminbarhide";
        $output .= ", bordertop : $borderTopJson";
        $output .= ", layoutWidth: $layoutWidth";
        $output .= ' });' . "\n";
        return $output;
    }
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

    public function front_end_style() {
        wp_enqueue_style(esc_attr('rightalign'), esc_url(plugins_url('styles/rightalign.css', $this->file)));
    }

	public function load_align_right () {
		$_rightalignenabled  = get_option('pootlepress-align-right-option');
		$_stickyenabled  = get_option('pootlepress-sticky-nav-option');
		if ($_rightalignenabled == '') $enabled = 'true';
		if ($_rightalignenabled == 'true') {
			add_action('wp_enqueue_scripts', array($this, 'front_end_style'));

			remove_action('woo_header_after','woo_nav', 10);
			/*$activeplugins = get_option('active_plugins');
			if (in_array('cx-menu-pack/pootlepress-menu-pack.php',$activeplugins)) {
				$menuoption = get_option('pootlepress-menu-pack-menu-style');
				if ($menuoption == 'beautiful_type') {
					add_action( 'woo_header_after','woo_nav_beautiful_type', 10 ); 		
				} else if ($menuoption != 'none') {
					add_action('woo_header_inside', 'woo_nav_custom', 10);
				} else {
					add_action('woo_header_inside', 'woo_nav', 10);
				}
			} else {
				add_action('woo_header_inside', 'woo_nav', 10);
			}*/
			add_action('woo_header_inside', 'woo_nav', 10);
			if ($_stickyenabled == 'true') {
				add_action('wp_footer', 'pp_ar_fixStickyMobile',10);
			}
		}
	} // End load_align_right()


} // End Class


