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
		    'name' => __( 'Bible reading:' , 'bible-readings-for-seriously-simple' ),
		    'description' => __( 'The Bible reading for this episode.' , 'bible-readings-for-seriously-simple' ),
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
			'KJ21' => __( '21st Century King James Version (KJ21)', 'bible-readings-for-seriously-simple' ),
			'ASV' => __( 'American Standard Version (ASV)', 'bible-readings-for-seriously-simple' ),
			'AMP' => __( 'Amplified Bible (AMP)', 'bible-readings-for-seriously-simple' ),
			'AMPC' => __( 'Amplified Bible, Classic Edition (AMPC)', 'bible-readings-for-seriously-simple' ),
			'BRG' => __( 'BRG Bible (BRG)', 'bible-readings-for-seriously-simple' ),
			'CSB' => __( 'Christian Standard Bible (CSB)', 'bible-readings-for-seriously-simple' ),
			'CEB' => __( 'Common English Bible (CEB)', 'bible-readings-for-seriously-simple' ),
			'CJB' => __( 'Complete Jewish Bible (CJB)', 'bible-readings-for-seriously-simple' ),
			'CEV' => __( 'Contemporary English Version (CEV)', 'bible-readings-for-seriously-simple' ),
			'DARBY' => __( 'Darby Translation (DARBY)', 'bible-readings-for-seriously-simple' ),
			'DLNT' => __( 'Disciples’ Literal New Testament (DLNT)', 'bible-readings-for-seriously-simple' ),
			'DRA' => __( 'Douay-Rheims 1899 American Edition (DRA)', 'bible-readings-for-seriously-simple' ),
			'ERV' => __( 'Easy-to-Read Version (ERV)', 'bible-readings-for-seriously-simple' ),
			'EHV' => __( 'Evangelical Heritage Version (EHV)', 'bible-readings-for-seriously-simple' ),
			'ESV' => __( 'English Standard Version (ESV)', 'bible-readings-for-seriously-simple' ),
			'ESVUK' => __( 'English Standard Version Anglicised (ESVUK)', 'bible-readings-for-seriously-simple' ),
			'EXB' => __( 'Expanded Bible (EXB)', 'bible-readings-for-seriously-simple' ),
			'GNV' => __( '1599 Geneva Bible (GNV)', 'bible-readings-for-seriously-simple' ),
			'GW' => __( 'GOD’S WORD Translation (GW)', 'bible-readings-for-seriously-simple' ),
			'GNT' => __( 'Good News Translation (GNT)', 'bible-readings-for-seriously-simple' ),
			'HCSB' => __( 'Holman Christian Standard Bible (HCSB)', 'bible-readings-for-seriously-simple' ),
			'ICB' => __( 'International Children’s Bible (ICB)', 'bible-readings-for-seriously-simple' ),
			'ISV' => __( 'International Standard Version (ISV)', 'bible-readings-for-seriously-simple' ),
			'PHILLIPS' => __( 'J.B. Phillips New Testament (PHILLIPS)', 'bible-readings-for-seriously-simple' ),
			'JUB' => __( 'Jubilee Bible 2000 (JUB)', 'bible-readings-for-seriously-simple' ),
			'KJV' => __( 'King James Version (KJV)', 'bible-readings-for-seriously-simple' ),
			'AKJV' => __( 'Authorized (King James) Version (AKJV)', 'bible-readings-for-seriously-simple' ),
			'LEB' => __( 'Lexham English Bible (LEB)', 'bible-readings-for-seriously-simple' ),
			'TLB' => __( 'Living Bible (TLB)', 'bible-readings-for-seriously-simple' ),
			'MSG' => __( 'The Message (MSG)', 'bible-readings-for-seriously-simple' ),
			'MEV' => __( 'Modern English Version (MEV)', 'bible-readings-for-seriously-simple' ),
			'MOUNCE' => __( 'Mounce Reverse-Interlinear New Testament (MOUNCE)', 'bible-readings-for-seriously-simple' ),
			'NOG' => __( 'Names of God Bible (NOG)', 'bible-readings-for-seriously-simple' ),
			'NABRE' => __( 'New American Bible (Revised Edition) (NABRE)', 'bible-readings-for-seriously-simple' ),
			'NASB' => __( 'New American Standard Bible (NASB)', 'bible-readings-for-seriously-simple' ),
			'NCV' => __( 'New Century Version (NCV)', 'bible-readings-for-seriously-simple' ),
			'NET' => __( 'New English Translation (NET Bible)', 'bible-readings-for-seriously-simple' ),
			'NIRV' => __( 'New International Reader\'s Version (NIRV)', 'bible-readings-for-seriously-simple' ),
			'NIV' => __( 'New International Version (NIV)', 'bible-readings-for-seriously-simple' ),
			'NIVUK' => __( 'New International Version - UK (NIVUK)', 'bible-readings-for-seriously-simple' ),
			'NKJV' => __( 'New King James Version (NKJV)', 'bible-readings-for-seriously-simple' ),
			'NLV' => __( 'New Life Version (NLV)', 'bible-readings-for-seriously-simple' ),
			'NLT' => __( 'New Living Translation (NLT)', 'bible-readings-for-seriously-simple' ),
			'NMB' => __( 'New Matthew Bible (NMB)', 'bible-readings-for-seriously-simple' ),
			'NRSV' => __( 'New Revised Standard Version (NRSV)', 'bible-readings-for-seriously-simple' ),
			'NRSVA' => __( 'New Revised Standard Version, Anglicised (NRSVA)', 'bible-readings-for-seriously-simple' ),
			'NRSVACE' => __( 'New Revised Standard Version, Anglicised Catholic Edition (NRSVACE)', 'bible-readings-for-seriously-simple' ),
			'NRSVCE' => __( 'New Revised Standard Version Catholic Edition (NRSVCE)', 'bible-readings-for-seriously-simple' ),
			'NTE' => __( 'New Testament for Everyone (NTE)', 'bible-readings-for-seriously-simple' ),
			'OJB' => __( 'Orthodox Jewish Bible (OJB)', 'bible-readings-for-seriously-simple' ),
			'TPT' => __( 'The Passion Translation (TPT)', 'bible-readings-for-seriously-simple' ),
			'RGT' => __( 'Revised Geneva Translation (RGT)', 'bible-readings-for-seriously-simple' ),
			'RSV' => __( 'Revised Standard Version (RSV)', 'bible-readings-for-seriously-simple' ),
			'RSVCE' => __( 'Revised Standard Version Catholic Edition (RSVCE)', 'bible-readings-for-seriously-simple' ),
			'TLV' => __( 'Tree of Life Version (TLV)', 'bible-readings-for-seriously-simple' ),
			'VOICE' => __( 'The Voice (VOICE)', 'bible-readings-for-seriously-simple' ),
			'WEB' => __( 'World English Bible (WEB)', 'bible-readings-for-seriously-simple' ),
			'WE' => __( 'Worldwide English (New Testament) (WE)', 'bible-readings-for-seriously-simple' ),
			'WYC' => __( 'Wycliffe Bible (WYC)', 'bible-readings-for-seriously-simple' ),
			'YLT' => __( 'Young\'s Literal Translation (YLT)', 'bible-readings-for-seriously-simple' ),
		);

		$settings['general']['fields'][] = array(
			'id'          => 'bible_version',
			'label'       => __( 'Bible version', 'bible-readings-for-seriously-simple' ),
			'description' => __( 'The version of the Bible to use for your episode Bible readings.', 'bible-readings-for-seriously-simple' ),
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
