<?php

class AddSlide extends CakeMigration
{
    /**
     * Migration description.
     *
     * @var string
     */
    public $description = 'add_slide';

    /**
     * Actions to be performed.
     *
     * @var array
     */
    public $migration = array(
        'up' => array(
            'create_table' => array(
                'categories' => array(
                    'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
                    'name' => array('type' => 'string', 'length' => 255, 'null' => false, 'default' => null),
                ),
                'slides' => array(
                    'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
                    'user_id' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'name' => array('type' => 'string', 'length' => 255, 'null' => false, 'default' => null),
                    'description' => array('type' => 'text', 'null' => false, 'default' => null),
                    'category_id' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'publish' => array('type' => 'boolean', 'null' => false, 'default' => false),
                    'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
                    'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
                    'indexes' => array(
                        'PRIMARY' => array('column' => 'id', 'unique' => true),
                        'idx_slides_user_id_key' => array('column' => 'user_id', 'unique' => false),
                        'idx_slides_category_id_key' => array('column' => 'category_id', 'unique' => false),
                    ),
                ),
            ),
        ),
        'down' => array(
            'drop_table' => array(
                'categories', 'slides',
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
