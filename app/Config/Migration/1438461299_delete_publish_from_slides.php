<?php

class DeletePublishFromSlides extends CakeMigration
{
    /**
     * Migration description.
     *
     * @var string
     */
    public $description = 'delete_publish_from_slides';

    /**
     * Actions to be performed.
     *
     * @var array
     */
    public $migration = array(
        'up' => array(
            'drop_field' => array(
                'slides' => array(
                    'publish',
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
