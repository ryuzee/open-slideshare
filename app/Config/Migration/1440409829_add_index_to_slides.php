<?php
class AddIndexToSlides extends CakeMigration
{
    /**
     * Migration description
     *
     * @var string
     */
    public $description = 'add_index_to_slides';

    /**
     * Actions to be performed
     *
     * @var array $migration
     */
    public $migration = array(
        'up' => array(
        ),
        'down' => array(
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
        $Slide = ClassRegistry::init('Slide');
        $dataSource = $Slide->getDataSource();
        if ($direction === 'up') {
            $dataSource->query("CREATE INDEX idx_slides_page_view_key ON slides(page_view)");
        } elseif ($direction === 'down') {
            $dataSource->query("ALTER TABLE slides DROP INDEX idx_slides_page_view_key");
        }
        return true;
    }
}
