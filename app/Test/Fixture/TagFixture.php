<?php
/**
 * Tag Fixture
 */
class TagFixture extends CakeTestFixture
{

    /**
     * import
     *
     * @var array
     */
    public $import = array('model' => 'Tags.Tag');

    /**
     * Records
     *
     * @var array
     */
    public $records = array(
        array(
            'id' => '1234567890',
            'identifier' => null,
            'name' => 'Tag1',
            'keyname' => 'Tag1',
            'created' => '2015-10-11 20:35:39',
            'modified' => '2015-10-11 20:35:39',
            'occurrence' => 1
        ),
    );
}
