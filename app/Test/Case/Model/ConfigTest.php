<?php
App::uses('Config', 'Model');

/**
 * Config Test Case
 */
class ConfigTest extends CakeTestCase
{
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = array(
        'app.config'
    );

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->Config = ClassRegistry::init('Config');
    }

    /**
     * testGetValue
     *
     */
    public function testGetValue()
    {
        $v = $this->Config->get_value("config1");
        $this->assertEqual($v, "1");
        $v = $this->Config->get_value("config2");
        $this->assertEqual($v, "value2");
        $v = $this->Config->get_value("config_not_exist");
        $this->assertEqual($v, null);
    }

    /**
     * testSetValue
     *
     */
    public function testSetValue()
    {
        $result = $this->Config->set_value("do_you_like_sushi", "yes");
        $this->assertTrue($result);
        $v = $this->Config->get_value("do_you_like_sushi");
        $this->assertEqual($v, "yes");
    }

    /**
     * testGetAllConfigs
     *
     */
    public function testGetAllConfigs()
    {
        $v = $this->Config->get_all_configs();
        $this->assertEqual($v["config1"], "1");
        $this->assertEqual($v["config2"], "value2");
        $this->assertEqual(count($v), 2);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Config);

        parent::tearDown();
    }
}
