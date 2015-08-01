<?php

class AddDownloadableToSlides extends CakeMigration
{
    /**
     * Migration description.
     *
     * @var string
     */
    public $description = 'add_downloadable_to_slides';

    /**
     * Actions to be performed.
     *
     * @var array
     */
    public $migration = array(
        'up' => array(
            'create_field' => array(
                'slides' => array(
                    'downloadable' => array(
                        'type' => 'boolean',
                        'null' => false,
                        'default' => false,
                        'after' => 'description',
                    ),
                ),
            ),
        ),
        'down' => array(
            'drop_field' => array(
                'slides' => array(
                    'downloadable',
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
