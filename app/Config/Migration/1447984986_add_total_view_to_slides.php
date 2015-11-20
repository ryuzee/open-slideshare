<?php

class AddTotalViewToSlides extends CakeMigration
{
    /**
     * Migration description.
     *
     * @var string
     */
    public $description = 'add_total_view_to_slides';

    /**
     * Actions to be performed.
     *
     * @var array
     */
    public $migration = array(
        'up' => array(
            'create_field' => array(
                'slides' => array(
                    'total_view' => array(
                        'type' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'after' => 'convert_status',
                    ),
                ),
            ),
        ),
        'down' => array(
            'drop_field' => array(
                'slides' => array(
                    'total_view',
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
        $Slide = ClassRegistry::init('Slide');
        $dataSource = $Slide->getDataSource();
        if ($direction === 'up') {
            $dataSource->query('UPDATE slides set total_view = page_view + embedded_view');
        }
        return true;
    }
}
