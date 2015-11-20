<?php

/**
 * SlideFixture.
 */
class SlideFixture extends CakeTestFixture
{
    /**
     * Import.
     *
     * @var array
     */
    public $import = array('model' => 'Slide');

    /**
     * Records.
     *
     * @var array
     */
    public $records = array(
        array(
            'id' => '1',
            'user_id' => '1',
            'name' => 'TestSlide1',
            'description' => 'The Description of TestSlide1',
            'downloadable' => 0,
            'category_id' => '1',
            'created' => '2015-02-01 00:00:00',
            'modified' => '2015-02-01 01:00:00',
            'key' => '4ea2abecba74eda5521fff924d9e5062',
            'extension' => '.pdf',
            'convert_status' => '100',
            'total_view' => '132',
            'page_view' => '99',
            'download_count' => '0',
            'embedded_view' => '33',
        ),
        array(
            'id' => '2',
            'user_id' => '1',
            'name' => 'TestSlide2',
            'description' => 'The Description of TestSlide2',
            'downloadable' => 1,
            'category_id' => '1',
            'created' => '2015-03-01 00:00:00',
            'modified' => '2015-03-01 01:00:00',
            'key' => '1cf9ff7657312d63072439632e6110bd',
            'extension' => '.pptx',
            'convert_status' => '100',
            'total_view' => '132',
            'page_view' => '99',
            'download_count' => '11',
            'embedded_view' => '33',
        ),
        array(
            'id' => '3',
            'user_id' => '2',
            'name' => 'TestSlide3',
            'description' => 'The Description of TestSlide3',
            'downloadable' => 1,
            'category_id' => '3',
            'created' => '2015-08-01 00:00:00',
            'modified' => '2015-08-01 01:00:00',
            'key' => '1cf9ff7657312d63072439632e6110bd',
            'extension' => '.pptx',
            'convert_status' => '100',
            'total_view' => '132',
            'page_view' => '88',
            'download_count' => '22',
            'embedded_view' => '44',
        ),
        array(
            'id' => '4',
            'user_id' => '2',
            'name' => 'TestSlide4',
            'description' => 'The Description of TestSlide4',
            'downloadable' => 1,
            'category_id' => '2',
            'created' => '2015-06-01 00:00:00',
            'modified' => '2015-06-01 01:00:00',
            'key' => '1cf9ff7657312d63072439632e6110bd',
            'extension' => '.pptx',
            'convert_status' => '100',
            'total_view' => '13332',
            'page_view' => '9999',
            'download_count' => '1111',
            'embedded_view' => '3333',
        ),
        array(
            'id' => '5',
            'user_id' => '1',
            'name' => 'TestSlide5',
            'description' => 'The Description of TestSlide5',
            'downloadable' => 0,
            'category_id' => '4',
            'created' => '2015-05-01 00:00:00',
            'modified' => '2015-05-01 01:00:00',
            'key' => '1cf9ff7657312d63072439632e6110bd',
            'extension' => '.pptx',
            'convert_status' => '-40',
            'total_view' => '2',
            'page_view' => '1',
            'download_count' => '1',
            'embedded_view' => '1',
        ),
    );
}
