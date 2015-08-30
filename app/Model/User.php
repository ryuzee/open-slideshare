<?php

App::uses('AppModel', 'Model');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth', 'AppModel', 'Model');

/**
 * User Model.
 */
class User extends AppModel
{
    /**
     * Display field.
     *
     * @var string
     */
    public $displayField = 'username';

    /**
     * validate.
     *
     * @var array
     */
    public $validate = array(
        'username' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
             ),
            'unique' => array(
                'rule' => array('isUnique'),
                'message' => 'username was already used',
            ),
            'minlength' => array(
                'rule' => array('minLength', 5),
                'message' => 'username must be more than 5 characters',
            ),
            'Email' => array(
                'rule' => array('email'),
                'message' => 'username must be your email address',
            ),
        ),
        'password' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
             ),
            'minlength' => array(
                'rule' => array('minLength', 5),
                'message' => 'password must be more than 5 characters',
                'allowEmpty' => true,
            ),
            'password_chars' => array(
                'rule' => array('custom', '/^[a-zA-Z0-9\!\#\$\%\&\*\+\@?]+$/'),
                'message' => 'password must be consist of alphabetic or numeric or !#$%&*+@? characters',
                'allowEmpty' => true,
            ),
        ),
        'display_name' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
             ),
        ),
        'biography' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
             ),
        ),
     );

    /**
     * beforeSave.
     *
     * @param array $options
     */
    public function beforeSave($options = array())
    {
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new SimplePasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash($this->data[$this->alias]['password']);
        }

        return true;
    }

    /**
     * get_conditions_to_get_all_users
     *
     */
    public function get_conditions_to_get_all_users()
    {
        $conditions = array(
            'model' => 'User',
            'recursive' => 2,
            'limit' => 20,
            'order' => array('User.id' => 'desc'),
        );

        return $conditions;
    }
}
