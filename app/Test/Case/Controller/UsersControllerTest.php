<?php
App::uses('UsersController', 'Controller');

/**
 * UsersController Test Case
 */
class UsersControllerTest extends ControllerTestCase
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
        'plugin.tags.tagged',
    );

    /**
     * __authUser
     *
     * @var array
     */
    private $__authUser = array(
        'id' => 1,
        'name' => 'test1',
        'biography' => "test1's bio",
        'created' => '2015-04-17 10:37:35',
        'modified' => '2015-04-17 10:37:35'
    );

    private $stored_request_uri;

    /**
     * setUp method
     *
     * @return void
     */
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
     * testSignup method
     *
     * @return void
     */
    public function testSignup()
    {
        $this->testAction('/users/signup', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        $expected_strings = array(
            __('Add User'),
            __('Register'),
            'UserUsername',
            'UserPassword',
            'UserBiography',
            'UserDisplayName',
        );
        foreach ($expected_strings as $str) {
            $this->assertRegExp("/" . preg_quote($str) . "/", $this->contents);
        }
    }

    /**
     * testSignupPostData
     *
     */
    public function testSignupPostData() {
        $data = array(
            'User' => array(
                'username' => 'sushi@example.com',
                'password' => 'sushi12345',
                'biography' => 'One of the sushi lover',
                'display_name' => 'SUSHILOVER',
            )
        );
        $result = $this->testAction('/users/signup', array(
            'data' => $data,
            'method' => 'post',
            'return' => 'contents'
        ));
        $this->assertContains('/users', $this->headers['Location']);
    }

    /**
     * testView method
     *
     * @return void
     */
    public function testView()
    {
        $this->testAction('/users/view/1', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        $expected_strings = array(
            sprintf(__('Slides by %s'), 'TestUser'),
            'TestSlide1',
            'TestSlide2',
        );
        foreach ($expected_strings as $str) {
            $this->assertRegExp("/" . preg_quote($str) . "/", $this->contents);
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
        $this->testAction('/users/view/1.rss', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        // Restore debug setting
        Configure::write('debug', $debug);

        $expected_strings = array(
            'TestUser',
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
     * testViewException
     *
     * @expectedException NotFoundException
     */
    public function testViewException()
    {
        $this->testAction('/users/view/', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
    }

    /**
     * testIndex method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->goIntoLoginStatus();

        $this->testAction('/users/index', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        $expected_strings = array(
            __('Speaker Details'),
            __('My Slides'),
        );
        foreach ($expected_strings as $str) {
            $this->assertRegExp("/" . preg_quote($str) . "/", $this->contents);
        }
    }

    /**
     * testIndexException
     *
     *i @expectedException NotFoundException
     */
    public function testIndexException()
    {
        $this->goIntoLoginStatus();

        // Delete this user.
        App::uses('User', 'Model');
        $s = new User();
        $s->useDbConfig = 'test';
        $s->id = 1;
        $s->delete();

        $this->assertEmpty($s->read(null, 1));

        $this->testAction('/users/index', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
    }

    /**
     * testStatistics method
     *
     * @return void
     */
    public function testStatistics()
    {
        $this->goIntoLoginStatus();

        $this->testAction('/users/statistics', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        $expected_strings = array(
            __('My Statistics'),
            __('Slide Name'),
            __('Created'),
            __('Modified'),
            __('Convert Status'),
            __('Page View'),
            __('Embedded View'),
            __('Download Count'),
            __('Number of Comments'),
        );
        foreach ($expected_strings as $str) {
            $this->assertRegExp("/" . preg_quote($str) . "/", $this->contents);
        }
    }

    /**
     * testLogoutWithReturnUrl method
     *
     * @return void
     */
    public function testLogoutWithReturnUrl()
    {
        $this->goIntoLoginStatus();

        $this->testAction('/users/logout?return_url=/hoge', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        $this->assertContains('/hoge', $this->headers['Location']);
    }

    /**
     * testLogout
     *
     * @return void
     */
    public function testLogout()
    {
        $this->goIntoLoginStatus();

        $this->testAction('/users/logout', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        $this->assertContains('/slides/index', $this->headers['Location']);
    }

    /**
     * testEdit method
     *
     * @return void
     */
    public function testEditGet()
    {
        $this->goIntoLoginStatus();

        App::uses('UserFixture', 'Test/Fixture');
        $fixture = new UserFixture();
        $expected_record = $fixture->records[0];

        $result = $this->testAction('/users/edit/1', array(
            'method' => 'get',
            'return' => 'contents'
        ));
        $this->assertContains($expected_record['username'], $this->view);
        $this->assertContains($expected_record['display_name'], $this->view);
        $this->assertContains($expected_record['biography'], $this->view);
        $this->assertNotContains($expected_record['password'], $this->view);
    }

    /**
     * testEdit method
     *
     * @return void
     */
    public function testEditPost()
    {
        $this->goIntoLoginStatus();

        $data = array(
            'User' => array(
                'id' => 1,
                'username' => 'sushi@example.com',
                'password' => 'sushi12345678',
                'biography' => 'SUSHI FOREVER',
                'display_name' => 'SUSHIFOREVER',
            )
        );
        $result = $this->testAction('/users/edit/1', array(
            'data' => $data,
            'method' => 'post',
            'return' => 'contents'
        ));
        $this->assertContains('/users', $this->headers['Location']);

        $passwordHasher = new SimplePasswordHasher();
        $column_password = $passwordHasher->hash($data['User']['password']);

        App::uses('User', 'Model');
        $s = new User();
        $s->useDbConfig = 'test';
        $rec = $s->read(null, 1);
        $this->assertEqual($rec['User']['password'], $column_password);
    }

    /**
     * testEditNoPassword method
     *
     * @return void
     */
    public function testEditPostNoPassword()
    {
        $this->goIntoLoginStatus();

        $data = array(
            'User' => array(
                'id' => 1,
                'username' => 'sushi@example.com',
                'biography' => 'SUSHI FOREVER',
                'password' => '',
                'display_name' => 'SUSHIFOREVER',
            )
        );
        $result = $this->testAction('/users/edit/1', array(
            'data' => $data,
            'method' => 'post',
            'return' => 'contents'
        ));
        $this->assertContains('/users', $this->headers['Location']);

        App::uses('UserFixture', 'Test/Fixture');
        $fixture = new UserFixture();
        $expected_record = $fixture->records[0];

        App::uses('User', 'Model');
        $s = new User();
        $s->useDbConfig = 'test';
        $rec = $s->read(null, 1);

        // Confirm password was not changed.
        $this->assertEqual($rec['User']['password'], $expected_record["password"]);
    }

    /**
     * testEditWithNoId
     *
     * @expectedException NotFoundException
     */
    public function testEditWithNoId()
    {
        $this->goIntoLoginStatus();

        $data = array(
            'User' => array(
                'id' => 1,
                'username' => 'sushi@example.com',
                'password' => 'sushi12345678',
                'biography' => 'SUSHI FOREVER',
                'display_name' => 'SUSHIFOREVER',
            )
        );
        $result = $this->testAction('/users/edit/', array(
            'data' => $data,
            'method' => 'post',
            'return' => 'contents'
        ));
    }

    /**
     * testEditOtherUser
     *
     */
    public function testEditOtherUser()
    {
        $this->goIntoLoginStatus();

        $data = array(
            'User' => array(
                'id' => 2,
                'username' => 'user2@example.com',
                'password' => 'User2User2',
                'biography' => 'Second User',
                'display_name' => 'user2',
            )
        );
        $result = $this->testAction('/users/edit/2', array(
            'data' => $data,
            'method' => 'post',
            'return' => 'contents'
        ));

        $this->assertContains('/users/index', $this->headers['Location']);
    }

    /**
     * testDelete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->goIntoLoginStatus();
        $this->testAction('/users/delete/1', array(
            'method' => 'GET',
            'return' => 'contents'
        ));
        $this->assertContains('/users', $this->headers['Location']);

        // ?? Is it OK to delete phisically? NEED TO CONSIDER
        App::uses('User', 'Model');
        $s = new User();
        $s->useDbConfig = 'test';
        $this->assertEqual($s->read(null, 1), array());
    }

    /**
     * goIntoLoginStatus
     *
     */
    public function goIntoLoginStatus() {
        $c = $this->generate('Users', array(
            'components' => array(
                'Auth' => array('user'),
            )
        ));
        $c->Auth->staticExpects($this->any())
            ->method('user')
            ->will($this->returnCallback(array($this, 'authUserCallback')));
    }

    /**
     * authUserCallback
     *
     * @param mixed $param
     */
    public function authUserCallback($param)
    {
        if (empty($param)) {
            return $this->__authUser;
        } else {
            return $this->__authUser[$param];
        }
    }
}
