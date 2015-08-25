<?php

App::uses('AppModel', 'Model');

/**
 * Slide Model.
 *
 * @property User $User
 * @property Category $Category
 */
class Slide extends AppModel
{
    /**
     * Display field.
     *
     * @var string
     */
    public $displayField = 'name';

    /**
     * actsAs.
     *
     * @var array
     */
    public $actsAs = array(
        'Search.Searchable',
    );

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
        'name' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Title must be compulsory',
             ),
            'maxlength' => array(
                'rule' => array('maxLength', 255),
                'message' => 'Title must be lower than 255 characters',
            ),
        ),
        'description' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Description must be compulsory',
             ),
        ),
        'key' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Key must be set',
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
            'counterCache' => 'slides_count',
            'order' => '',
        ),
        'Category' => array(
            'className' => 'Category',
            'foreignKey' => 'category_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
    );

    /**
     * hasMany.
     *
     * @var array
     */
    public $hasMany = array(
        'Comment' => array(
            'className' => 'Comment',
            'foreignKey' => 'slide_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => '',
        ),
    );

    /**
     * filterArgs.
     *
     * @var array
     */
    public $filterArgs = array(
        'name' => array('type' => 'like'),
        'description' => array('type' => 'like'),
        'display_name' => array('type' => 'like', 'field' => array('User.display_name')),
        'category' => array('type' => 'value', 'field' => array('Category.name')),
    );

    /**
     * countup.
     *
     * @param mixed $column
     * @param mixed $id
     */
    public function countup($column, $id = null)
    {
        if (!$id) {
            return false;
        }
        $fields = array(sprintf('%s', $column) => sprintf('%s + 1', $column));
        $conditions = array('Slide.id' => $id);
        return $this->updateAll($fields, $conditions);
    }

    /**
     * Get Single Slide.
     *
     * @param int $id Slide ID
     *
     * @return Model
     */
    public function get_slide($id)
    {
        $options = array('conditions' => array('Slide.id' => $id), 'recursive' => 2);

        return $this->find('first', $options);
    }

    /**
     * Retrieve slides in the specific category.
     *
     * @param int $category_id
     *                         integer $exclude_slide_id
     *
     * @return array
     */
    public function get_recent_slides_in_category($category_id, $exclude_slide_id = null)
    {
        $conditions = array('Slide.convert_status = '.SUCCESS_CONVERT_COMPLETED.' and Slide.category_id ='.$category_id);
        if ($exclude_slide_id) {
            $conditions[] = 'Slide.id != '.$exclude_slide_id;
        }

        $result = $this->find('all',
            array(
                'recursive' => 1,
                'limit' => 10,
                'order' => array('Slide.created desc'),
                'conditions' => $conditions,
            )
        );

        return $result;
    }

    /**
     * Get paginate condition for retrieving slides in specific category.
     *
     * @param int $id category ID
     *
     * @return array search conditions
     */
    public function get_conditions_to_get_slides_in_category($id)
    {
        $conditions = array(
            'model' => 'Slide',
            'limit' => 20,
            'recursive' => 2,
            'conditions' => 'Slide.convert_status = '.SUCCESS_CONVERT_COMPLETED.' and Slide.category_id = '.$id,
            'order' => array('Slide.id' => 'desc'),
        );

        return $conditions;
    }

    /**
     * get_conditions_to_get_latest_slides
     *
     */
     public function get_conditions_to_get_latest_slides($count = 20)
    {
        $conditions = array(
            'model' => 'Slide',
            'limit' => $count,
            'recursive' => 2,
            'conditions' => array('Slide.convert_status = ' . SUCCESS_CONVERT_COMPLETED),
            'order' => array('Slide.created' => 'desc'),
        );

        return $conditions;
    }

    /**
     * get_conditions_to_get_popular_slides
     *
     */
    public function get_conditions_to_get_popular_slides($count = 20)
    {
        $conditions = array(
            'model' => 'Slide',
            'limit' => $count,
            'recursive' => 2,
            'conditions' => array('Slide.convert_status = ' . SUCCESS_CONVERT_COMPLETED),
            'order' => array('Slide.page_view' => 'desc'),
        );

        return $conditions;
    }

    /**
     * get_conditions_to_get_slides_by_user
     *
     * @param mixed $user_id
     */
    public function get_conditions_to_get_slides_by_user($user_id, $include_all_slides = false)
    {
        $conditions = array(
            'model' => 'Slide',
            'limit' => 15,
            'recursive' => 2,
            'conditions' => array('Slide.user_id = ' . $user_id),
            'order' => array('Slide.id' => 'desc'),
        );
        if ($include_all_slides === false) {
            $conditions['conditions'][] = 'Slide.convert_status = ' . SUCCESS_CONVERT_COMPLETED;
        }

        return $conditions;
    }

    /**
     * Update status code in Slide to indicate conversion status
     *
     * @param string $key
     *        int    $status_code
     * @return void
     *
     */
    public function update_status($key, $status_code)
    {
        $this->primaryKey = "key";
        if ($this->exists($key)) {
            $this->read(null, $key);
            $this->set('convert_status', $status_code);
            $this->save();
        }
    }

    /**
     * Update status code in Slide to indicate conversion status
     *
     * @param string $key
     *        string $extension
     * @return void
     *
     */
    public function update_extension($key, $extension)
    {
        $this->primaryKey = "key";
        if ($this->exists($key)) {
            $this->read(null, $key);
            $this->set('extension', $extension);
            $this->save();
        }
    }
}
