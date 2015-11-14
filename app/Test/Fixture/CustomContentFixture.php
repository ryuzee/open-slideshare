<?php
/**
 * CustomContent Fixture
 */
class CustomContentFixture extends CakeTestFixture
{
    /**
     * import
     *
     * @var array
     */
    public $import = array('model' => 'CustomContent');

    /**
     * Records
     *
     * @var array
     */
    public $records = array(
        array(
            'name' => 'content1',
            'value' => 'value1 Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eligendi non quis exercitationem culpa nesciunt nihil aut nostrum explicabo reprehenderit optio amet ab temporibus asperiores quasi cupiditate. Voluptatum ducimus voluptates voluptas?',
            'created' => '2015-10-02 12:14:22',
            'modified' => '2015-10-02 12:14:22'
        ),
        array(
            'name' => 'content2',
            'value' => 'value2 Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eligendi non quis exercitationem culpa nesciunt nihil aut nostrum explicabo reprehenderit optio amet ab temporibus asperiores quasi cupiditate. Voluptatum ducimus voluptates voluptas?',
            'created' => '2015-10-02 12:14:22',
            'modified' => '2015-10-02 12:14:22'
        ),
    );
}
