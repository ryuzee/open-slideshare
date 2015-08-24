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
    /**
     * presetVars.
     */
    public $presetVars = array(
        array('field' => 'name', 'type' => 'value'),
        array('field' => 'display_name', 'type' => 'value'),
        array('field' => 'description', 'type' => 'value'),
        array('field' => 'category', 'type' => 'value'),
        array('field' => 'created_f', 'type' => 'value'),
        array('field' => 'created_t', 'type' => 'value'),
    );

    /**
     * uses.
     */
    public $uses = array('Slide', 'User');

    /**
     * SlideProcessing.
     *
     * @var mixed
     */
    public $SlideProcessing;


    /**
     * paginate
     *
     * @var mixed
     */
    public $paginate;

    /**
     * beforeFilter.
     */
    public function beforeFilter()
    {
        $this->Auth->allow('index', 'view', 'update_view', 'download', 'embedded', 'popular', 'latest');
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

        $add_query = array('Slide.convert_status = ' . SUCCESS_CONVERT_COMPLETED);

        $val = isset($this->passedArgs['created_f']) ? $this->passedArgs['created_f'] : null;
        if (!empty($val)) {
            $add_query[] = "Slide.created >= '" . $val . "'";
        }
        $val = isset($this->passedArgs['created_t']) ? $this->passedArgs['created_t'] : null;
        if (!empty($val)) {
            $add_query[] = "Slide.created <= '" . $val . "'";
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
     * latest
     *
     */
    public function latest()
    {
        $this->set('title_for_layout', __('Recent Slides'));
        $this->Paginator->settings = $this->Slide->get_conditions_to_get_latest_slides();
        $this->set('slides', $this->Paginator->paginate('Slide'));
    }

    /**
     * popular
     *
     */
    public function popular()
    {
        $this->set('title_for_layout', __('Popular Slides'));
        $this->Paginator->settings = $this->Slide->get_conditions_to_get_popular_slides();
        $this->set('slides', $this->Paginator->paginate('Slide'));
    }

    /**
     * view method.
     *
     * @throws NotFoundException
     *
     * @param string $id
     */
    public function view($id = null, $display_position = 1)
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

        $file_list = $this->SlideProcessing->get_slide_pages_list($slide['Slide']['key']);
        $this->set('file_list', $file_list);

        if (count($file_list) > $display_position) {
            $start_position = 0;
        } elseif ($display_position <= 0) {
            $start_position = 0;
        } else {
            $start_position = $display_position - 1;
        }
        $this->set('start_position', $start_position);

        $other_slides_in_category = $this->Slide->get_recent_slides_in_category($slide['Slide']['category_id'], $id);
        $this->set('other_slides_in_category', $other_slides_in_category);

        $this->Slide->countup('page_view', $id);
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

        // increment download count
        $this->Slide->countup('download_count', $id);

        // Generate Signed URL
        $url = $this->SlideProcessing->get_original_file_download_path($this->S3->getClient(), $slide['Slide']['key'], $slide['Slide']['extension']);

        // Redirect
        $this->autoRender = false;
        $this->response->header('Location', $url);
    }

    /**
     * embedded.
     *
     * @param mixed $id
     */
    public function embedded($id = null)
    {
        if (!$this->Slide->exists($id)) {
            throw new NotFoundException(__('Invalid slide'));
        }
        Configure::write('debug', 0);
        $this->response->type('javascript');
        $this->layout = 'escaped_javascript';
        $slide = $this->Slide->get_slide($id);
        $this->set('slide', $slide);
        $file_list = $this->SlideProcessing->get_slide_pages_list($slide['Slide']['key']);
        $this->set('file_list', $file_list);
        $this->Slide->countup('embedded_view', $id);
    }

    /**
     * update_view.
     *
     * @param mixed $id
     */
    public function update_view($id = null)
    {
        if (!$this->Slide->exists($id)) {
            throw new NotFoundException(__('Invalid slide'));
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $slide = $this->Slide->get_slide($id);
        $this->set('slide', $slide);
        $file_list = $this->SlideProcessing->get_slide_pages_list($slide['Slide']['key']);
        $this->set('file_list', $file_list);
    }

    /**
     * add method.
     */
    public function add()
    {
        $this->set('title_for_layout', __('New Slide'));
        $user = $this->Auth->User();
        $user_id = $user['id'];
        $form_values = $this->S3->createPolicy();
        $this->set('form_values', $form_values);

        if ($this->request->is('post')) {
            $this->Slide->create();
            $this->request->data['Slide']['user_id'] = $user_id;
            if ($this->Slide->save($this->request->data)) {
                $last_insert_id = $this->Slide->getLastInsertID();
                $this->Session->success(__('The slide has been saved.'));
                ClassRegistry::init('SQS.SimpleQueue')->send('extract', array('id' => $last_insert_id, 'key' => $this->request->data['Slide']['key']));

                return $this->redirect('/slides/view/' . $last_insert_id);
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
        $form_values = $this->S3->createPolicy();
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
                    $this->SlideProcessing->delete_generated_files($this->S3->getClient(), $d['Slide']['key']);
                    ClassRegistry::init('SQS.SimpleQueue')->send('extract', array('id' => $id, 'key' => $d['Slide']['key']));
                    $this->Session->success(__('The slide has been saved. File conversion has just started.'));
                } else {
                    $this->Session->success(__('The slide has been saved.'));
                }
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
            $this->SlideProcessing->delete_slide_from_s3($data['Slide']['key']);

            // show message
            $this->Session->success(__('The slide has been deleted.'));
        } else {
            $this->Session->warning(__('The slide could not be deleted. Please, try again.'));
        }

        return $this->redirect(array('controller' => 'users', 'action' => 'index'));
    }
}
