<?php
class AddConfigTable extends CakeMigration
{
    /**
     * Migration description
     *
     * @var string
     */
    public $description = 'add_config_table';

    /**
     * Actions to be performed.
     *
     * @var array
     */
    public $migration = array(
        'up' => array(
            'create_table' => array(
                'configs' => array(
                    'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 32, 'key' => 'primary'),
                    'value' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '255'),
                    'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
                    'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
                    'indexes' => array(
                        'PRIMARY' => array('column' => 'name', 'unique' => true),
                    ),
                ),
            ),
        ),
        'down' => array(
            'drop_table' => array(
                'configs',
            ),
        ),
    );

    /**
     * Before migration callback
     *
     * @param string $direction Direction of migration process (up or down)
     * @return bool Should process continue
     */
    public function before($direction)
    {
        return true;
    }

    /**
     * After migration callback
     *
     * @param string $direction Direction of migration process (up or down)
     * @return bool Should process continue
     */
    public function after($direction)
    {
        return true;
    }
}
