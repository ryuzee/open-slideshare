<?php

App::uses('AppController', 'Controller');
/**
 * Users Controller.
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class UsersController extends AppController
{
    /**
     * uses.
     */
    public $uses = array('User', 'Slide');

    /**
     * beforeFilter.
     */
    public function beforeFilter()
    {
        $this->Auth->allow('signup', 'view', 'index');
        parent::beforeFilter();
    }

    /**
     * index.
     */
    public function index()
    {
        $this->set('title_for_layout', __('Your Dashboard'));
        $user = $this->Auth->User();
        $user_id = $user['id'];
        $this->paginate = array(
                    'Slide' => array(
                        'model' => 'Slide',
                        'limit' => 12,
                        'recursive' => 2,
                        'conditions' => 'Slide.user_id = ' . $user_id,
                        'order' => array('id' => 'desc'),
                    ),
            );
        $this->set('slides', $this->Paginator->paginate('Slide'));

        $userinfo = $this->User->read(null, $user_id);
        if (!$userinfo) {
            throw new NotFoundException(__('Invalid user'));
        }

        $this->set('user', $userinfo);
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
        if (!$this->User->exists($id)) {
            throw new NotFoundException(__('Invalid user'));
        }
        $options = array('conditions' => array('User.' . $this->User->primaryKey => $id), 'recursive' => 2);
        $user = $this->User->find('first', $options);
        $this->set('user', $user);
        $this->set('title_for_layout', h($user['User']['display_name']));
        $title = sprintf(__('Slides by %s'), $user['User']['display_name']);
        $this->set('title', $title);
        $conditions = $this->Slide->get_conditions_to_get_slides_by_user($id);

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

    /**
     * login.
     */
    public function login()
    {
        $this->set('title_for_layout', __('Login'));
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                // ログインフォームに戻りURLの指定があるか確認
                $return_url = $this->request->data['return_url'];
                // 本来の戻りURL
                $url = $this->Auth->redirectUrl();
                // どっちのURLを使うか
                if (isset($return_url) && $this->Auth->loginAction != $return_url) {
                    $redirect_url = $return_url;
                } else {
                    $redirect_url = $url;
                }

                return $this->redirect($redirect_url);
            } else {
                $this->Session->danger(__('Invalid username or password, try again'));
            }
        }
    }

    /**
     * logout.
     */
    public function logout()
    {
        $this->Auth->logout();
        $this->Session->success(__('You have finished to logout.', true));
        $this->redirect(array('controller' => 'slides', 'action' => 'index'));
    }

    /**
     * signup.
     */
    public function signup()
    {
        $this->set('title_for_layout', __('Signup'));
        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Session->success(__('The user has been saved'));

                return $this->redirect(array('action' => 'login'));
            } else {
                $this->Session->danger(__('The user could not be saved. Please, try again.'));
            }
        }
    }

    /**
     * json_list.
     */
    public function json_list()
    {
        $this->layout = 'ajax';
        Configure::write('debug', 0);
        $users = $this->User->find('all', array());

        $results = array();
        if (is_array($users)) {
            foreach ($users as $user) {
                $rec['id'] = $user['User']['id'];
                $rec['name'] = $user['User']['display_name'];
                $rec['avatar'] = '';
                $rec['icon'] = '';
                $rec['type'] = 'contact';
                $results[] = $rec;
            }
        }
        $this->set('users', json_encode($results));
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
        $this->set('title_for_layout', __('Edit'));

        if (!$this->User->exists($id)) {
            throw new NotFoundException(__('Invalid user'));
        }
        $u = $this->Auth->User();
        // illegal user check
        if ($id != $u['id']) {
            return $this->redirect(array('controller' => 'users', 'action' => 'index'));
        }
        if ($this->request->is(array('post', 'put'))) {
            $save_fields = array('password', 'display_name', 'biography');
            if ($this->request->data['User']['password'] === '') {
                $save_fields = array('display_name', 'biography');
            }
            if ($this->User->save($this->request->data, true, $save_fields)) {
                $this->Session->success(__('The user has been saved.'));

                return $this->redirect(array('controller' => 'users', 'action' => 'index'));
            } else {
                $this->Session->warning(__('The user could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
            $this->request->data = $this->User->find('first', $options);
        }
        $userinfo = $this->User->read(null, $id);
        $this->set('user', $userinfo);
    }

    /**
     * delete method.
     *
     * @throws NotFoundException
     */
    public function delete()
    {
        $u = $this->Auth->User();
        $id = $u['id'];
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        $this->request->allowMethod('post', 'get');
        if ($this->User->delete()) {
            $this->Session->success(__('The user has been deleted.'));
            $this->Auth->logout();
        } else {
            $this->Session->warning(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(array('controller' => 'users', 'action' => 'index'));
    }
}
