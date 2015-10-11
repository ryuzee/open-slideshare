<?php
/**
 * Config Fixture
 */
class ConfigFixture extends CakeTestFixture
{
    /**
     * import
     *
     * @var array
     */
    public $import = array('model' => 'Config');

    /**
     * Records
     *
     * @var array
     */
    public $records = array(
        array(
            'name' => 'config1',
            'value' => '1',
            'created' => '2015-10-02 12:14:22',
            'modified' => '2015-10-02 12:14:22'
        ),
        array(
            'name' => 'config2',
            'value' => 'value2',
            'created' => '2015-10-02 12:14:22',
            'modified' => '2015-10-02 12:14:22'
        ),
    );
}
