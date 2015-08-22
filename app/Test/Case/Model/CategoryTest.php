<?php
App::uses('Category', 'Model');

/**
 * Category Test Case
 */
class CategoryTest extends CakeTestCase
{
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = array(
        'app.category',
        'app.slide',
        'app.user',
        'app.comment'
    );

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->Category = ClassRegistry::init('Category');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Category);

        parent::tearDown();
    }

    /**
     * testGetCategoryName method
     *
     * @return void
     */
    public function testGetCategoryName()
    {
        $name1 = $this->Category->get_category_name(1);
        App::uses('CategoryFixture', 'Test/Fixture');
        $fixture = new CategoryFixture();
        $records = $fixture->records;
        $this->assertEqual($name1, $records[0]['name']);

        // ID does not exist.
        $name2 = $this->Category->get_category_name(100);
        $this->assertEqual($name2, 'No Category');
    }
}
