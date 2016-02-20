<?php
/**
 * Plugin Name: Drafty In Here
 * Version:     1.1.0
 * Plugin URI:
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
 * Because we our plugin uses namespaces we need at least php5.3 to run
 * If we have php5.3 installed we load the plugin
 * If we do not have php5.3 installed we display an error
 */
if ( version_compare( PHP_VERSION, '5.3.0', '>=' ) ) {
	require_once 'drafty-main.php';
}
else {
	is_admin() && add_action('admin_notices', create_function('', "
	echo '
		<div class=\"error\"><p>".
		__('Sorry Drafty In Here requires PHP 5.3 to function properly. Please upgrade PHP.', 'drafty-in-here')
		."</p></div>';"
	));
}
