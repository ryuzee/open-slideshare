<?php

App::uses('AppController', 'Controller');
/**
 * Users Controller.
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class ManagementsController extends AppController
{
    /**
     * uses.
     */
    public $uses = array('User', 'Slide', 'Comment');

    /**
     * beforeFilter.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    /**
     * admin_index
     *
     */
    public function admin_dashboard()
    {
        $this->set('user_count', $this->User->find('count'));
        $this->set('slide_count', $this->Slide->find('count'));
        $this->set('comment_count', $this->Comment->find('count'));
        $this->set('conversion_failed_count', $this->Slide->find('count', array('conditions' => 'convert_status <= 0')));
        $options = array('fields' => array(
            'sum(Slide.page_view) as slide_page_view',
            'sum(Slide.download_count) as slide_download_count',
            'sum(Slide.embedded_view) as slide_embedded_view',
        ));
        $pv = $this->Slide->find('first', $options);
        $this->set('page_view', $pv[0]['slide_page_view']);
        $this->set('download_count', $pv[0]['slide_download_count']);
        $this->set('embedded_view', $pv[0]['slide_embedded_view']);
    }

    /**
     * admin_index
     *
     */
    public function admin_index()
    {
        $conditions = array(
            'model' => 'User',
            'recursive' => 2,
            'limit' => 20,
            'order' => array('User.id' => 'desc'),
        );
        $this->paginate = $conditions;
        $this->set('users', $this->Paginator->paginate('User'));
    }
}
