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
    public $uses = array('Slide', 'User', 'Comment', 'Category');


    public $presetVars = array(
        array('field' => 'name', 'type' => 'value')
    );

    /**
     * beforeFilter.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'admin';
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

        $latest_slides = $this->Slide->get_latest_slides(10);
        $this->set('latest_slides', $latest_slides);
        $popular_slides = $this->Slide->get_popular_slides(10);
        $this->set('popular_slides', $popular_slides);
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
        $this->Prg->commonProcess();
        $this->Paginator->settings = array(
            'Slide' => array(
                'conditions' => array($this->Slide->parseCriteria($this->passedArgs)),
                'limit' => 20,
                'recursive' => 2,
                'order' => array('Slide.created' => 'desc'),
            ),
        );
        // $this->paginate = $this->Slide->get_conditions_to_get_all_slides();
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

    /**
     * admin_slide_edit
     *
     * @param mixed $id
     */
    public function admin_slide_edit($id = null)
    {
        if (!$this->Slide->exists($id)) {
            throw new NotFoundException(__('Invalid slide'));
        }

        if ($this->request->is(array('post', 'put'))) {
            if ($this->Slide->save($this->request->data)) {
                $this->Session->success(__('The slide has been saved.'));
            } else {
                $this->Session->warning(__('The slide could not be saved. Please, try again.'));
            }
            return $this->redirect(array('controller' => 'managements', 'action' => 'slide_list'));
        } else {
            $this->request->data = $this->Slide->get_slide($id);
        }
        $categories = $this->Slide->Category->find('list');
        $this->set(compact('categories'));

    }

    /**
     * admin_site_setting
     *
     */
    public function admin_site_setting()
    {
        if($this->request->data) {
            if ($this->Config->saveAll($this->request->data['Config'])) {
                $this->Session->success(__("Completed to save settings..."));
            } else {
                $this->Session->error(__("Could not save settings..."));
            }
        }
        $data = $this->Config->find('all');
        $this->set(compact('data'));
    }

    /**
     * admin_custom_contents_setting
     *
     */
    public function admin_custom_contents_setting()
    {
        if($this->request->data) {
            if ($this->CustomContent->saveAll($this->request->data['CustomContent'])) {
                $this->Session->success(__("Completed to save contents..."));
            } else {
                $this->Session->error(__("Could not save contents..."));
            }
        }
        $data = $this->CustomContent->find('all');
        $this->set(compact('data'));
    }
}
