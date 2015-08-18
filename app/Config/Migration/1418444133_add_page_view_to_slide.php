<?php

class AddPageViewSlides extends CakeMigration
{
    /**
     * Migration description.
     *
     * @var string
     */
    public $description = 'add_page_view_to_slides';

    /**
     * Actions to be performed.
     *
     * @var array
     */
    public $migration = array(
        'up' => array(
            'create_field' => array(
                'slides' => array(
                    'page_view' => array(
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
                    'page_view',
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
