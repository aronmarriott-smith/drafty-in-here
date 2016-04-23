<?php

class Drafty_In_Here_Test extends Drafty_UnitTestCase {
	
	/**
	 * @var Drafty_In_Here
	 */
	private $instance;

	/**
	 * @var string
	 */
	private $cron_name;

	public function setUp()
	{
		parent::setUp();
		
		$drafty = new Drafty_In_Here();
		$this->instance = $drafty;
		$this->cron_name = $drafty::$cron_name;
	}

	/**
	 * @covers Drafty_In_Here::activate
	 */
	public function test_it_has_method_activate()
	{
		$this->assertTrue( method_exists( $this->instance, 'activate' ) );
	}

	/**
	 * @covers Drafty_In_Here::deactivate
	 */
	public function test_it_has_method_deactivate()
	{
		$this->assertTrue( method_exists( $this->instance, 'deactivate' ) );
	}

	/**
	 * @covers Drafty_In_Here::get_posts
	 */
	public function test_get_posts_when_draft_posts_exist_return_instance_of_wp_query()
	{
		$this->create_draft_post();
		$this->assertInstanceOf( 'WP_Query', $this->instance->get_posts() );
		$this->delete_draft_post();
	}

	/**
	 * @expectedException Exception
	 * @covers Drafty_In_Here::get_posts
	 */
	public function test_get_posts_when_no_draft_posts_exist_exception_thrown()
	{
		$this->expectException( $this->instance->get_posts() );
	}

	/**
	 * @covers Drafty_In_Here::cron_callback
	 */
	public function test_cron_callback_with_no_draft_posts_and_test_parameter_is_false_return_false()
	{
		$test = false;
		$this->assertFalse( $this->instance->cron_callback( $test ) );
	}

	/**
	 * @covers Drafty_In_Here::cron_callback
	 */
	public function test_cron_callback_with_draft_posts_and_test_parameter_is_false_return_false()
	{
		$this->create_draft_post();
		$test = false;
		$this->assertTrue( $this->instance->cron_callback( $test ) );
		$this->delete_draft_post();
	}

	/**
	 * @covers Drafty_In_Here::cron_callback
	 */
	public function test_cron_callback_with_no_draft_posts_and_test_parameter_false_return_true()
	{
		$test = true;
		$this->assertTrue( $this->instance->cron_callback( $test ) );
	}

	/**
	 * @covers Drafty_In_Here::cron_callback
	 */
	public function test_cron_callback_with_draft_posts_and_test_parameter_true_return_true()
	{
		$this->create_draft_post();
		$test = true;
		$this->assertTrue( $this->instance->cron_callback( $test ) );
		$this->delete_draft_post();
	}

	/**
	 * Because wp_cron is disabled in cli mode we can't test this code
	 */
	public function test_cron_integration_when_draft_posts_exist()
	{
//		$this->create_draft_post();
//		wp_schedule_event( time() - 3600*24, 'hourly', $this->cron_name );
//      $cron_timestamp = wp_next_scheduled( $this->cron_name );
//		add_action($this->cron_name, array($this->instance, 'cron_callback'));
//		spawn_cron();
//		$new_cron_timestamp = wp_next_scheduled( $this->cron_name );
//		$this->assertGreaterThan( $new_cron_timestamp, $cron_timestamp );
//		$this->delete_draft_post();
	}

	/**
	 * Because wp_cron is disabled in cli mode we can't test this code
	*/
	public function test_cron_integration_when_draft_posts_do_not_exist()
	{
//		wp_schedule_event( time() - 3600*24, 'hourly', $this->cron_name );
//		$cron_timestamp = wp_next_scheduled( $this->cron_name );
//		add_action($this->cron_name, array($this->instance, 'cron_callback'));
//		spawn_cron();
//		$new_cron_timestamp = wp_next_scheduled( $this->cron_name );
//		$this->assertGreaterThan( $new_cron_timestamp, $cron_timestamp );
	}
}