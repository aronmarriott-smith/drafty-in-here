<?php
class Drafty_UnitTestCase extends WP_UnitTestCase
{

	/**
	 * @var int
	 */
	var $last_inserted_post_id;

	public function create_draft_post()
	{
		$my_post = array(
			'post_title'   => 'My Test Post',
			'post_content' => 'This is my test post.',
			'post_status'  => 'draft',
			'post_author'  => 1
		);
		$this->last_inserted_post_id = wp_insert_post( $my_post );
	}

	public function delete_draft_post()
	{
		if ( ! empty( $this->last_inserted_post_id ) ) {
			wp_delete_post( $this->last_inserted_post_id, true );
		}
	}

}