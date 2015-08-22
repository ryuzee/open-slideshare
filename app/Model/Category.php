<?php

App::uses('AppModel', 'Model');
/**
 * Category Model.
 *
 * @property Slide $Slide
 */
class Category extends AppModel
{
    /**
     * Display field.
     *
     * @var string
     */
    public $displayField = 'name';

    /**
     * Validation rules.
     *
     * @var array
     */
    public $validate = array(
        'name' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
            ),
        ),
    );

    /**
     * hasMany associations.
     *
     * @var array
     */
    public $hasMany = array(
        'Slide' => array(
            'className' => 'Slide',
            'foreignKey' => 'category_id',
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
     * Get category name by id.
     *
     * @param int $id Category ID
     *
     * @return string category name
     */
    public function get_category_name($id)
    {
        $category = $this->read(null, $id);
        if ($category) {
            return $category['Category']['name'];
        } else {
            return __('No Category');
        }
    }
}
