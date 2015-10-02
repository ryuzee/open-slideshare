<?php
/**
 * Config Fixture
 */
class ConfigFixture extends CakeTestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    public $fields = array(
        'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 32, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
        'value' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
        'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array(
            'PRIMARY' => array('column' => 'name', 'unique' => 1)
        ),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
    );

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
