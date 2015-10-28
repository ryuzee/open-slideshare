<?php

App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

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
        Configure::write('debug', 0);
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
        $this->Prg->commonProcess();
        $add_query = array('Slide.convert_status = ' . SUCCESS_CONVERT_COMPLETED);

        $val = isset($this->passedArgs['created_f']) ? $this->passedArgs['created_f'] : null;
        if (!empty($val)) {
            $add_query[] = "Slide.created >= '" . Sanitize::clean($val) . "'";
        }
        $val = isset($this->passedArgs['created_t']) ? $this->passedArgs['created_t'] : null;
        if (!empty($val)) {
            $add_query[] = "Slide.created <= '" . Sanitize::clean($val) . "'";
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
            return $this->send_error('slides', 400, __('Failed to retrieve results'));
        }
        $this->response->statusCode(200);
        $this->set('slides', $records);
        return $this->render('slides');
    }

    /**
     * get_slide
     *
     */
    public function get_slide_by_id()
    {
        $id = isset($this->request->params['id']) ? $this->request->params['id'] : '';
        if (!$id || !$this->Slide->exists($id)) {
            return $this->send_error('slide', 400, __('Invalid slide'));
        }

        $this->response->statusCode(200);
        $slide = $this->Slide->get_slide($id);
        $this->set('slide', $slide);
        return $this->render('slide');
    }

    /**
     * get_transcript_by_id
     *
     */
    public function get_transcript_by_id()
    {
        $id = isset($this->request->params['id']) ? $this->request->params['id'] : '';
        if (!$id || !$this->Slide->exists($id)) {
            return $this->send_error('transcript', 400, __('Invalid slide'));
        }

        $this->response->statusCode(200);
        $slide = $this->Slide->get_slide($id);
        $transcripts = $this->SlideProcessing->get_transcript($slide['Slide']['key']);
        $this->set('transcripts', $transcripts);
        return $this->render('transcript');
    }

    /**
     * get_slides_by_user_id
     *
     */
    public function get_slides_by_user_id()
    {
        $id = isset($this->request->params['id']) ? $this->request->params['id'] : '';
        if (!$id || !$this->User->exists($id)) {
            return $this->send_error('slides', 400, __('Invalid User'));
        }
        $this->response->statusCode(200);
        $conditions = $this->Slide->get_conditions_to_get_slides_by_user($id, false, false);
        $slides = $this->Slide->find('all', $conditions);
        $this->set('slides', $slides);
        return $this->render('slides');
    }

    /**
     * get_user_by_user_id
     *
     */
    public function get_user_by_user_id()
    {
        $id = isset($this->request->params['id']) ? $this->request->params['id'] : '';
        if (!$id || !$this->User->exists($id)) {
            return $this->send_error('user', 400, __('Invalid User'));
        }
        $this->response->statusCode(200);
        $user = $this->User->read(null, $id);
        $this->set('user', $user);
        return $this->render('user');
    }

    /**
     * send_error
     *
     * @param mixed $template_name
     * @param mixed $status_code
     * @param mixed $message
     */
    private function send_error($template_name, $status_code, $message)
    {
        $this->response->statusCode($status_code);
        $result = array();
        $result['error']['message'] = $message;
        $this->set('error', $result['error']);
        return $this->render($template_name);
    }

}
