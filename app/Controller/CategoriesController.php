<?php
App::uses('AppController', 'Controller');
/**
 * Categories Controller
 *
 * @property Category $Category
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class CategoriesController extends AppController
{
    public $uses = array('Category', 'Slide');

    public function beforeFilter()
    {
        $this->Auth->allow('view');
        parent::beforeFilter();
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null)
    {
        if (!$this->Category->exists($id)) {
            throw new NotFoundException(__('Invalid category'));
        }
        $this->paginate = $this->Slide->get_conditions_to_get_slides_in_category($id);
        $this->set('slides', $this->Paginator->paginate('Slide'));
        $title = sprintf(__('Slides in %s'), h($this->Category->get_category_name($id)));
        $this->set('title', $title);
        $this->set('title_for_layout', $title);
    }
}
