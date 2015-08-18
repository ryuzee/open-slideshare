<?php

class AddSlidesCount extends CakeMigration
{
    /**
     * Migration description.
     *
     * @var string
     */
    public $description = 'add_artifacts_count';

    /**
     * Actions to be performed.
     *
     * @var array
     */
    public $migration = array(
        'up' => array(
            'create_field' => array(
                'users' => array(
                    'slides_count' => array(
                        'type' => 'integer',
                        'default' => 0,
                    ),
                ),
            ),
        ),
        'down' => array(
            'drop_field' => array(
                'users' => array(
                    'slides_count',
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
