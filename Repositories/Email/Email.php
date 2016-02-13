<?php namespace Repositories\Email;

use Repositories\Email\EmailerInterface;

class Email implements EmailInterface 
{

	public static $to;
	public static $from;
	public static $subject;
	public static $text;
	public static $HTML;
	public static $message;
	public static $headers;

	public static function to($to)
	{
		static::$to=$to;
		return new static;
	}

	public static function from($from)
	{
		static::$from=$from;
		return new static;
	}

	public static function subject($subject)
	{
		static::$subject=$subject;
		return new static;
	}

	public static function text($text)
	{
		static::$text=$text;
		return new static;
	}

	public static function HTML($HTML)
	{
		static::$HTML=$HTML;
		return new static;
	}

	public static function headers($headers)
	{
		static::$headers=$headers;
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
		if (self::validate(self::$to, self::$subject, self::$message)) {
			return wp_mail(self::$to, self::$subject, self::$message, self::$headers);
		}
		return false;
	}


	/**
	 * Checks and validates data before sending the email
	 * 
	 * @param  string $to      The email addresses we are sending to.
	 * @param  string $subject The subject of the email.
	 * @param  string $message The content of the email.
	 * @return bool            Weather validation passes.
	 */
	private static function validate($to='', $subject='', $message='')
	{
		$valid = true;
		if (! is_email($to)) {
			return new \WP_Error( 'drafty-email', __( "Can't send email because email is not valid.", 'drafty-in-here' ) );
			$valid = false;
		}
		if (empty($subject)) {
			return new \WP_Error( 'drafty-email', __( "Can't send email because there is no subject.", 'drafty-in-here' ) );
			$valid = false;
		}
		if (empty($message)) {
			return new \WP_Error( 'drafty-email', __( "Can't send email because there is no message.", 'drafty-in-here' ) );
			$valid = false;
		}

		return $valid;
	}


	/**
	 * Builds a multipart HTML and plain text message if both text and HTML have been supplied
	 * @return void
	 */
	private static function buildMessage()
	{
		$message = '';
		$headers = "MIME-Version: 1.0\r\n";
		$boundary = uniqid('np');
		$boundary_section = "\r\n\r\n--" . $boundary . "\r\n";
		$boundary_end = "\r\n\r\n--" . $boundary . "\r\n";

		if (! empty(self::$HTML) && ! empty(self::$text)) {
			$headers .= "Content-Type: multipart/alternative; boundary=" . $boundary . "\r\n";

			$message .= $boundary_section;
			$message .= "Content-type: text/plain; charset=UTF-8;\r\nContent-Transfer-Encoding: QUOTED-PRINTABLE\r\nContent-Disposition: inline\r\n\r\n";
			$message .= self::$text;
			$message .= $boundary_section;
			$message .= "Content-type: text/html; charset=UTF-8;\r\nContent-Transfer-Encoding: QUOTED-PRINTABLE\r\nContent-Disposition: inline\r\n\r\n";
			$message .= self::$HTML;
			$message .= $boundary_end;
		}
		elseif(! empty(self::$text)) {
			$headers .= "Content-Type: text/plain; boundary=" . $boundary . "\r\n";

			$message .= $boundary_section;
			$message .= "Content-type: text/plain; charset=UTF-8;\r\nContent-Transfer-Encoding: QUOTED-PRINTABLE\r\nContent-Disposition: inline\r\n\r\n";
			$message .= self::$text;
			$message .= $boundary_end;
		}
		elseif(! empty(self::$html)) {
			$headers .= "Content-Type: text/html; boundary=" . $boundary . "\r\n";

			$message .= $boundary_section;
			$message .= "Content-type: text/html; charset=UTF-8;\r\nContent-Transfer-Encoding: QUOTED-PRINTABLE\r\nContent-Disposition: inline\r\n\r\n";
			$message .= self::$html;
			$message .= $boundary_end;
		}

		static::$headers = $headers;
		static::$message = $message;
	}

}