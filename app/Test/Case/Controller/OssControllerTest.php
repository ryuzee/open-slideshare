<?php

/**
 * Class: OssControllerTestCase
 *
 */
class OssControllerTestCase extends ControllerTestCase
{
    /**
     * stored_request_uri
     *
     * @var string
     */
    public $stored_request_uri;

    /**
     * __authUser
     *
     * @var array
     */
    protected $__authUser = array(
        'id' => 1,
        'name' => 'test1',
        'biography' => "test1's bio",
        'created' => '2015-04-17 10:37:35',
        'modified' => '2015-04-17 10:37:35'
    );

    /**
     * setUp
     *
     */
    public function setUp()
    {
        $this->stored_request_uri = '';
        if (isset($_SERVER['REQUEST_URI'])) {
            $this->stored_request_uri = $_SERVER['REQUEST_URI'];
        }
        $_SERVER['REQUEST_URI'] = '/example';
        Configure::write('cdn_base_url', '');
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
     * goIntoLoginStatus
     *
     */
    public function goIntoLoginStatus($name = 'Users') {
        $c = $this->generate($name, array(
            'components' => array(
                'Auth' => array('user'),
                'SlideProcessing' => array('get_original_file_download_path'),
            )
        ));
        $c->Auth->staticExpects($this->any())
            ->method('user')
            ->will($this->returnCallback(array($this, 'authUserCallback')));
        $c->SlideProcessing->expects($this->any())
            ->method('get_original_file_download_path')
            ->will($this->returnValue('http://makimono.example.com/sushi.pdf'));

        return $c;
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

