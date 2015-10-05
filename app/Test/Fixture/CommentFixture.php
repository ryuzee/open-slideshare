<?php

/**
 * CommentFixture.
 */
class CommentFixture extends CakeTestFixture
{
    /**
     * Import.
     *
     * @var array
     */
    public $import = array('model' => 'Comment');

    /**
     * Records.
     *
     * @var array
     */
    public $records = array(
        array(
            'id' => '1',
            'user_id' => '1',
            'slide_id' => '1',
            'content' => 'てすと1',
            'created' => '2015-03-01 00:00:00',
            'modified' => '2015-03-01 01:00:00',
        ),
        array(
            'id' => '2',
            'user_id' => '1',
            'slide_id' => '1',
            'content' => 'テスト2',
            'created' => '2015-04-01 00:00:00',
            'modified' => '2015-04-01 01:00:00',
        ),
        array(
            'id' => '3',
            'user_id' => '1',
            'slide_id' => '1',
            'content' => 'てすと3',
            'created' => '2015-05-01 00:00:00',
            'modified' => '2015-05-01 01:00:00',
        ),
        array(
            'id' => '4',
            'user_id' => '2',
            'slide_id' => '1',
            'content' => 'てすと4',
            'created' => '2015-05-01 00:00:00',
            'modified' => '2015-05-01 01:00:00',
        ),
    );
}
