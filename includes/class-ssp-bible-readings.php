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
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 */
	public function __construct ( $file = '', $version = '1.0.0' ) {
		global $wpdb;

		// Load plugin constants
		$this->_version = $version;
		$this->_token = 'ssp_bible_readings';

		register_activation_hook( $file, array( $this, 'install' ) );

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
		$bible_reading = apply_filters( 'ssp_bible_readings_reading', get_post_meta( $episode_id, 'reading', true ), $episode_id );

	    if( $bible_reading ) {

	    	// Format the Bible reading for the biblegateway.com URL parameter
	        $param = strtolower( str_replace( ' ', '+', $bible_reading ) );

	    	// Get the Bible version to be used
	    	$version = $this->get_bible_version( $episode_id );

	        // Construct the Bible Gateway URL
	        $url = 'https://www.biblegateway.com/passage/?search=' . $param . '&version='. $version;

	        // Allow dynamic filtering of the URL so other sites can be used
	        $url = apply_filters( 'ssp_bible_readings_url', $url, $episode_id, $bible_reading, $version );

	        // Add the Bible reading to the episode meta data
	        $meta['bible_reading'] = '<a href="' . $url . '" target="_blank">' . $bible_reading . '</a>';
	    }

		return $meta;
	}

	public function get_bible_version ( $episode_id = 0 ) {

		$version = get_option( 'ss_podcasting_bible_version', 'NIV' );

		return apply_filters( 'ssp_bible_readings_version', $version, $episode_id );
	}

	public function add_settings_field ( $settings = array() ) {

		$version_options = array(
			'KJ21' => __( '21st Century King James Version (KJ21)', 'seriously-simple-bible-readings' ),
			'ASV' => __( 'American Standard Version (ASV)', 'seriously-simple-bible-readings' ),
			'AMP' => __( 'Amplified Bible (AMP)', 'seriously-simple-bible-readings' ),
			'AMPC' => __( 'Amplified Bible, Classic Edition (AMPC)', 'seriously-simple-bible-readings' ),
			'BRG' => __( 'BRG Bible (BRG)', 'seriously-simple-bible-readings' ),
			'CSB' => __( 'Christian Standard Bible (CSB)', 'seriously-simple-bible-readings' ),
			'CEB' => __( 'Common English Bible (CEB)', 'seriously-simple-bible-readings' ),
			'CJB' => __( 'Complete Jewish Bible (CJB)', 'seriously-simple-bible-readings' ),
			'CEV' => __( 'Contemporary English Version (CEV)', 'seriously-simple-bible-readings' ),
			'DARBY' => __( 'Darby Translation (DARBY)', 'seriously-simple-bible-readings' ),
			'DLNT' => __( 'Disciples’ Literal New Testament (DLNT)', 'seriously-simple-bible-readings' ),
			'DRA' => __( 'Douay-Rheims 1899 American Edition (DRA)', 'seriously-simple-bible-readings' ),
			'ERV' => __( 'Easy-to-Read Version (ERV)', 'seriously-simple-bible-readings' ),
			'EHV' => __( 'Evangelical Heritage Version (EHV)', 'seriously-simple-bible-readings' ),
			'ESV' => __( 'English Standard Version (ESV)', 'seriously-simple-bible-readings' ),
			'ESVUK' => __( 'English Standard Version Anglicised (ESVUK)', 'seriously-simple-bible-readings' ),
			'EXB' => __( 'Expanded Bible (EXB)', 'seriously-simple-bible-readings' ),
			'GNV' => __( '1599 Geneva Bible (GNV)', 'seriously-simple-bible-readings' ),
			'GW' => __( 'GOD’S WORD Translation (GW)', 'seriously-simple-bible-readings' ),
			'GNT' => __( 'Good News Translation (GNT)', 'seriously-simple-bible-readings' ),
			'HCSB' => __( 'Holman Christian Standard Bible (HCSB)', 'seriously-simple-bible-readings' ),
			'ICB' => __( 'International Children’s Bible (ICB)', 'seriously-simple-bible-readings' ),
			'ISV' => __( 'International Standard Version (ISV)', 'seriously-simple-bible-readings' ),
			'PHILLIPS' => __( 'J.B. Phillips New Testament (PHILLIPS)', 'seriously-simple-bible-readings' ),
			'JUB' => __( 'Jubilee Bible 2000 (JUB)', 'seriously-simple-bible-readings' ),
			'KJV' => __( 'King James Version (KJV)', 'seriously-simple-bible-readings' ),
			'AKJV' => __( 'Authorized (King James) Version (AKJV)', 'seriously-simple-bible-readings' ),
			'LEB' => __( 'Lexham English Bible (LEB)', 'seriously-simple-bible-readings' ),
			'TLB' => __( 'Living Bible (TLB)', 'seriously-simple-bible-readings' ),
			'MSG' => __( 'The Message (MSG)', 'seriously-simple-bible-readings' ),
			'MEV' => __( 'Modern English Version (MEV)', 'seriously-simple-bible-readings' ),
			'MOUNCE' => __( 'Mounce Reverse-Interlinear New Testament (MOUNCE)', 'seriously-simple-bible-readings' ),
			'NOG' => __( 'Names of God Bible (NOG)', 'seriously-simple-bible-readings' ),
			'NABRE' => __( 'New American Bible (Revised Edition) (NABRE)', 'seriously-simple-bible-readings' ),
			'NASB' => __( 'New American Standard Bible (NASB)', 'seriously-simple-bible-readings' ),
			'NCV' => __( 'New Century Version (NCV)', 'seriously-simple-bible-readings' ),
			'NET' => __( 'New English Translation (NET Bible)', 'seriously-simple-bible-readings' ),
			'NIRV' => __( 'New International Reader\'s Version (NIRV)', 'seriously-simple-bible-readings' ),
			'NIV' => __( 'New International Version (NIV)', 'seriously-simple-bible-readings' ),
			'NIVUK' => __( 'New International Version - UK (NIVUK)', 'seriously-simple-bible-readings' ),
			'NKJV' => __( 'New King James Version (NKJV)', 'seriously-simple-bible-readings' ),
			'NLV' => __( 'New Life Version (NLV)', 'seriously-simple-bible-readings' ),
			'NLT' => __( 'New Living Translation (NLT)', 'seriously-simple-bible-readings' ),
			'NMB' => __( 'New Matthew Bible (NMB)', 'seriously-simple-bible-readings' ),
			'NRSV' => __( 'New Revised Standard Version (NRSV)', 'seriously-simple-bible-readings' ),
			'NRSVA' => __( 'New Revised Standard Version, Anglicised (NRSVA)', 'seriously-simple-bible-readings' ),
			'NRSVACE' => __( 'New Revised Standard Version, Anglicised Catholic Edition (NRSVACE)', 'seriously-simple-bible-readings' ),
			'NRSVCE' => __( 'New Revised Standard Version Catholic Edition (NRSVCE)', 'seriously-simple-bible-readings' ),
			'NTE' => __( 'New Testament for Everyone (NTE)', 'seriously-simple-bible-readings' ),
			'OJB' => __( 'Orthodox Jewish Bible (OJB)', 'seriously-simple-bible-readings' ),
			'TPT' => __( 'The Passion Translation (TPT)', 'seriously-simple-bible-readings' ),
			'RGT' => __( 'Revised Geneva Translation (RGT)', 'seriously-simple-bible-readings' ),
			'RSV' => __( 'Revised Standard Version (RSV)', 'seriously-simple-bible-readings' ),
			'RSVCE' => __( 'Revised Standard Version Catholic Edition (RSVCE)', 'seriously-simple-bible-readings' ),
			'TLV' => __( 'Tree of Life Version (TLV)', 'seriously-simple-bible-readings' ),
			'VOICE' => __( 'The Voice (VOICE)', 'seriously-simple-bible-readings' ),
			'WEB' => __( 'World English Bible (WEB)', 'seriously-simple-bible-readings' ),
			'WE' => __( 'Worldwide English (New Testament) (WE)', 'seriously-simple-bible-readings' ),
			'WYC' => __( 'Wycliffe Bible (WYC)', 'seriously-simple-bible-readings' ),
			'YLT' => __( 'Young\'s Literal Translation (YLT)', 'seriously-simple-bible-readings' ),
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
	public static function instance ( $file = '', $version = '1.0.0' ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version );
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
