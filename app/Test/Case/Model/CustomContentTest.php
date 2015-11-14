<?php
App::uses('CustomContent', 'Model');

/**
 * CustomContent Test Case
 */
class CustomContentTest extends CakeTestCase
{
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = array(
        'app.custom_content'
    );

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->CustomContent = ClassRegistry::init('CustomContent');
    }

    /**
     * testGetAllCustomContents
     *
     */
    public function testGetAllCustomContents()
    {
        $v = $this->CustomContent->get_all_custom_contents();
        $this->assertRegExp('/value1 Lorem/', $v["content1"]);
        $this->assertRegExp('/value2 Lorem/', $v["content2"]);
        $this->assertEqual(count($v), 2);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CustomContent);

        parent::tearDown();
    }
}
