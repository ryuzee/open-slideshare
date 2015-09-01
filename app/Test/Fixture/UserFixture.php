<?php

/**
 * UserFixture.
 */
class UserFixture extends CakeTestFixture
{
    /**
     * Import.
     *
     * @var array
     */
    public $import = array('model' => 'User');

    /**
     * Records.
     *
     * @var array
     */
    public $records = array(
        array(
            'id' => '1',
            'username' => 'test@example.com',
            'display_name' => 'TestUser',
            'password' => 'cfe9423f96c6a58c20cad0c27b224f9491379eb9',
            'disabled' => 0,
            'admin' => 1,
            'created' => '2015-01-01 00:00:00',
            'modified' => '2015-01-01 01:00:00',
            'biography' => 'Hello TestUser',
            'slides_count' => '2',
        ),
        array(
            'id' => '2',
            'username' => 'test2@example.com',
            'display_name' => 'UserTest2',
            'password' => 'cfe9423f96c6a58c20cad0c27b224f9491379eb9',
            'disabled' => 0,
            'admin' => 0,
            'created' => '2015-01-01 00:00:00',
            'modified' => '2015-01-01 01:00:00',
            'biography' => 'TestUser2 Hello',
            'slides_count' => '2',
        ),
    );
}
