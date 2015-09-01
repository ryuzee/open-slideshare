<?php
App::uses('CategoriesController', 'Controller');

/**
 * CategoriesController Test Case
 */
class CategoriesControllerTest extends ControllerTestCase
{
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = array(
        'app.category',
        'app.slide',
        'app.user',
        'app.comment',
        'plugin.tags.tag',
        'plugin.tags.tagged'
    );

    /**
     * stored_request_uri
     *
     * @var string
     */
    private $stored_request_uri;

    public function setUp()
    {
        $this->stored_request_uri = '';
        if (isset($_SERVER['REQUEST_URI'])) {
            $this->stored_request_uri = $_SERVER['REQUEST_URI'];
        }
        $_SERVER['REQUEST_URI'] = '/example';
        parent::setUp();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        $_SERVER['REQUEST_URI'] = $this->stored_request_uri;
        parent::tearDown();
    }

    /**
     * testView method
     *
     * @return void
     */
    public function testView()
    {
        $this->testAction('/categories/view/1', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        $expected_strings = array(
            sprintf(__('Slides in %s'), 'Books'),
            'TestSlide1',
            'TestSlide2',
        );
        foreach ($expected_strings as $str) {
            $this->assertRegExp("/" . preg_quote($str) . "/", $this->view);
        }
    }

    /**
     * testViewRss
     *
     */
    public function testViewRss()
    {
        // Disable debug mode to avoid DebugKit interruption
        $debug = Configure::read('debug');
        Configure::write('debug', 0);
        $this->testAction('/categories/view/1.rss', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        // Restore debug setting
        Configure::write('debug', $debug);

        $expected_strings = array(
            'Books',
            'TestSlide1',
            'TestSlide2',
        );
        foreach ($expected_strings as $str) {
            $this->assertRegExp("/" . preg_quote($str) . "/", $this->contents);
        }

        $z = new XMLReader;
        $z->xml($this->contents, NULL, LIBXML_DTDVALID);
        $this->assertTrue($z->isValid());
    }

}
