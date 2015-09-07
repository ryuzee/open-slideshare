<?php

App::uses('AppController', 'Controller');

/**
 * ApiV1 Controller.
 *
 * @property Comment $Comment
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class ApiV1Controller extends AppController
{
    /**
     * uses
     *
     * @var array
     */
    public $uses = array('Slide', 'User');

    public $presetVars = array(
        array('field' => 'name', 'type' => 'value'),
        array('field' => 'display_name', 'type' => 'value'),
        array('field' => 'description', 'type' => 'value'),
        array('field' => 'tags', 'type' => 'value'),
        array('field' => 'category', 'type' => 'value'),
        array('field' => 'created_f', 'type' => 'value'),
        array('field' => 'created_t', 'type' => 'value'),
    );

    /**
     * beforeFilter
     *
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->viewClass = 'Json';
        $this->Auth->allow();
        $this->response->header('X-Content-Type-Options', 'nosniff');
    }

    /**
     * get_slides
     *
     */
    public function get_slides()
    {
        // Configure::write('debug', 0);
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
        $this->Paginator->settings = array(
            'conditions' => array($this->Slide->parseCriteria($this->passedArgs), $add_query),
            'limit' => 200,
            'recursive' => 1,
            'order' => array('created' => 'desc'),
        );
        try {
            $records = $this->Paginator->paginate('Slide');
        } catch(Exception $e) {
            $this->response->statusCode(400);
            $result['error']['message'] = __('Failed to retrieve results');
            $this->set('error', $result['error']);
            return $this->render('slides');
        }
        $this->response->statusCode(200);
        $this->set('slides', $records);
        return $this->render('slides');
    }

    /**
     * get_slide
     *
     * @param mixed $id
     */
    public function get_slide_by_id()
    {
        $id = isset($this->request->params['id']) ? $this->request->params['id'] : '';
        if (!$id || !$this->Slide->exists($id)) {
            $this->response->statusCode(400);
            $result['error']['message'] = __('Invalid slide');
            $this->set('error', $result['error']);
            return $this->render('slide');
        }
        $this->response->statusCode(200);
        $slide = $this->Slide->get_slide($id);
        $this->set('slide', $slide);
        return $this->render('slide');
    }

    /**
     * get_slides_by_user_id
     *
     * This API can be accessed by "/api/v1/user/:id/slides". See route.php
     *
     * @param mixed $id
     */
    public function get_slides_by_user_id()
    {
        $id = isset($this->request->params['id']) ? $this->request->params['id'] : '';
        if (!$id || !$this->User->exists($id)) {
            $this->response->statusCode(400);
            $result['error']['message'] = __('Invalid user');
            $this->set('error', $result['error']);
            return $this->render('slides');
        }
        $this->response->statusCode(200);
        $conditions = $this->Slide->get_conditions_to_get_slides_by_user($id, false, false);
        $slides = $this->Slide->find('all', $conditions);
        $this->set('slides', $slides);
        return $this->render('slides');
    }
}
