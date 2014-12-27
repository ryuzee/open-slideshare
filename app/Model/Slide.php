<?php
App::uses('AppModel', 'Model');

/**
 * Slide Model
 *
 * @property User $User
 * @property Category $Category
 */
class Slide extends AppModel
{
    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'name';

    public $actsAs = array(
        'Search.Searchable'
    );

    /**
     * Validation rules
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
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Title must be compulsory',
             ),
            'maxlength' => array(
                'rule' => array('maxLength', 255),
                'message' => 'Title must be lower than 255 characters',
            ),
        ),
        'description' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Description must be compulsory',
             ),
        ),
        'key' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Key must be set',
             ),
        ),
    );

    /**
     * belongsTo associations
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
            'order' => ''
        ),
        'Category' => array(
            'className' => 'Category',
            'foreignKey' => 'category_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

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
            'counterQuery' => ''
        ),
    );

    public $filterArgs = array(
        'name' => array('type' => 'like'),
        'description' => array('type' => 'like'),
        'display_name' => array('type' => 'like', 'field' => array('User.display_name')),
        'category' => array('type' => 'value', 'field' => array('Category.name')),
    );

    /**
     * Count up the page view for specific slide
     *
     * @param integer $id Slide ID
     *
     */
    public function countup($id = null)
    {
        if (!$id) {
            return;
        }
        $fields = array('page_view' => 'page_view + 1');
        $conditions = array('Slide.id' => $id);
        $this->updateAll($fields, $conditions);
    }

    /**
     * Get Single Slide
     *
     * @param  integer $id Slide ID
     * @return Model
     *
     */
    public function get_slide($id)
    {
        $options = array('conditions' => array('Slide.id' => $id), 'recursive' => 2);

        return $this->find('first', $options);
    }

    /**
     * Retrieve slides in the specific category
     *
     * @param  integer $category_id
     *                              integer $exclude_slide_id
     * @return array
     *
     */
    public function get_recent_slides_in_category($category_id, $exclude_slide_id = null)
    {
        $conditions = array("Slide.convert_status = ". SUCCESS_CONVERT_COMPLETED . " and Slide.category_id =" . $category_id);
        if ($exclude_slide_id) {
            $conditions[] = "Slide.id != " . $exclude_slide_id;
        }

        $result = $this->find('all',
            array(
                'recursive' => 1,
                'limit' => 10,
                'order' => array("Slide.created desc"),
                'conditions' => $conditions
            )
        );

        return $result;
    }

    /**
     * Get paginate condition for retrieving slides in specific category
     *
     * @param  integer $id category ID
     * @return array   search conditions
     *
     */
    public function get_conditions_to_get_slides_in_category($id)
    {
        $conditions = array(
            'Slide' => array(
                'model' => 'Slide',
                'limit' => 20,
                'recursive' => 2,
                'conditions' => 'Slide.convert_status = '. SUCCESS_CONVERT_COMPLETED . ' and Slide.category_id = ' .$id,
                'order' => array('id' => 'desc'),
            ),
        );

        return $conditions;
    }

    /**
     * Get category name by id
     *
     * @param  integer $id Category ID
     * @return string  category name
     *
     */
    public function get_category_name($id)
    {
        $category = $this->read(null, $id);

        return $category["Category"]["name"];
    }
}
