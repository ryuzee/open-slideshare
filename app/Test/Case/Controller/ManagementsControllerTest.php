<?php
App::uses('ManagementsController', 'Controller');

require_once dirname(__FILE__) . DS . 'OssControllerTest.php';

/**
 * ManagementsController Test Case
 */
class ManagementsControllerTest extends OssControllerTestCase
{
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = array(
        'app.user',
        'app.slide',
        'app.category',
        'app.comment',
        'app.config',
        'app.custom_content',
        'app.tag',
        'app.tagged'
    );

    // override value
    protected $__authUser = array(
        'id' => 1,
        'name' => 'test1',
        'admin' => true,
        'biography' => "test1's bio",
        'created' => '2015-04-17 10:37:35',
        'modified' => '2015-04-17 10:37:35'
    );


    /**
     * testAdminDashboard method
     *
     * @return void
     */
    public function testAdminDashboard()
    {
        $this->goIntoLoginStatus('Managements');
        $this->testAction('/admin/managements/dashboard', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        $str = __('Admin Dashboard');
        $this->assertRegExp("/" . preg_quote($str) . "/", $this->view);
    }

    /**
     * testAdminUserList method
     *
     * @return void
     */
    public function testAdminUserList()
    {
        $this->goIntoLoginStatus('Managements');
        $this->testAction('/admin/managements/user_list', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        $str = __('User List');
        $this->assertRegExp("/" . preg_quote($str) . "/", $this->view);
    }

    /**
     * testAdminSlideList method
     *
     * @return void
     */
    public function testAdminSlideList()
    {
        $this->goIntoLoginStatus('Managements');
        $this->testAction('/admin/managements/slide_list', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        $str = __('Slide List');
    }

    /**
     * testAdminDownloadNonExistenceSlide
     *
     * @expectedException NotFoundException
     */
    public function testAdminDownloadNonExistenceSlide()
    {
        $this->goIntoLoginStatus('Managements');
        $this->testAction('/admin/managements/download/9999', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
    }

    /**
     * testAdminDownload
     *
     */
    public function testAdminDownload()
    {
        $this->goIntoLoginStatus('Managements');
        $this->testAction('/admin/managements/download/2', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        $this->assertContains('http://makimono.example.com/sushi.pdf', $this->headers['Location']);
    }

    /**
     * testAdminSlideEditNonExistenceSlide
     *
     * @expectedException NotFoundException
     */
    public function testAdminSlideEditNonExistenceSlide()
    {
        $this->goIntoLoginStatus('Managements');
        $this->testAction('/admin/managements/slide_edit/9999', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
    }

    /**
     * testAdminSlideEdit
     *
     */
    public function testAdminSlideEdit()
    {
        $this->goIntoLoginStatus('Managements');
        $this->testAction('/admin/managements/slide_edit/4', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        $this->assertContains(__('Edit Slide'), $this->view);
        $this->assertContains('TestSlide4', $this->view);
        $this->assertContains('The Description of TestSlide4', $this->view);
        $data = array(
            'Slide' => array(
                'id' => 4,
                'name' => 'Updated4',
                'description' => 'UpdatedDescription4',
                'downloadable' => 1,
                'category_id' => 3,
                'key' => '1cf9ff7657312d63072439632e6110bd',
                'convert_status' => 100,
                'tags' => 'sushi',
            ),
        );
        //@TODO:This configure setting must be removed after cakedc/tags fixes the issue
        Configure::write('debug', 0);
        $this->testAction('/admin/managements/slide_edit/4', array(
            'data' => $data,
            'method' => 'POST',
            'return' => 'contents'
        ));
        Configure::write('debug', 2);
        $this->assertContains('/admin/managements/slide_list', $this->headers['Location']);

        App::uses('Slide', 'Model');
        $s = new Slide();
        $s->useDbConfig = 'test';
        $s->recursive = -1;
        $s->id = 1;
        $rec = $s->read(null, 4);
        $this->assertEqual($rec['Slide']['name'], $data['Slide']['name']);
        $this->assertEqual($rec['Slide']['description'], $data['Slide']['description']);
        $this->assertEqual($rec['Slide']['downloadable'], $data['Slide']['downloadable']);
        $this->assertEqual($rec['Slide']['category_id'], $data['Slide']['category_id']);
        $this->assertEqual($rec['Slide']['user_id'], 2);

    }

    /**
     * testNotAccessible
     *
     */
    public function testNotAccessible()
    {
        $url = array(
            '/admin/managements/dashboard',
            '/admin/managements/user_list',
            '/admin/managements/slide_list',
            '/admin/managements/download/1',
        );

        foreach ($url as $u) {
            $this->__authUser['admin'] = false;
            $this->goIntoLoginStatus('Managements');
            $this->testAction($u, array(
                'method' => 'GET',
                'return' => 'contents'
            ));
            $this->assertContains('/slides/index', $this->headers['Location']);
        }
    }
}
