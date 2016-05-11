<?php
/**
 * Plugin Name: Drafty In Here
 * Version:     1.1.3
 * Plugin URI:  https://wordpress.org/plugins/drafty-in-here/
 * Author:      Aron Marriott-Smith <aron@atomace.com>
 * Author URI:  http://aronmarriottsmith.co.uk
 * Text Domain: drafty-in-here
 * Domain Path: /languages/
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Description: Get email notifications of draft post sitting in your WordPress Blog waiting to be published.
 */


/**
 * Prevent this script being called outside WordPress
 */
if ( ! defined('ABSPATH') ) {
	exit;
}

/**
 * Define a constant for use later
*/
if ( ! defined( 'DRAFTY_FILE' ) ) {
	define( 'DRAFTY_FILE', __FILE__ );
}


/**
 * Because we our plugin uses namespaces and composer we need at least PHP 5.3.2+ to run
 * If we have PHP 5.3.2+ installed we load the plugin
 * If we do not have at least PHP 5.3.2 installed we display an error
 */
if ( version_compare( PHP_VERSION, '5.3.2', '>=' ) ) {
	require_once( dirname( DRAFTY_FILE ) . '/drafty-main.php' );
}
else {
	is_admin() && add_action('admin_notices', create_function('', "
	echo '
		<div class=\"error\"><p>".
		__('Sorry Drafty In Here requires at least PHP 5.3.2 to function properly. Please upgrade PHP.', 'drafty-in-here')
		."</p></div>';"
	));
}
