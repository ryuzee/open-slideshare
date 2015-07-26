<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * @link      http://cakephp.org CakePHP(tm) Project
 * @package   app.Controller
 * @since     CakePHP(tm) v 0.2.9
 */

App::uses('Controller', 'Controller');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package    app.Controller
 * @link       http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    public $helpers = array(
        'Session',
        'Html' => array('className' => 'BoostCake.BoostCakeHtml'),
        'Form' => array('className' => 'BoostCake.BoostCakeForm'),
        'Paginator' => array('className' => 'BoostCake.BoostCakePaginator'),
        'S3Image',
        'Common',
        'Tags.TagCloud',
    );
    public $components = array(
        'Auth',
        'Paginator',
        'Session' => array('className' => 'SessionEx'),
        'S3',
        'DebugKit.Toolbar',
        'Search.Prg' => array(
            'commonProcess' => array('paramType' => 'named'),
            'presetForm' => array('paramType' => 'named'),
        ),
    );

    public $uses = array('Comment', 'Artifact', 'Status');

    public function beforeFilter()
    {
        $this->set('title_for_layout', '');

        if (isset($this->Auth)) {
            $this->Auth->loginAction = "/users/login";
            $this->Auth->loginError = __("Invalid username or password", true);
            $this->Auth->authError = __('You have no privileges', true);
            $this->Auth->userModel = "User";
            $this->Auth->fields = array("username" => "username", "password" => "password");
            $this->Auth->autoRedirect = true;
        }
    }
}
