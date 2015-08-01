<?php

App::uses('AppModel', 'Model');
/**
 * Comment Model.
 *
 * @property User $User
 * @property Artifact $Artifact
 */
class Comment extends AppModel
{
    /**
     * Display field.
     *
     * @var string
     */
    public $displayField = 'content';

    /**
     * Validation rules.
     *
     * @var array
     */
    public $validate = array(
        'user_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
    );

    /**
     * belongsTo associations.
     *
     * @var array
     */
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
        'Slide' => array(
            'className' => 'Slide',
            'foreignKey' => 'slide_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
    );

    public function get_recent_comment()
    {
        $recent_comment = $this->find('all', array(
            'fields' => array('Slide.id', 'Slide.name', 'User.display_name', 'Comment.created'),
            'order' => 'Comment.created desc',
            'recursive' => 2,
            'limit' => 10,
        ));

        return $recent_comment;
    }
}
