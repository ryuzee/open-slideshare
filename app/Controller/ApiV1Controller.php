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
    public $uses = array('Slide');

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
        $this->viewClass = 'Json';
        $this->Auth->allow('get_slides', 'get_slide');
        parent::beforeFilter();
    }

    /**
     * get_slides
     *
     */
    public function get_slides()
    {
        Configure::write('debug', 0);
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
            $records = array();
        }
        $this->set('slides', $records);
    }

    public function get_slide($id = null)
    {
        if (!$this->Slide->exists($id)) {
            throw new NotFoundException(__('Invalid slide'));
        }
        $slide = $this->Slide->get_slide($id);
        $this->set('slide', $slide);
    }
}
