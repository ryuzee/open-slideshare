<?php
App::uses('AppModel', 'Model');

/**
 * Config Model
 *
 */
class Config extends AppModel
{
    /**
     * Primary key field
     *
     * @var string
     */
    public $primaryKey = 'name';

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'name';

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'name' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
            ),
        ),
        'value' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
            ),
        ),
    );

    function get_value($key)
    {
        $record = $this->findByName($key);
        if(!$record) {
            return false;
        } else {
            return $record["Config"]["value"];
        }
    }

    function get_all_configs()
    {
        $records = $this->find('all');
        if(!$records) {
            return false;
        } else {
            $data = array();
            foreach($records as $record) {
                $k = $record["Config"]["name"];
                $v = $record["Config"]["value"];
                $data[$k] = $v;
            }
            return $data;
        }
    }

    function set_value($key, $value)
    {
        $data = array();
        $data["Config"]["name"] = $key;
        $data["Config"]["value"] = $value;
        $this->create($data);
        if ($this->save()) {
            return true;
        } else {
            return false;
        }
    }
}
