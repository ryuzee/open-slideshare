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
        'content' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
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
}
