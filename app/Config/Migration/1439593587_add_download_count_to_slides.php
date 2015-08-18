<?php

class AddDownloadCountToSlides extends CakeMigration
{
    /**
     * Migration description.
     *
     * @var string
     */
    public $description = 'add_download_count_to_slides';

    /**
     * Actions to be performed.
     *
     * @var array
     */
    public $migration = array(
        'up' => array(
            'create_field' => array(
                'slides' => array(
                    'download_count' => array(
                        'type' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'after' => 'page_view',
                    ),
                ),
            ),
        ),
        'down' => array(
            'drop_field' => array(
                'slides' => array(
                    'download_count',
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
