<?php

App::uses('AppController', 'Controller');
/**
 * Slides Controller.
 *
 * @property Slide $Slide
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class SlidesController extends AppController
{
    public $presetVars = array(
        array('field' => 'name', 'type' => 'value'),
        array('field' => 'display_name', 'type' => 'value'),
        array('field' => 'description', 'type' => 'value'),
        array('field' => 'category', 'type' => 'value'),
        array('field' => 'created_f', 'type' => 'value'),
        array('field' => 'created_t', 'type' => 'value'),
    );

    public $uses = array('Slide', 'User');

    public function beforeFilter()
    {
        $this->Auth->allow('index', 'view', 'update_view');
        parent::beforeFilter();
    }

    /**
     * index method.
     */
    public function index()
    {
        $this->set('title_for_layout', __('All Slides'));
        $this->Slide->recursive = 0;

        $this->Prg->commonProcess();

        $add_query = array('Slide.convert_status = '.SUCCESS_CONVERT_COMPLETED);

        $val = isset($this->passedArgs['created_f']) ? $this->passedArgs['created_f'] : null;
        if (!empty($val)) {
            $add_query[] = "Slide.created >= '".$val."'";
        }
        $val = isset($this->passedArgs['created_t']) ? $this->passedArgs['created_t'] : null;
        if (!empty($val)) {
            $add_query[] = "Slide.created <= '".$val."'";
        }

        $this->paginate = array(
            'Slide' => array(
                'conditions' => array($this->Slide->parseCriteria($this->passedArgs), $add_query),
                'limit' => 20,
                'recursive' => 2,
                'order' => array('id' => 'desc'),
            ),
        );

        $this->set('slides', $this->Paginator->paginate());
    }

    /**
     * view method.
     *
     * @throws NotFoundException
     *
     * @param string $id
     */
    public function view($id = null)
    {
        $user = $this->Auth->User();
        $user_id = $user['id'];
        $this->set('user_id', $user_id);

        if (!$this->Slide->exists($id)) {
            throw new NotFoundException(__('Invalid slide'));
        }
        $slide = $this->Slide->get_slide($id);
        $this->set('title_for_layout', h($slide['Slide']['name']));
        $this->set('slide', $slide);
        $userinfo = $this->User->read(null, $slide['Slide']['user_id']);
        $this->set('user', $userinfo);

        $other_slides_in_category = $this->Slide->get_recent_slides_in_category($slide['Slide']['category_id'], $id);
        $this->set('other_slides_in_category', $other_slides_in_category);

        $this->Slide->countup($id);
    }

    /**
     * download method.
     *
     * @throws NotFoundException
     *
     * @param string $id
     */
    public function download($id = null)
    {
        if (!$this->Slide->exists($id)) {
            throw new NotFoundException(__('Invalid slide'));
        }
        $slide = $this->Slide->get_slide($id);
        if (!$slide['Slide']['downloadable']) {
            throw new NotFoundException(__('This slide can not be downloaded'));
        }
        // Signed URL 生成
        $url = $this->S3->get_original_file_download_path($slide['Slide']['key'], $slide['Slide']['extension']);
        // リダイレクト
        $this->autoRender = false;
        $this->response->header('Location', $url);
    }

    public function update_view($id = null)
    {
        if (!$this->Slide->exists($id)) {
            throw new NotFoundException(__('Invalid slide'));
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->set('slide', $this->Slide->get_slide($id));
    }

    /**
     * add method.
     */
    public function add()
    {
        $this->set('title_for_layout', __('New Slide'));
        $user = $this->Auth->User();
        $user_id = $user['id'];
        $form_values = $this->S3->createPolicy(null);
        $this->set('form_values', $form_values);

        if ($this->request->is('post')) {
            $this->Slide->create();
            $this->request->data['Slide']['user_id'] = $user_id;
            if ($this->Slide->save($this->request->data)) {
                $last_insert_id = $this->Slide->getLastInsertID();
                $this->Session->success(__('The slide has been saved.'));
                ClassRegistry::init('SQS.SimpleQueue')->send('extract', array('id' => $last_insert_id, 'key' => $this->request->data['Slide']['key']));

                return $this->redirect('/slides/view/'.$last_insert_id);
            } else {
                $this->Session->warning(__('The slide could not be saved. Please, try again.'));
            }
        }

        $categories = $this->Slide->Category->find('list');
        $this->set(compact('categories'));

        $userinfo = $this->User->read(null, $user_id);
        $this->set('user', $userinfo);
    }

    /**
     * edit method.
     *
     * @throws NotFoundException
     *
     * @param string $id
     */
    public function edit($id = null)
    {
        if (!$this->Slide->exists($id)) {
            throw new NotFoundException(__('Invalid slide'));
        }
        $form_values = $this->S3->createPolicy(null);
        $this->set('form_values', $form_values);

        // access check
        $user = $this->Auth->User();
        $user_id = $user['id'];
        $d = $this->Slide->read(null, $id);
        $this->set('slide', $d);
        if ($user_id != $d['Slide']['user_id']) {
            $this->Session->warning(__('You do not have permission to edit the slide created by others.'));

            return $this->redirect(array('controller' => 'slides', 'action' => 'view', $id));
        }

        if ($this->request->is(array('post', 'put'))) {
            if ($this->Slide->save($this->request->data)) {
                if ($this->request->data['Slide']['convert_status'] == '0') {
                    $this->S3->delete_generated_files($d['Slide']['key']);
                    ClassRegistry::init('SQS.SimpleQueue')->send('extract', array('id' => $id, 'key' => $d['Slide']['key']));
                }
                $this->Session->success(__('The slide has been saved.'));
            } else {
                $this->Session->warning(__('The slide could not be saved. Please, try again.'));
            }

            return $this->redirect(array('controller' => 'slides', 'action' => 'view', $id));
        } else {
            $this->request->data = $this->Slide->get_slide($id);
        }

        $userinfo = $this->User->read(null, $user_id);
        $this->set('user', $userinfo);

        $categories = $this->Slide->Category->find('list');
        $this->set(compact('categories'));
    }

    /**
     * delete method.
     *
     * @throws NotFoundException
     *
     * @param string $id
     */
    public function delete($id = null)
    {
        $this->Slide->id = $id;
        if (!$this->Slide->exists()) {
            throw new NotFoundException(__('Invalid slide'));
        }
        $data = $this->Slide->findById($id);

        // access check
        $user = $this->Auth->User();
        $user_id = $user['id'];
        if ($user_id != $data['Slide']['user_id']) {
            $this->Session->warning(__('You do not have permission to delete the slide created by others.'));

            return $this->redirect(array('controller' => 'slides', 'action' => 'view', $id));
        }

        $this->request->allowMethod('post', 'delete');
        if ($this->Slide->delete()) {
            // Delete master slide and generated images
            $this->S3->delete_slide_from_s3($data['Slide']['key']);

            // show message
            $this->Session->success(__('The slide has been deleted.'));
        } else {
            $this->Session->warning(__('The slide could not be deleted. Please, try again.'));
        }

        return $this->redirect(array('controller' => 'users', 'action' => 'index'));
    }
}
