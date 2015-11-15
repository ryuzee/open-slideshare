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
        'app.config',
        'app.custom_content',
        'app.tag',
        'app.tagged'
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
     * testViewRss
     *
     */
    public function testPopularRss()
    {
        // Disable debug mode to avoid DebugKit interruption
        $debug = Configure::read('debug');
        Configure::write('debug', 0);
        $this->testAction('/slides/popular.rss', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        // Restore debug setting
        Configure::write('debug', $debug);

        $expected_strings = array(
            'Popular Slides',
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
                    'delete_generated_files',
                ),
                'S3' => array(
                    'createPolicy'
                ),
            ),
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
        $c->SlideProcessing->expects($this->any())
            ->method('delete_generated_files')
            ->will($this->returnValue(true));
        $c->S3->expects($this->any())
            ->method('createPolicy')
            ->will($this->returnValue(array(
                'access_id' => 'AKI12345',
                'base64_policy' => 'basepolicystrings',
                'date_ymd' => '20150901',
                'date_gm' => '20150901',
                'acl' => '',
                'security_token' => 'token',
                'signature' => 'sig',
                'success_action_status' => '201',
            )));
        $c->getEventManager()->attach(function($event) {
            return false;
        }, 'Controller.Slide.afterAdd', array('priority' => 1));
    }

    /**
     * testSearch method
     *
     * @return void
     */
    public function testSearch()
    {
        $url = array(
            '/slides/search/name:TestSlide1',
            '/slides/search/created_f:20140101/created_t:20381231',
            "/slides/search/created_f:');alert('1",
            "/slides/search/tags:Tag1",
        );
        $expected_strings = array(
            __('Search Result'),
            'TestSlide1',
            'TestUser',
        );

        foreach ($url as $u) {
            $this->testAction($u, array(
                'method' => 'GET',
                'return' => 'contents'
            ));
            foreach ($expected_strings as $str) {
                $this->assertRegExp("/" . preg_quote($str) . "/", $this->view);
            }
        }

        // unexpected check
        $url = array(
            '/slides/search/created_f:20381231',
            "/slides/search/created_t:');alert('1",
        );
        $unexpected_strings = array(
            'TestSlide1',
            'TestUser',
        );
        $expected_strings = array(
            __('Search Result'),
        );

        foreach ($url as $u) {
            $this->testAction($u, array(
                'method' => 'GET',
                'return' => 'contents'
            ));
            foreach ($unexpected_strings as $str) {
                $this->assertNotContains($str, $this->view);
            }
            foreach ($expected_strings as $str) {
                $this->assertRegExp("/" . preg_quote($str) . "/", $this->view);
            }
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
     * testDownloadNotDownloadable
     *
     * @expectedException NotFoundException
     */
    public function testDownloadNotDownloadable()
    {
        $this->testAction('/slides/download/1', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
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
     * notFoundUrlProvider
     *
     */
    public function notFoundUrlProvider()
    {
        return array(
            array('/slides/update_view/9999'),
            array('/slides/embedded/9999'),
            array('/slides/view/9999'),
            array('/slides/download/9999'),
            array('/slides/edit/9999'),
            array('/slides/delete/9999'),
        );
    }

    /**
     * testSlideNotFound
     *
     * @expectedException NotFoundException
     * @dataProvider notFoundUrlProvider
     * @param mixed $url
     */
    public function testSlideNotFound($url)
    {
        $this->testAction($url, array(
            'method' => 'GET',
            'return' => 'contents'
        ));
    }

    /**
     * testAdd method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->goIntoLoginStatus('Slides');
        $this->mockSlide();

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
     * testAddFailBecauseOfNoLogin
     *
     */
    public function testAddFailBecauseOfNoLogin()
    {
        $this->mockSlide();
        $this->testAction('/slides/add', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        $this->assertContains('/users/login', $this->headers['Location']);
    }

    /**
     * testAddWithPostData
     *
     */
    public function testAddWithPostData()
    {
        $this->goIntoLoginStatus('Slides');
        $this->mockSlide();

        $data = array(
            'Slide' => array(
                'name' => 'Add1',
                'description' => 'AddDescription1',
                'downloadable' => 0,
                'category_id' => 4,
                'key' => 'torouniunagiikatako',
                'tags' => 'toro,uni,ika',
            ),
        );
        Configure::write('debug', 0);
        $this->testAction('/slides/add', array(
            'data' => $data,
            'method' => 'POST',
            'return' => 'contents'
        ));
        Configure::write('debug', 2);
        // Slide ID:3 was owned by user ID:2
        $this->assertContains('/slides/view/6', $this->headers['Location']);
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
     * testEditOtherUserSlide method
     *
     * @return void
     */
    public function testEditOtherUserSlide()
    {
        $this->goIntoLoginStatus('Slides');
        $this->mockSlide();

        $this->testAction('/slides/edit/3', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        // Slide ID:3 was owned by user ID:2
        $this->assertContains('/slides/view/3', $this->headers['Location']);
    }

    /**
     * testEditWithPost method
     *
     * @return void
     */
    public function testEditWithPost()
    {
        $this->goIntoLoginStatus('Slides');
        $this->mockSlide();

        $data = array(
            'Slide' => array(
                'id' => 1,
                'name' => 'Updated1',
                'description' => 'UpdatedDescription1',
                'downloadable' => 1,
                'category_id' => 3,
                'key' => '4ea2abecba74eda5521fff924d9e5062',
                'convert_status' => 100,
                'tags' => 'toro,uni,ika',
            ),
        );
        //@TODO:This configure setting must be removed after cakedc/tags fixes the issue
        Configure::write('debug', 0);
        $this->testAction('/slides/edit/1', array(
            'data' => $data,
            'method' => 'POST',
            'return' => 'contents'
        ));
        Configure::write('debug', 2);
        // Slide ID:3 was owned by user ID:2
        $this->assertContains('/slides/view/1', $this->headers['Location']);

        App::uses('Slide', 'Model');
        $s = new Slide();
        $s->useDbConfig = 'test';
        $s->recursive = -1;
        $s->id = 1;
        $rec = $s->read(null, 1);
        $this->assertEqual($rec['Slide']['name'], $data['Slide']['name']);
        $this->assertEqual($rec['Slide']['description'], $data['Slide']['description']);
        $this->assertEqual($rec['Slide']['downloadable'], $data['Slide']['downloadable']);
        $this->assertEqual($rec['Slide']['category_id'], $data['Slide']['category_id']);

    }

    /**
     * testEditWithPostWithConvert method
     *
     * @return void
     */
    public function testEditWithPostWithConvert()
    {
        $this->goIntoLoginStatus('Slides');
        $this->mockSlide();

        $data = array(
            'Slide' => array(
                'id' => 1,
                'name' => 'Updated1',
                'description' => 'UpdatedDescription1',
                'downloadable' => 1,
                'category_id' => 3,
                'key' => '4ea2abecba74eda5521fff924d9e5062',
                'convert_status' => 0,
                'tags' => 'toro,uni,ika',
            ),
        );
        //@TODO:This configure setting must be removed after cakedc/tags fixes the issue
        Configure::write('debug', 0);
        $this->testAction('/slides/edit/1', array(
            'data' => $data,
            'method' => 'POST',
            'return' => 'contents'
        ));
        Configure::write('debug', 2);
        // Slide ID:3 was owned by user ID:2
        $this->assertContains('/slides/view/1', $this->headers['Location']);

        App::uses('Slide', 'Model');
        $s = new Slide();
        $s->useDbConfig = 'test';
        $s->recursive = -1;
        $s->id = 1;
        $rec = $s->read(null, 1);
        $this->assertEqual($rec['Slide']['convert_status'], $data['Slide']['convert_status']);
        $this->assertEqual($rec['Slide']['convert_status'], 0);
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

    /**
     * testDeleteOthersSlide
     *
     */
    public function testDeleteOthersSlide()
    {
        $this->goIntoLoginStatus('Slides');
        $this->mockSlide();
        $result = $this->testAction(
            '/comments/delete/3',
            array(
                'method' => 'POST',
                'return' => 'contents'
            )
        );
        $this->assertContains('/slides/view/3', $this->headers['Location']);
    }

}
