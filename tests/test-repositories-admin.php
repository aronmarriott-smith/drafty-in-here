<?php

Use Drafty\Repositories\Admin\Admin;

class Drafty_Repositories_Admin_Test extends Drafty_UnitTestCase {
	
	/**
	 * @var Admin
	 */
	protected $instance;

	/**
	 * @var string
	 */
	protected $cron_name;

	public function setUp()
	{
		parent::setUp();

		$admin = new Admin;
		$this->instance = $admin;
		$this->cron_name = $admin::$cron_name;

		wp_clear_scheduled_hook( $this->cron_name );
	}

	/**
	 * @covers Admin::validate_basic_options_section
	 */
	public function test_validate_user_options_with_no_parameters()
	{
		$res = $this->instance->validate_basic_options_section();
		$this->assertTrue( is_array( $res ) );
		$this->assertArrayHasKey( 'email_address', $res );
		$this->assertArrayHasKey( 'drafty_frequency', $res );
		$this->assertTrue( empty( $res['email_address'] ) );
		$this->assertTrue( empty( $res['drafty_frequency'] ) );

		$next_scheduled = wp_next_scheduled( $this->cron_name );
		$this->assertFalse( $next_scheduled );
	}

	/**
	 * @covers Admin::validate_basic_options_section
	 */
	public function test_validate_user_options_with_invalid_parameters()
	{
		$parameters = array(
			'email_address' => 'example.com',
			'drafty_frequency' => 'sometimes'
		);
		$res = $this->instance->validate_basic_options_section( $parameters );
		$this->assertTrue( is_array( $res ) );
		$this->assertArrayHasKey( 'email_address', $res );
		$this->assertArrayHasKey( 'drafty_frequency', $res );
		$this->assertTrue( empty($res['email_address']) );
		$this->assertTrue( empty($res['drafty_frequency']) );

		$next_scheduled = wp_next_scheduled( $this->cron_name );
		$this->assertFalse( $next_scheduled );
	}

	/**
	 * @covers Admin::validate_basic_options_section
	 */
	public function test_validate_user_options_with_valid_parameters()
	{
		$parameters = array(
			'email_address' => 'name@example.com',
			'drafty_frequency' => 'hourly'
		);
		$res = $this->instance->validate_basic_options_section( $parameters );
		$this->assertTrue( is_array( $res ) );
		$this->assertArrayHasKey( 'email_address', $res );
		$this->assertArrayHasKey( 'drafty_frequency', $res );
		$this->assertTrue( $res['email_address'] === 'name@example.com' );
		$this->assertTrue( $res['drafty_frequency'] === 'hourly' );

		$next_scheduled = wp_next_scheduled( $this->cron_name );
		$this->assertTrue( is_numeric( $next_scheduled ) );
	}
}