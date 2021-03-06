<?php namespace Drafty\Repositories\Admin;

use \Drafty_In_Here;
use Drafty\Repositories\Scheduler\Scheduler;
use Drafty\Repositories\Options\Options;

class Admin extends Drafty_In_Here implements AdminInterface 
{

	function __construct()
	{
		add_action( 'admin_init', array( $this, 'drafty_options_init' ) );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
	}


	/**
	 * Adds a new menu under settings in WordPress
	 */
	public function add_admin_menu() 
	{
		$page_title  = sprintf( __( '%s Options', 'drafty-in-here' ), 'Drafty In Here' );
		$menu_title  = 'Drafty In Here';
		$capability  = 'manage_options';
		$menu_slug   = 'drafty-in-here';
		$function    = 'admin_interface';

		add_options_page( $page_title, $menu_title, $capability, $menu_slug, array( $this, $function ) );
	}


	/**
	 * Sets up our plug-in options using the WordPress settings API
	 */
	public function drafty_options_init() 
	{
		register_setting( 'draftySettingsPage', 'drafty_options', array( $this, 'validate_basic_options_section' ) );

		add_settings_section(
			'drafty_basic_settings_section', 
			'', 
			array( $this, 'drafty_options_section_callback' ), 
			'draftySettingsPage'
		);

		add_settings_field( 
			'email_address', 
			__( 'E-mail Address', 'drafty-in-here' ), 
			array( $this, 'email_address_render' ), 
			'draftySettingsPage', 
			'drafty_basic_settings_section' 
		);

		add_settings_field( 
			'drafty_frequency', 
			__( 'Frequency', 'drafty-in-here' ), 
			array( $this, 'drafty_frequency_render' ), 
			'draftySettingsPage', 
			'drafty_basic_settings_section' 
		);

		add_settings_field( 
			'drafty_send_test', 
			__( 'Testing', 'drafty-in-here' ), 
			array( $this, 'drafty_test_email_render' ), 
			'draftySettingsPage', 
			'drafty_basic_settings_section' 
		);
	}

	/**
	 * Populate the email field 
	 */
	public function email_address_render()
	{
		?>
		<input name='drafty_options[email_address]' type='email' aria-describedby="email-description" value='<?php echo self::$options['email_address']; ?>' class="regular-text">
		<p class="description" id="email-description"><?php _e( 'We will send the email to this address.', 'drafty-in-here' ); ?></p>
		<?php
	}


	/**
	 * Populate the select field
	 */
	public function drafty_frequency_render() 
	{
		?>
		<select name='drafty_options[drafty_frequency]' aria-describedby="frequency-description">
			<option value='never' <?php selected( self::$options['drafty_frequency'], 'never' ); ?>>
			<?php _e( 'Never', 'drafty-in-here' ); ?>
			</option>
			<option value='hourly' <?php selected( self::$options['drafty_frequency'], 'hourly' ); ?>>
			<?php _e( 'Every Hour', 'drafty-in-here' ); ?>
			</option>
			<option value='daily' <?php selected( self::$options['drafty_frequency'], 'daily' ); ?>>
			<?php _e( 'Every Day', 'drafty-in-here' ); ?>
			</option>
			<option value='weekly' <?php selected( self::$options['drafty_frequency'], 'weekly' ); ?>>
			<?php _e( 'Every Week', 'drafty-in-here' ); ?>
			</option>
		</select>
		<p id="frequency-description" class="description"><?php _e( 'Set how often we send emails.', 'drafty-in-here' ); ?></p>
	<?php
	}

	public function drafty_test_email_render() 
	{
		?>
	 	<label for="drafty_options[drafty_send_test]"><input name="drafty_options[drafty_send_test]" id="drafty_options[drafty_send_test]" type="checkbox" aria-describedby="send_test-description" value="1"/>
	 	<?php _e( 'Send a test e-mail when you save changes', 'drafty-in-here' ); ?></label>
	 	<p class="timezone-info">
		<?php
		$url = 'https://wordpress.org/plugins/drafty-in-here/other_notes/';
		$link = sprintf(
			wp_kses(
				__( 'If you are not receiving emails, <a href="%s" target="_blank">try this guide</a>.', 'my-text-domain' ),
				array( 'a' => array( 'href' => array(), 'target' => array() ) )
			),
			esc_url( $url )
		);
		echo $link;
		?> 		
		</p>
	<?php
	}	 

	/**
	 * For some reason WordPress requires a callback function at the start of each settings section
	 * @todo change the date format call to use the setting from WordPess
	 */
	public function drafty_options_section_callback() 
	{ 
		$date = Scheduler::next_sheduled( self::$cron_name );
		if ( false !== $date ) {
			$text = sprintf( __( '%s is next scheduled to run', 'drafty-in-here' ), 'Drafty In Here' );
	?>
		<div class="notice notice-info" style="display: inline-block;">
			<p><?php echo $text ?>: <code><?php echo $date->format( 'F jS Y, g:i a T' ) ?></code>.</p>
		</div>
	<?php
		}
	}


	/**
	 * Checks that the email supplied is correct and sets/resets the wp_cron event
	 * 
	 * @param  array $settings_array
	 * @return array $settings_array
	 */
	public function validate_basic_options_section( $settings_array = array() )
	{
		if ( empty( $settings_array['email_address'] ) || ! is_email( $settings_array['email_address'] ) ) {
			$settings_array['email_address'] = '';
			add_settings_error( 'email_address', 'invalid_email', __( 'Please enter a valid email address.', 'drafty-in-here' ), 'error' );
		}

		if ( empty( $settings_array['drafty_frequency'] ) || ! in_array( $settings_array['drafty_frequency'], array( 'never', 'hourly', 'daily', 'weekly' ) ) ) {
			$settings_array['drafty_frequency'] = '';
			add_settings_error( 'email_address', 'invalid_email', __( 'Please select how often we send emails.', 'drafty-in-here' ), 'error' );
		}
		
		if ( $settings_array['drafty_frequency'] != self::$options['drafty_frequency'] || false == Scheduler::next_sheduled( self::$cron_name ) ) {
			
			Scheduler::remove( self::$cron_name ); // remove current
			
			if ( 'never' != $settings_array['drafty_frequency'] ) {
				Scheduler::add( self::$cron_name, $settings_array['drafty_frequency'] ); // add new time slot	
			}
		}

		if ( isset( $settings_array['drafty_send_test'] ) && $settings_array['drafty_send_test'] == 1 ) {
			$this->cron_callback( true );
		}
		return $settings_array;
	}


	/**
	 * Loads the view for the settings page
	 */
	public function admin_interface() 
	{
		if ( ! current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'drafty-in-here' ) );
		}
		require_once( DRAFTY_PATH . '/partials/partial-admin-interface.php' );
	}

}