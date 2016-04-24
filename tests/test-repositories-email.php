<?php

Use Repositories\Email\Email;

class Drafty_Repositories_Email_Test extends Drafty_UnitTestCase {
	
	/**
	 * @covers Email::validate
	 */
	public function test_return_value_is_true_or_instance_of_wp_error()
	{
		$res = Email::validate( 'name@example.com', 'My subject', 'My Message' );
		$this->assertTrue( $res );

		// invalid email
		$res = Email::validate( 'example.com', 'My subject', 'My Message' );
		$this->assertInstanceOf( 'WP_Error', $res );

		// no subject
		$res = Email::validate( 'name@example.com', null, 'My Message' );
		$this->assertInstanceOf( 'WP_Error', $res );

		// no message
		$res = Email::validate( 'name@example.com', 'My subject', null );
		$this->assertInstanceOf( 'WP_Error', $res );
	}

	/**
	 * @covers Email::send
	 */
	public function test_send_returns_true_when_we_pass_valid_parameters()
	{
		$to = get_option('admin_email');
		$res = Email::to($to)->subject('My Subject')->text('My Message')->HTML('<p>Some HTML</p>')->send();
		$this->assertTrue( $res );

		$res = Email::to($to)->subject('My Subject')->text('My Message')->HTML(null)->send();
		$this->assertTrue( $res );

		$res = Email::to($to)->subject('My Subject')->text(null)->HTML('<p>Some HTML</p>')->send();
		$this->assertTrue( $res );

		// invalid email
		$res = Email::to('example.com')->subject('My Subject')->text('My Message')->HTML('<p>Some HTML</p>')->send();
		$this->assertFalse( $res );
		
		// no subject
		$res = Email::to($to)->subject(null)->text('My Message')->HTML('<p>Some HTML</p>')->send();
		$this->assertFalse( $res );

		// no message text or HTML
		$res = Email::to($to)->subject('My Subject')->text(null)->HTML(null)->send();
		$this->assertFalse( $res );
	}
}