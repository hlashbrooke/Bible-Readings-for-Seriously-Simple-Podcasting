<?php
/*
 * Plugin Name: Bible Readings for Seriously Simple Podcasting
 * Version: 1.0
 * Plugin URI: https://wordpress.org/plugins/bible-readings-seriously-simple
 * Description: Add linked Bible readings to sermons published with Seriously Simple Podcasting.
 * Author: Hugh Lashbrooke
 * Author URI: https://hugh.blog/
 * Requires at least: 4.4
 * Tested up to: 5.3
 *
 * Text Domain: bible-readings-for-seriously-simple
 *
 * @package WordPress
 * @author Hugh Lashbrooke
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'is_ssp_active' ) ) {
	require_once( 'ssp-includes/ssp-functions.php' );
}

if( is_ssp_active( '1.15.0' ) ) {

	// Load plugin class files
	require_once( 'includes/class-ssp-bible-readings.php' );

	/**
	 * Returns the main instance of SSP_Bible_Readings to prevent the need to use globals.
	 *
	 * @since  1.0.0
	 * @return object SSP_Bible_Readings
	 */
	function SSP_Bible_Readings () {
		$instance = SSP_Bible_Readings::instance( __FILE__, '1.0.0' );
		return $instance;
	}

	SSP_Bible_Readings();

}
