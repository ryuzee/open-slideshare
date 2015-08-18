<?php

class CreateComments extends CakeMigration
{
    /**
     * Migration description.
     *
     * @var string
     */
    public $description = 'create comments table';

    /**
     * Actions to be performed.
     *
     * @var array
     */
    public $migration = array(
        'up' => array(
            'create_table' => array(
                'comments' => array(
                    'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
                    'user_id' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'slide_id' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'content' => array('type' => 'text', 'null' => false, 'default' => null),
                    'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
                    'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
                    'indexes' => array(
                        'PRIMARY' => array('column' => 'id', 'unique' => true),
                        'idx_comments_user_id_key' => array('column' => 'user_id', 'unique' => false),
                        'idx_comments_slide_id_key' => array('column' => 'slide_id', 'unique' => false),
                    ),
                ),
            ),
        ),
        'down' => array(
            'drop_table' => array(
                'comments',
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
