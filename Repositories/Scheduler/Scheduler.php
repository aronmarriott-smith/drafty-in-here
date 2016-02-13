<?php namespace Repositories\Scheduler;

use Carbon\Carbon;

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
	public static function add($event_name = '', $frequency = 'hourly')
	{
		if ( ! wp_next_scheduled($event_name) ) {
			$interval = self::getSecondsInterval($frequency);
			wp_schedule_event(time() + $interval, $frequency, $event_name);
		}
	}


	/**
	 * Remove a wp_cron event.
	 * 
	 * @param  string $event_name Name of the wp_cron event.
	 * @return void
	 */
	public static function remove($event_name = '') 
	{
		if ( false !== ($time = wp_next_scheduled($event_name)) ) {
			wp_unschedule_event($time, $event_name);
		}
	}


	/**
	 * Return a Carbon object of when the event is next scheduled to fire.
	 * 
	 * @param  string $event_name Name of the wp_cron event.
	 * @return object|bool        A Carbon DateTime object or false.
	 */
	public static function next_sheduled($event_name = '') 
	{
		if ( false !== ($time = wp_next_scheduled($event_name)) ) {
			$date = new Carbon;
			$date->timestamp = $time;
			return $date;
		}
		return false;
	}

	private static function getSecondsInterval($frequency='')
	{
		$hour = 3600;
		switch ($frequency) {
			case 'hourly':
				return $hour;
				break;
			case 'daily':
				return 24 * $hour;
				break;
			case 'weekly':
				return (24 * 7) * $hour;
				break;
			default:
				return 0;
				break;
		}
	}

}
