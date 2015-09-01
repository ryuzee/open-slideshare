<?php
App::uses('SlidesController', 'Controller');

require_once dirname(__FILE__) . DS . 'OssControllerTest.php';

/**
 * SlidesController Test Case
 */
class SlidesControllerTest extends OssControllerTestCase
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
        'plugin.tags.tag',
        'plugin.tags.tagged'
    );

    /**
     * testIndex method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->testAction('/slides/index', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        $expected_strings = array(
            __('Latest Slides'),
            __('Popular Slides'),
        );
        foreach ($expected_strings as $str) {
            $this->assertRegExp("/" . preg_quote($str) . "/", $this->view);
        }
    }

    /**
     * testLatest method
     *
     * @return void
     */
    public function testLatest()
    {
        $this->testAction('/slides/latest', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        $expected_strings = array(
            __('Latest Slides'),
        );
        foreach ($expected_strings as $str) {
            $this->assertRegExp("/" . preg_quote($str) . "/", $this->view);
        }
    }

    /**
     * testPopular method
     *
     * @return void
     */
    public function testPopular()
    {
        $this->testAction('/slides/popular', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        $expected_strings = array(
            __('Popular Slides'),
        );
        foreach ($expected_strings as $str) {
            $this->assertRegExp("/" . preg_quote($str) . "/", $this->view);
        }
    }

    /**
     * testView method
     *
     * @return void
     */
    public function testView()
    {
        Configure::write('image_bucket_name', 'sushi.example.com');
        Configure::write('use_s3_static_hosting', 1);
        $this->mockSlide();

        $this->testAction('/slides/view/1', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        $expected_strings = array(
            __('TestSlide1'),
            'Transcript1',
            'Transcript2',
            'http://sushi.example.com/sushi/slide-1.jpg',
            'http://sushi.example.com/sushi/slide-2.jpg',
        );
        foreach ($expected_strings as $str) {
            $this->assertRegExp("|" . preg_quote($str) . "|", $this->view);
        }

        $unexpected_strings = array(
            __('Edit Slide'),
            __('Delete Slide'),
        );
        foreach ($unexpected_strings as $str) {
            $this->assertNotContains($str, $this->view);
        }
    }

    /**
     * mockSlide
     *
     */
    public function mockSlide()
    {
        $c = $this->generate('Slides', array(
            'components' => array(
                'Auth' => array('user'),
                'SlideProcessing' => array(
                    'get_slide_pages_list',
                    'get_transcript',
                    'get_original_file_download_path',
                    'delete_slide_from_s3',
                ),
            )
        ));
        $c->SlideProcessing->expects($this->any())
            ->method('get_slide_pages_list')
            ->will($this->returnValue(array('sushi/slide-1.jpg', 'sushi/slide-2.jpg')));
        $c->SlideProcessing->expects($this->any())
            ->method('get_transcript')
            ->will($this->returnValue(array('Transcript1', 'Transcript2')));
        $c->SlideProcessing->expects($this->any())
            ->method('get_original_file_download_path')
            ->will($this->returnValue('http://makimono.example.com/sushi.pdf'));
        $c->SlideProcessing->expects($this->any())
            ->method('delete_slide_from_s3')
            ->will($this->returnValue(true));
    }

    /**
     * testSearch method
     *
     * @return void
     */
    public function testSearch()
    {
        $this->testAction('/slides/search/name:TestSlide1', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        $expected_strings = array(
            __('Search Result'),
            'TestSlide1',
            'TestUser',
        );
        foreach ($expected_strings as $str) {
            $this->assertRegExp("/" . preg_quote($str) . "/", $this->view);
        }
    }

    /**
     * testDownload method
     *
     * @return void
     */
    public function testDownload()
    {
        $this->mockSlide();
        $result = $this->testAction('/slides/download/2', array(
            'method' => 'get',
            'return' => 'contents'
        ));
        $this->assertContains('http://makimono.example.com/sushi.pdf', $this->headers['Location']);
    }

    /**
     * testEmbedded method
     *
     * @return void
     */
    public function testEmbedded()
    {
        Configure::write('image_bucket_name', 'sushi.example.com');
        Configure::write('use_s3_static_hosting', 1);
        $this->mockSlide();

        $this->testAction('/slides/embedded/1', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        $expected_strings = array(
            'http://sushi.example.com/sushi/slide-1.jpg',
            'http://sushi.example.com/sushi/slide-2.jpg',
        );
        foreach ($expected_strings as $str) {
            $this->assertRegExp("|" . preg_quote($str) . "|", $this->view);
        }
    }

    /**
     * testUpdateView method
     *
     * @return void
     */
    public function testUpdateView()
    {
        Configure::write('image_bucket_name', 'sushi.example.com');
        Configure::write('use_s3_static_hosting', 1);
        $this->mockSlide();

        $this->testAction('/slides/update_view/1', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        $this->assertEqual("2", $this->view); // Num of Slide pages
    }

    /**
     * testAdd method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->goIntoLoginStatus('Slides');

        $this->testAction('/slides/add', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        $expected_strings = array(
            __('Upload slide'),
            __('Name'),
            __('Description'),
            __('Tag'),
            __('Category'),
            __('Upload a file to save'),
        );
        foreach ($expected_strings as $str) {
            $this->assertRegExp("/" . preg_quote($str) . "/", $this->view);
        }

    }

    /**
     * testEdit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->goIntoLoginStatus('Slides');
        $this->mockSlide();

        $this->testAction('/slides/edit/1', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        $expected_strings = array(
            __('Edit slide'),
            __('Name'),
            __('Description'),
            __('Tag'),
            __('Category'),
            __('Re-upload'),
            __('Submit'),
            'The Description of TestSlide1',
        );
        foreach ($expected_strings as $str) {
            $this->assertRegExp("/" . preg_quote($str) . "/", $this->view);
        }
    }

/**
 * testDelete method
 *
 * @return void
 */
    public function testDelete()
    {
        $this->goIntoLoginStatus('Slides');
        $this->mockSlide();
        $result = $this->testAction(
            '/comments/delete/1',
            array(
                'method' => 'POST',
                'return' => 'contents'
            )
        );
        $this->assertContains('/users', $this->headers['Location']);
    }
}
