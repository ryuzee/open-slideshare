<?php
App::uses('AppModel', 'Model');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth', 'AppModel', 'Model');

/**
 * User Model
 *
 */
class User extends AppModel
{
    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'username';

    public $validate = array(
        'username' => array(
            'notempty' => array(
                'rule' => array('notempty'),
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
                'message' => 'username must be your email address'
            ),
        ),
        'password' => array(
            'notempty' => array(
                'rule' => array('notempty'),
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
            'notempty' => array(
                'rule' => array('notempty'),
             ),
        ),
        'biography' => array(
            'notempty' => array(
                'rule' => array('notempty'),
             ),
        ),
     );

    public function beforeSave($options = array())
    {
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new SimplePasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash($this->data[$this->alias]['password']);
        }

        return true;
    }
}
