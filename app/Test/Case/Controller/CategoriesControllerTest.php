<?php
App::uses('CategoriesController', 'Controller');

require_once dirname(__FILE__) . DS . 'OssControllerTest.php';

/**
 * CategoriesController Test Case
 */
class CategoriesControllerTest extends OssControllerTestCase
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
        'app.config',
        'app.custom_content',
        'app.tag',
        'app.tagged'
    );

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
     * testViewNonExistentCategory
     *
     * @expectedException NotFoundException
     */
    public function testViewNonExistentCategory()
    {
        $this->testAction('/categories/view/9999', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
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
