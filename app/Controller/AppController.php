<?php

/**
 * Application level Controller.
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     CakePHP(tm) v 0.2.9
 */
App::uses('Controller', 'Controller');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

/**
 * Application Controller.
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link       http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * helpers.
     */
    public $helpers = array(
        'Session',
        'Html' => array('className' => 'BoostCake.BoostCakeHtml'),
        'Form' => array('className' => 'BoostCake.BoostCakeForm'),
        'Paginator' => array('className' => 'BoostCake.BoostCakePaginator'),
        'Common',
        'Tags.TagCloud',
    );

    /**
     * components.
     */
    public $components = array(
        'Auth',
        'Paginator',
        'Session' => array('className' => 'SessionEx'),
        'SlideProcessing',
        'S3',
        'DebugKit.Toolbar',
        'Search.Prg' => array(
            'commonProcess' => array('paramType' => 'named'),
            'presetForm' => array('paramType' => 'named'),
        ),
        'RequestHandler',
    );

    /**
     * uses.
     */
    public $uses = array('Comment', 'Category', 'Config', 'CustomContent');

    /**
     * beforeFilter.
     */
    public function beforeFilter()
    {
        // Check the S3 buckets name to avoid SSL issue.
        if ((strpos(Configure::read('bucket_name'), '.') !== false) ||
           (Configure::read('use_s3_static_hosting') != 1 && strpos(Configure::read('image_bucket_name'), '.') !== false)) {
            throw new Exception(
                sprintf(
                    __('Dot can not be allowed in the bucket name %s to avoid SSL certification issue... Please check S3 bucket name settings'),
                    Configure::read('bucket_name') . " / " . Configure::read('image_bucket_name')
                )
            );
        }

        $this->set('title_for_layout', '');

        if (isset($this->Auth)) {
            $this->Auth->loginAction = '/users/login';
            $this->Auth->loginError = __('Invalid username or password', true);
            $this->Auth->authError = __('You have no privileges', true);
            $this->Auth->userModel = 'User';
            $this->Auth->fields = array('username' => 'username', 'password' => 'password');
            $this->Auth->autoRedirect = true;
            $this->Auth->loginRedirect = '/users/index';

            // admin
            if (!empty($this->params['admin']) && $this->Auth->user("admin") !== true) {
                return $this->redirect('/slides/index');
            }
        }

        // set category
        $this->set('category', $this->Category->find('all'));

        // set site settings
        $this->set('config', $this->Config->get_all_configs());

        // set site settings
        $this->set('custom_contents', $this->CustomContent->get_all_custom_contents());
    }
}
