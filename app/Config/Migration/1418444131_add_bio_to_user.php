<?php

class AddBioToUser extends CakeMigration
{
    /**
     * Migration description.
     *
     * @var string
     */
    public $description = 'add_bio_to_status';

    /**
     * Actions to be performed.
     *
     * @var array
     */
    public $migration = array(
        'up' => array(
            'create_field' => array(
                'users' => array(
                    'biography' => array(
                        'type' => 'text',
                        'default' => '',
                        'NULL' => false,
                    ),
                ),
            ),
        ),
        'down' => array(
            'drop_field' => array(
                'users' => array(
                    'biography',
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
