<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SSP_Bible_Readings {

	/**
	 * The single instance of SSP_Bible_Readings.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_version;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_token;

	/**
	 * The main plugin file.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $file;

	/**
	 * The main plugin directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $dir;

	/**
	 * The plugin assets directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_dir;

	/**
	 * The plugin assets URL.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_url;

	/**
	 * Suffix for Javascripts.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $script_suffix;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 */
	public function __construct ( $file = '', $version = '1.0.0' ) {
		global $wpdb;

		// Load plugin constants
		$this->_version = $version;
		$this->_token = 'ssp_bible_readings';

		// Load plugin environment variables
		$this->file = $file;
		$this->dir = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );
		$this->script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		register_activation_hook( $this->file, array( $this, 'install' ) );

		// Add custom field to episode data
		add_filter( 'ssp_episode_fields', array( $this, 'add_field' ), 10, 1 );

		// Add transcript download link to episode meta
		add_filter( 'ssp_episode_meta_details', array( $this, 'display_reading' ), 10, 3 );

		add_filter( 'ssp_settings_fields', array( $this, 'add_settings_field' ), 10, 1 );

	} // End __construct ()

	public function add_field ( $fields = array() ) {

		$fields['reading'] = array(
		    'name' => __( 'Bible reading:' , 'seriously-simple-bible-readings' ),
		    'description' => __( 'The Bible reading for this episode.' , 'seriously-simple-bible-readings' ),
		    'type' => 'text',
		    'default' => '',
		    'section' => 'info',
		);

		return $fields;
	}

	public function display_reading ( $meta = array(), $episode_id = 0, $context = '' ) {

		if( ! $episode_id ) {
			return $meta;
		}

		// Fetch the Bible reading for the episode
		$bible_reading = get_post_meta( $episode_id , 'reading' , true );

	    if( $bible_reading ) {

	    	// Get the Bible version to be used
	    	$version = $this->get_bible_version();

	    	// Format the Bible reading for the URL parameter
	        $param = strtolower( str_replace( ' ', '+', $bible_reading ) );

	        // Construct the Bible Gateway URL
	        $url = 'http://www.biblegateway.com/bible?language=en&version=' . $version . '&passage=' . $param;

	        // Add the Bible reading to the episode meta data
	        $meta['reading'] = '<a href="' . $url . '" target="_blank">' . $bible_reading . '</a>';
	    }

		return $meta;
	}

	public function get_bible_version () {

		$version = get_option( 'ss_podcasting_bible_version', 'NIV' );

		return $version;
	}

	public function add_settings_field ( $settings = array() ) {

		$version_options = array(
			'NIV' => __( 'New Interntional Version (NIV)', 'seriously-simple-bible-readings' ),
			'ESV' => __( 'English Standard Version (ESV)', 'seriously-simple-bible-readings' ),
		);

		$settings['general']['fields'][] = array(
			'id'          => 'bible_version',
			'label'       => __( 'Bible version', 'seriously-simple-bible-readings' ),
			'description' => __( 'The version of the Bible to use for your episode Bible readings.', 'seriously-simple-bible-readings' ),
			'type'        => 'select',
			'options'     => $version_options,
			'default'     => 'NIV',
			'callback'    => 'wp_strip_all_tags',
		);

		return $settings;
	}

	/**
	 * Main SSP_Transcripts Instance
	 *
	 * Ensures only one instance of SSP_Transcripts is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see SSP_Transcripts()
	 * @return Main SSP_Transcripts instance
	 */
	public static function instance ( $file = '', $version = '1.0.0', $db_version = '1.0.0' ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version, $db_version );
		}
		return self::$_instance;
	} // End instance ()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __clone ()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __wakeup ()

	/**
	 * Installation. Runs on activation.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install () {
		$this->_log_version_number();
	} // End install ()

	/**
	 * Log the plugin version number.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number () {
		update_option( $this->_token . '_version', $this->_version );
	} // End _log_version_number ()

}
