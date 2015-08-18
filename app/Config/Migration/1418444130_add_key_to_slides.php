<?php

class AddKeyToSlides extends CakeMigration
{
    /**
     * Migration description.
     *
     * @var string
     */
    public $description = 'add_artifact_user_id_to_status';

    /**
     * Actions to be performed.
     *
     * @var array
     */
    public $migration = array(
        'up' => array(
            'create_field' => array(
                'slides' => array(
                    'key' => array(
                        'type' => 'string',
                        'length' => '255',
                        'default' => '',
                        'NULL' => false,
                    ),
                    'convert_status' => array(
                        'type' => 'integer',
                        'default' => 0,
                        'NULL' => false,
                    ),
                ),
            ),
        ),
        'down' => array(
            'drop_field' => array(
                'slides' => array(
                    'key', 'convert_status',
                ),
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
