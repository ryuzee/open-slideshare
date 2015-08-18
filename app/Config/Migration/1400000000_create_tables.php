<?php

class CreatePosts extends CakeMigration
{
    /**
     * Migration description.
     *
     * @var string
     */
    public $description = '';

    /**
     * Actions to be performed.
     *
     * @var array
     */
    public $migration = array(
        'up' => array(
            'create_table' => array(
                'users' => array(
                    'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
                    'username' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => '32'),
                    'display_name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => '128'),
                    'password' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => '255'),
                    'disabled' => array('type' => 'boolean', 'default' => false),
                    'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
                    'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
                    'indexes' => array(
                        'PRIMARY' => array('column' => 'id', 'unique' => true),
                        'idx_username_ukey' => array('column' => 'username', 'unique' => true),
                    ),
                ),
            ),
        ),
        'down' => array(
            'drop_table' => array(
                'users',
            ),
        ),
    );

    /**
     * Before migration callback.
     *
     * @param string $direction up or down direction of migration process
     *
     * @return bool Should process continue
     */
    public function before($direction)
    {
        return true;
    }

    /**
     * After migration callback.
     *
     * @param string $direction up or down direction of migration process
     *
     * @return bool Should process continue
     */
    public function after($direction)
    {
        return true;
    }
}
