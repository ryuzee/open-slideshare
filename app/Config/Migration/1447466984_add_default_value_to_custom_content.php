<?php
class AddDefaultValueToCustomContent extends CakeMigration
{
    /**
     * Migration description
     *
     * @var string
     */
    public $description = 'add_default_value_to_custom_content';

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
        // Custom Contents data
        $c = ClassRegistry::init('CustomContent');
        if ($direction === 'up') {
            $contents_data = array(
                "center_top" => "",
                "center_bottom" => "",
                "right_top" => "",
            );
            foreach ($contents_data as $k => $v) {
                $data = array();
                $data['CustomContent']['name'] = $k;
                $data['CustomContent']['value'] = $v;

                $c->create();
                if ($c->save($data)) {
                    $this->callback->out('Custom contents data was created...');
                } else {
                    echo 'Custom contents data creation failed......';
                }
            }
        }
        return true;
    }
}
