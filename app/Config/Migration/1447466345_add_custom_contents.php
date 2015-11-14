<?php
class CustomContents extends CakeMigration
{
    /**
     * Migration description
     *
     * @var string
     */
    public $description = 'custom_contents';

    /**
     * Actions to be performed
     *
     * @var array $migration
     */
    public $migration = array(
        'up' => array(
            'create_table' => array(
                'custom_contents' => array(
                    'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 32, 'key' => 'primary'),
                    'value' => array('type' => 'text', 'null' => false, 'default' => ''),
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
                'custom_contents',
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
