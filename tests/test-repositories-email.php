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
}