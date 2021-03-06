<?php
/**
 * Prevent this script being called outside WordPress
 */
if ( ! defined('ABSPATH') ) {
	exit;
}

/**
 * Define some constants to keep or code DRY
*/
if ( ! defined( 'DRAFTY_BASENAME' ) ) {
	define( 'DRAFTY_BASENAME', plugin_basename( DRAFTY_FILE ) );
}
if ( ! defined( 'DRAFTY_PATH' ) ) {
	define( 'DRAFTY_PATH', plugin_dir_path( DRAFTY_FILE ) );
}

/**
 * Load our plugin dependencies
 */
require_once DRAFTY_PATH . '/vendor/autoload.php';
use Drafty\Repositories\Admin\Admin;
use Drafty\Repositories\Email\Email;
use Drafty\Repositories\Options\Options;
use Drafty\Repositories\Scheduler\Scheduler;

/**
 * Instantiated version of our plugin class
 */
$GLOBALS['Drafty_In_Here'] = new Drafty_In_Here();


/**
 * Our plugin hooks
 */
register_activation_hook( DRAFTY_FILE, array( 'Drafty_In_Here', 'activate' ) );
register_deactivation_hook( DRAFTY_FILE, array( 'Drafty_In_Here', 'deactivate' ) );


class Drafty_In_Here 
{

	/**
	 * Make our plugin options available to the whole class
	 */
	public static $options = null;

	/**
	 * The name of the wp_cron event our plugin will use
	 */
	public static $cron_name = 'drafty_in_here_event';

	/**
	 * Variable to hold an instance of our plugin
	 */
	private static $_this;
	

	public function __construct() {
		self::$_this = $this;

		// Load translation files
		add_action( 'init', array( $this, 'load_text_domain' ) );

		// Add a new wp_cron schedule option
		add_filter( 'cron_schedules', array( $this, 'add_weekly_cron_schedule' ) );

		// Bind callback to wp_cron event
		add_action( self::$cron_name, array( $this, 'cron_callback' ) );

		// Add a settings link to our plugin listing in the plugin admin section
		$prefix = is_network_admin() ? 'network_admin_' : '';
		add_filter( $prefix . 'plugin_action_links_' . DRAFTY_BASENAME, array( $this, 'add_action_link' ), 10, 2 );
		
		// Load plugin options
		self::$options = Options::get( 'drafty_options' );

		$this->load_admin();
	}


	/**
	 * Method to allow users to override any filters or hooks we set.
	 * 
	 * You can use this method like the following example:
	 * ````
	 * $instance = Drafty_In_Here::this();
	 * remove_filter( 'SOME_FILTER', array( $instance, 'SOME_METHOD_NAME' ) );
	 * ````
	 * Where SOME_FILTER would be the WordPress filter and SOME_METHOD_NAME would be a method inside our plugin.
	 *
	 * @return Drafty_In_Here
	 */
	static function this()
	{
		return self::$_this;
	}

	
	/**
	 * Method for when plugin is activated
	 */
	static function activate()
	{
		self::options_init();
	}


	/**
	 * Method for when plugin deactivated
	 */
	static function deactivate()
	{
		Scheduler::remove( self::$cron_name );
	}

	/**
	 * Load or create our plugin options
	 * @return void
	 */
	private static function options_init() 
	{
		$slug = 'drafty_options';
		$prototype = array(
			'email_address' => Options::get( 'admin_email '),
			'drafty_frequency' => '',
		);
		self::$options = Options::get( $slug );
		if (! empty( self::$options ) ) {
			return;
		}
		Options::save( $slug, $prototype );
	}


	/**
	 * Initializes the Admin class
	 */
	private function load_admin()
	{
		$admin = new Admin;
	}


	/**
	 * Loads user translation files
	 */
	public function load_text_domain() 
	{
		$domain = 'drafty-in-here';
		// The "plugin_locale" filter is also used in load_plugin_textdomain()
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/drafty-in-here/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, false, dirname( DRAFTY_BASENAME ) . '/languages/' );
	}


	/**
	 * Add Settings link in plugins admin section
	 * 
	 * @param $links
	 * @param $file
	 * @return mixed
	 */
	public function add_action_link( $links, $file ) {
		if ( $file != DRAFTY_BASENAME ) {
			return $links;
		}
		
		$review_link = '<a href="https://wordpress.org/support/view/plugin-reviews/drafty-in-here">' . __( 'Review', 'drafty-in-here' ) . '</a>';
		array_unshift( $links, $review_link );

		$support_link = '<a href="https://wordpress.org/plugins/drafty-in-here/faq/">' . __( 'FAQ', 'drafty-in-here' ) . '</a>';
		array_unshift( $links, $support_link );

		$settings_link = '<a href="' . esc_url( admin_url( 'options-general.php?page=' . dirname( DRAFTY_BASENAME ) ) ) . '">' . __( 'Settings', 'drafty-in-here' ) . '</a>';

		array_unshift( $links, $settings_link );

		return $links;
	}


	/**
	 * Gets posts of given type with given status.
	 *
	 * @param string $type
	 * @param string $status
	 * @return WP_Query
	 * @throws Exception
	 * @internal param or $string array $type the post type
	 * @internal param or $string array $status the post status
	 *
	 */
	public function get_posts( $type = 'post', $status = 'draft' )
	{
		$args = array(
			'post_type' => $type,
			'post_status' => $status,
		);
		$posts = new WP_Query( $args );

		if ( ! $posts->have_posts() ) {
			throw new \Exception( sprintf( __( '%s can not send you an email, because you do not have any draft posts.', 'drafty-in-here' ), 'Drafty In Here' ), 1 );
		}
		return $posts;
	}


	/**
	 * Adds weekly frequency option to the existing wp_cron schedules.
	 *
	 * @param  array $schedules An array of wp_cron schedules.
	 * @return array The array of wp_cron schedules.
	 */
	public function add_weekly_cron_schedule( $schedules ) {
		$schedules['weekly'] = array(
			'interval' => 604800,
			'display' => __( 'Once Weekly', 'drafty-in-here' )
		);
		return $schedules;
	}


	/**
	 * Method called by wp_con event
	 *
	 * Here we build up the email contents and pass it to our Email class.
	 * @todo: return a call to an event rather than boolean
	 * 
	 * @param  bool $test Weather we are testing the callback or not
	 * @return bool|void If no draft posts are found returns false
	 */
	public function cron_callback( $test = false )
	{
		try {
			$posts = $this->get_posts();
		} catch (\Exception $e) {
			// we have no posts
			if ( false === $test ) return false;
			$posts = null;
		}
		
		if ( $test || $posts) {
			$to = self::$options['email_address'];
			$subject = __( 'You have drafts waiting to be published', 'drafty-in-here' );
			$text = $this->build_message( $posts, $test );
			$html = '<html><body>' . nl2br( $text ) . '</body></html>';
			
			Email::to( $to )->subject( $subject )->text( $text )->HTML( $html )->send();
			
			return true;
		}
		
		return false;
	}

	public function build_message( $posts, $test )
	{
		$message  = __( 'Hello,', 'drafty-in-here' );
		$message .= "\r\n\r\n";
		
		if ( $posts ) {
			$message .= sprintf( _n( 'Right now on %s you have one draft post:', 
					'Right now on %s you have %s draft posts:', 
					$posts->post_count, 
					'drafty-in-here'
				), 
				Options::get( 'blogname' ),
				$posts->post_count
			);
		}
		if ( ! $posts && $test ) {
			$message .= sprintf( __( 'Right now on %s you have zero draft posts:', 'drafty-in-here' ), Options::get( 'blogname' ) );
		}
		$message .= "\r\n\r\n";
		
		if ( $posts ) {
			while ( $posts->have_posts() ) {
				$posts->the_post();
				$message .= "* " . get_the_title() . " - " . __( 'last updated', 'drafty-in-here' ) . ' ' . get_the_date() . "\r\n";
			}
		}
		
		$message .= "\r\n";
		$message .= __( 'So what are you waiting for?', 'drafty-in-here' );
		$message .= "\r\n";
		if ( $posts ) {
			$message .= _n( 'Login and publish it today - like a boss! :-)', 'Login and publish them today - like a boss! :-)', $posts->post_count, 'drafty-in-here' );
		}
		if ( ! $posts && $test ) {
			$message .= __( 'Login and write a new post today - like a boss! :-)', 'drafty-in-here' );
		}
		$message .= "\r\n\r\n" . admin_url();
		$message .= "\r\n\r\n-------------------------------------------------------------\r\n\r\n";
		$message .= sprintf( __( 'E-mail generated by %s', 'drafty-in-here' ), 'Drafty In Here' );

		return $message;
	}

}
