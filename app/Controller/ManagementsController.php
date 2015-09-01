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
     * admin_dashboard
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
     * admin_slide_list
     *
     */
    public function admin_user_list()
    {
        $this->paginate = $this->User->get_conditions_to_get_all_users();
        $this->set('users', $this->Paginator->paginate('User'));
    }

    /**
     * admin_slide_list
     *
     */
    public function admin_slide_list()
    {
        $this->paginate = $this->Slide->get_conditions_to_get_all_slides();
        $this->set('slides', $this->Paginator->paginate('Slide'));
        Configure::write('cdn_base_url', '');
    }

    /**
     * admin_download
     *
     * @param mixed $id
     */
    public function admin_download($id = null)
    {
        // duplicate code because of passing test (resolving redirect issue)
        if (!empty($this->params['admin']) && $this->Auth->user("admin") !== true) {
            return $this->redirect('/slides/index');
        }

        if (!$this->Slide->exists($id)) {
            throw new NotFoundException(__('Invalid slide'));
        }
        $slide = $this->Slide->get_slide($id);

        // Generate Signed URL
        $url = $this->SlideProcessing->get_original_file_download_path($this->S3->getClient(), $slide['Slide']['key'], $slide['Slide']['extension']);

        // Redirect
        $this->autoRender = false;
        $this->response->header('Location', $url);
    }
}
