<?php
App::uses('ApiV1Controller', 'Controller');

require_once dirname(__FILE__) . DS . 'OssControllerTest.php';

/**
 * ApiV1Controller Test Case
 */
class ApiV1ControllerTest extends OssControllerTestCase
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
     * testGetSlides method
     *
     * @return void
     */
    public function testGetSlides()
    {
        // Disable debug mode to avoid DebugKit interruption
        $debug = Configure::read('debug');
        Configure::write('debug', 0);
        $this->testAction('/api/v1/slides', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        // Restore debug setting
        Configure::write('debug', $debug);
        $data = json_decode($this->contents);
        $expected_properties = $this->get_slide_expected_properties();
        foreach($expected_properties as $p) {
            $this->assertTrue(property_exists($data->slides[0]->Slide, $p));
        }
    }

    private function get_slide_expected_properties()
    {
         $expected_properties = array(
            'id',
            'name',
            'description',
            'created',
            'downloadable',
            'download_url',
            'thumbnail_url',
            'tags',
            'category_id',
            'category_name',
            'user_id',
            'user_name',
        );
        return $expected_properties;
    }

    /**
     * testGetSlideById method
     *
     * @return void
     */
    public function testGetSlideById()
    {
         // Disable debug mode to avoid DebugKit interruption
        $debug = Configure::read('debug');
        Configure::write('debug', 0);
        $this->testAction('/api/v1/slide/1', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        // Restore debug setting
        Configure::write('debug', $debug);
        $data = json_decode($this->contents);
        $expected_properties = $this->get_slide_expected_properties();
        foreach($expected_properties as $p) {
            $this->assertTrue(property_exists($data->slide->Slide, $p));
        }
    }

    /**
     * testGetSlidesByUserId method
     *
     * @return void
     */
    public function testGetSlidesByUserId()
    {
        // Disable debug mode to avoid DebugKit interruption
        $debug = Configure::read('debug');
        Configure::write('debug', 0);
        $this->testAction('/api/v1/user/1/slides', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        // Restore debug setting
        Configure::write('debug', $debug);
        $data = json_decode($this->contents);
        $expected_properties = $this->get_slide_expected_properties();
        foreach($expected_properties as $p) {
            $this->assertTrue(property_exists($data->slides[0]->Slide, $p));
        }
    }

    /**
     * testGetUserByUserId method
     *
     * @return void
     */
    public function testGetUserByUserId()
    {
        // Disable debug mode to avoid DebugKit interruption
        $debug = Configure::read('debug');
        Configure::write('debug', 0);
        $this->testAction('/api/v1/user/1', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        // Restore debug setting
        Configure::write('debug', $debug);
        $data = json_decode($this->contents);
        $expected_properties = array(
            'id',
            'user_name',
            'biography',
            'created',
        );
        foreach($expected_properties as $p) {
            $this->assertTrue(property_exists($data->user->User, $p));
        }
    }
}
