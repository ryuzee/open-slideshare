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
        'plugin.tags.tag',
        'plugin.tags.tagged'
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
