<?php
/**
 * Tagged Fixture
 */
class TaggedFixture extends CakeTestFixture
{
    public $import = array('model' => 'Tags.Tagged');
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'tagged';

    /**
     * Records
     *
     * @var array
     */
    public $records = array(
        array(
            'id' => '1234567890',
            'foreign_key' => 1,
            'tag_id' => '1234567890',
            'model' => 'Slide',
            'language' => 'ja',
            'created' => '2015-10-11 20:35:47',
            'modified' => '2015-10-11 20:35:47',
            'times_tagged' => 1
        ),
    );
}
