<?php namespace Repositories\Scheduler;

use Repositories\Options\Options;

class Scheduler implements SchedulerInterface
{

	/**
	 * Add a new wp_cron event.
	 *
	 * Method first checks if wp_cron event exists before creating a new one.
	 * 
	 * @param  string $event_name Name of the wp_cron event
	 * @param  string $frequency  How often the event should fire
	 * @return void
	 */
	public static function add( $event_name = '', $frequency = 'hourly' )
	{
		if ( ! wp_next_scheduled( $event_name ) ) {
			$interval = self::get_seconds_interval( $frequency );
			wp_schedule_event( time() + $interval, $frequency, $event_name );
		}
	}


	/**
	 * Remove a wp_cron event.
	 * 
	 * @param  string $event_name Name of the wp_cron event.
	 * @return void
	 */
	public static function remove( $event_name = '' ) 
	{
		if ( false !== ( $time = wp_next_scheduled( $event_name ) ) ) {
			wp_unschedule_event( $time, $event_name );
		}
	}


	/**
	 * Return a DateTime object of when the event is next scheduled to fire.
	 *
	 * This method tries to convert the scheduled at time to match a user
	 * defined timezone_string. Should timezone be missing it will ues
	 * whatever is default.
	 * 
	 * @param  string $event_name Name of the wp_cron event.
	 * @return object|bool		A DateTime instance or false.
	 */
	public static function next_sheduled( $event_name = '' ) 
	{
		if ( false !== ( $time = wp_next_scheduled( $event_name ) ) ) {
			$dt = new \DateTime( "@$time", new \DateTimeZone('UTC') );
			$tz = Options::get( 'timezone_string', false );
			return empty( $tz ) ? $dt : $dt->setTimezone( new \DateTimeZone($tz) );
		}
		return false;
	}

	private static function get_seconds_interval( $frequency = '' )
	{
		$hour = 3600;
		$times = array(
			'hourly' => $hour,
			'daily' => 24 * $hour,
			'weekly' => (24 * 7) * $hour,
		);
		
		return ( isset( $times[$frequency] ) ) ? $times[$frequency] : 0; 
	}

}
