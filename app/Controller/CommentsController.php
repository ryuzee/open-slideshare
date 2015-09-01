<?php

App::uses('AppController', 'Controller');
/**
 * Comments Controller.
 *
 * @property Comment $Comment
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class CommentsController extends AppController
{
    /**
     * add method.
     */
    public function add()
    {
        $user = $this->Auth->User();
        $user_id = $user['id'];
        $this->set('user_id', $user_id);
        if ($this->request->is('post')) {
            // force set commenter user id.
            $this->request->data['Comment']['user_id'] = $user_id;
            $this->Comment->create();
            if ($this->Comment->save($this->request->data)) {
                $this->Session->success(__('The comment has been saved.'));

                return $this->redirect($this->request->data['Comment']['return_url']);
            } else {
                $this->Session->warning(__('The comment could not be saved. Please, try again.'));
            }
        }
        $users = $this->Comment->User->find('list');
        $slides = $this->Comment->Slide->find('list');
        $this->set(compact('users', 'slides'));
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
        $this->request->allowMethod('post', 'delete');

        $return_url = $this->request->query['return_url'];
        $this->Comment->id = $id;
        if (!$this->Comment->exists()) {
            throw new NotFoundException(__('Invalid comment'));
        }

        $options = array('conditions' => array('Comment.' . $this->Comment->primaryKey => $id), 'recursive' => 0);
        $comment = $this->Comment->find('first', $options);
        $comment_owner = $comment['Comment']['user_id'];

        $user = $this->Auth->User();
        $user_id = $user['id'];

        if ($comment_owner != $user_id) {
            $this->Session->warning(__('You can not delete comments posted by others.'));
        } else {
            if ($this->Comment->delete()) {
                $this->Session->success(__('The comment has been deleted.'));
            } else {
                $this->Session->warning(__('The comment could not be deleted. Please, try again.'));
            }
        }
        if ($return_url) {
            return $this->redirect($return_url);
        } else {
            return $this->redirect(array('action' => 'index'));
        }
    }
}
