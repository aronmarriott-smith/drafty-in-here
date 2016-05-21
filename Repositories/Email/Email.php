<?php namespace Drafty\Repositories\Email;

use Repositories\Email\EmailerInterface;
use Drafty\Repositories\Email\EmailInterface;

class Email implements EmailInterface 
{

	public static $to;
	public static $from;
	public static $subject;
	public static $text;
	public static $HTML;
	public static $message;
	public static $headers;

	public static function to( $to )
	{
		static::$to = $to;
		return new static;
	}

	public static function from( $from )
	{
		static::$from = $from;
		return new static;
	}

	public static function subject( $subject )
	{
		static::$subject = $subject;
		return new static;
	}

	public static function text( $text )
	{
		static::$text = $text;
		return new static;
	}

	public static function HTML( $HTML )
	{
		static::$HTML = $HTML;
		return new static;
	}

	public static function headers( $headers )
	{
		static::$headers = $headers;
		return new static;
	}

	/**
	 * Sends email
	 *
	 * @return  bool Whether the email contents were sent successfully.
	 */
	public static function send()
	{
		self::buildMessage();
		if ( true === self::validate( self::$to, self::$subject, self::$message ) ) {
			return wp_mail( self::$to, self::$subject, self::$message, self::$headers );
		}
		return false;
	}


	/**
	 * Checks and validates data before sending the email
	 * 
	 * @param  string $to      The email addresses we are sending to.
	 * @param  string $subject The subject of the email.
	 * @param  string $message The content of the email.
	 * @return bool|\WP_Error
	 */
	public static function validate( $to = '', $subject = '', $message = '' )
	{
		if ( ! is_email( $to ) ) {
			return new \WP_Error( 'drafty-email', __( "Can't send email because email is not valid.", 'drafty-in-here' ) );
		}
		if ( empty( $subject ) ) {
			return new \WP_Error( 'drafty-email', __( "Can't send email because there is no subject.", 'drafty-in-here' ) );
		}
		if ( empty( $message ) ) {
			return new \WP_Error( 'drafty-email', __( "Can't send email because there is no message.", 'drafty-in-here' ) );
		}

		return true;
	}

	/**
	 * Sets the message depending on what has been supplied
	 * @return void
	 */
	private static function buildMessage()
	{
		$message = '';
		if ( ! empty( self::$text ) ) {
			$message = self::$text;
		}

		static::$message = $message;
	}

}