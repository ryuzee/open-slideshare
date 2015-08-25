<?php

App::uses('AppController', 'Controller');
/**
 * Categories Controller.
 *
 * @property Category $Category
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class CategoriesController extends AppController
{
    /**
     * uses
     *
     */
    public $uses = array('Category', 'Slide');

    /**
     * beforeFilter
     *
     */
    public function beforeFilter()
    {
        $this->Auth->allow('view');
        parent::beforeFilter();
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
        if (!$this->Category->exists($id)) {
            throw new NotFoundException(__('Invalid category'));
        }
        $title = sprintf(__('Slides in %s'), h($this->Category->get_category_name($id)));
        $this->set('id', $id);
        $this->set('title', $title);
        $this->set('title_for_layout', $title);
        $conditions = $this->Slide->get_conditions_to_get_slides_in_category($id);

        if ($this->RequestHandler->isRss()) {
            Configure::write('debug', 0);
            $slides = $this->Slide->find('all', $conditions);
            $this->set(compact('slides'));
            $this->set('description', $title);
            return $this->render('/Slides/rss/slide_list');
        }

        $this->paginate = $conditions;
        $this->set('slides', $this->Paginator->paginate('Slide'));

    }
}
