<?php

class CreateCakeSessions extends CakeMigration
{
    /**
     * Migration description.
     *
     * @var string
     */
    public $description = 'create cakesessions table';

    /**
     * Actions to be performed.
     *
     * @var array
     */
    public $migration = array(
        'up' => array(
            'create_table' => array(
                'cake_sessions' => array(
                    'id' => array('type' => 'string', 'length' => 255, 'null' => false, 'default' => '', 'key' => 'primary'),
                    'data' => array('type' => 'text', 'null' => false, 'default' => null),
                    'expires' => array('type' => 'integer', 'null' => false, 'default' => null),
                ),
            ),
        ),
        'down' => array(
            'drop_table' => array(
                'cake_sessions',
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
