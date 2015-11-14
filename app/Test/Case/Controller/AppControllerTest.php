<?php
App::uses('AppController', 'Controller');

require_once dirname(__FILE__) . DS . 'OssControllerTest.php';

/**
 * ApiV1Controller Test Case
 */
class AppControllerTest extends OssControllerTestCase
{
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = array(
        'app.slide',
        'app.user',
        'app.category',
        'app.comment',
        'app.config',
        'app.custom_content',
        'app.tag',
        'app.tagged'
    );

    /**
     * beforeFilterTest
     *
     * @expectedException Exception
     */
    public function testBeforeFilter1()
    {
        Configure::write('bucket_name', 'a.a');
        $this->testAction('/slides/view/1', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
    }

    /**
     * beforeFilterTest
     *
     * @expectedException Exception
     */
    public function testBeforeFilter2()
    {
        Configure::write('use_s3_static_hosting', 0);
        Configure::write('image_bucket_name', 'a.a');
        $this->testAction('/slides/view/1', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
    }
}
