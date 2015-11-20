<?php

class AddLikesTable extends CakeMigration
{
    /**
     * Migration description.
     *
     * @var string
     */
    public $description = 'add_likes_table';

    /**
     * Actions to be performed.
     *
     * @var array
     */
    public $migration = array(
        'up' => array(
            'create_table' => array(
                'likes' => array(
                    'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
                    'model' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50),
                    'foreign_id' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'user_id' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
                    'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
                ),
            ),
        ),
        'down' => array(
            'drop_table' => array(
                'likes',
            ),
        ),
    );

    /**
     * Before migration callback.
     *
     * @param string $direction Direction of migration process (up or down)
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
     * @param string $direction Direction of migration process (up or down)
     *
     * @return bool Should process continue
     */
    public function after($direction)
    {
        return true;
    }
}
