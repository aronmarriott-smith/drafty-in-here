<?php namespace Repositories\Options;

class Options implements OptionsInterface 
{

	/**
	 * @param string         $option      Name of option to add. Expected to not be SQL-escaped.
	 * @param mixed          $value       Optional. Option value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
	 * @return bool False if option was not added and true if option was added.
	 */
	public static function create( $option = '', $value = null ) 
	{
		return add_option( $option, $value );
	}

	/**
	 * @param string $option  Name of option to retrieve. Expected to not be SQL-escaped.
	 * @param mixed  $default Optional. Default value to return if the option does not exist.
	 * @return mixed Value set for the option.
	 */
	public static function read( $option = '' ) 
	{
		return get_option( $option );
	}

	/**
	 * @param string $option  Name of option to retrieve. Expected to not be SQL-escaped.
	 * @param mixed  $default Optional. Default value to return if the option does not exist.
	 * @return mixed Value set for the option.
	 */
	public static function get( $option = '', $default = false ) 
	{
		return get_option( $option, $default );
	}

	/**
     * @param string      $option   Option name. Expected to not be SQL-escaped.
 	 * @param mixed       $value    Option value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
	 * @return bool True if option value has changed, false if not or if update failed.
	 */
	public static function update( $option = '', $value = null )
	{
		return update_option( $option, $value );
	}

	/**
     * @param string      $option   Option name. Expected to not be SQL-escaped.
 	 * @param mixed       $value    Option value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
	 * @return bool True if option value has changed, false if not or if update failed.
	 */
	public static function save( $option = '', $value = null ) 
	{
		return update_option( $option, $value );
	}
	
	/**
	 * @param string $option Name of option to remove. Expected to not be SQL-escaped.
	 * @return bool True, if option is successfully deleted. False on failure
	 */
	public static function delete( $option = '' ) 
	{
		return delete_option( $option );
	}

}