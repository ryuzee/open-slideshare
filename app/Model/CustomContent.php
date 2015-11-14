<?php
App::uses('AppModel', 'Model');
/**
 * CustomContent Model
 *
 */
class CustomContent extends AppModel
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
     * get_all_custom_contents
     *
     */
    function get_all_custom_contents()
    {
        $records = $this->find('all');
        if(!$records) {
            return false;
        } else {
            $data = array();
            foreach($records as $record) {
                $k = $record["CustomContent"]["name"];
                $v = $record["CustomContent"]["value"];
                $data[$k] = $v;
            }
            return $data;
        }
    }
}
