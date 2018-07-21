<?php
/**
 *
 * @link              https://github.com/jspellman814/jswp-primary-term
 * @since             1.0.0
 * @package           JSWP_Primary_Term
 *
 * @wordpress-plugin
 * Plugin Name:       JSWP Primary Term
 * Plugin URI:        https://github.com/jspellman814/jswp-primary-term
 * Description:       WordPress plugin that allows authors to select a primary term if multiple categories are chosen.
 * Version:           1.0.0
 * Author:            John Spellman
 * Author URI:        http://johnjspellman.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       jswp
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'JSWP_PRIMARY_TERM', '1.0.0' );

/**
 * Load our admin class when in the admin dashboard
 */
if ( is_admin() ) {
	require plugin_dir_path( __FILE__ ) . 'admin/class-jswp-primary-term-admin.php';
}