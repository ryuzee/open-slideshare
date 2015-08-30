<?php

App::uses('User', 'Model');

/**
 * User Test Case.
 */
class UserTest extends CakeTestCase
{
    /**
     * Fixtures.
     *
     * @var array
     */
    public $fixtures = array(
        'app.user',
    );

    /**
     * base_data
     *
     * @var array
     */
    public $base_data = array(
        'username' => 'hoge@example.com',
        'password' => '12345678',
        'display_name' => 'test',
        'biography' => 'test',
    );

    /**
     * setUp method.
     */
    public function setUp()
    {
        parent::setUp();
        $this->User = ClassRegistry::init('User');
    }

    /**
     * validationProvider
     *
     */
    public function validationProvider()
    {
        return array(
            array($this->base_data, true),
            array(array_merge($this->base_data, array('username' => 'hoge@example.com')), true),
            array(array_merge($this->base_data, array('username' => '')), false),
            array(array_merge($this->base_data, array('username' => 'hoge')),  false),
            array(array_merge($this->base_data, array('username' => 'hoge2')), false),
            array(array_merge($this->base_data, array('username' => 'test@example.com')), false), // already used
            array(array_merge($this->base_data, array('password' => '12345')), true),
            array(array_merge($this->base_data, array('password' => '!#$%&*+@')), true),
            array(array_merge($this->base_data, array('password' => '[]`"(){}<>/.,_')), false),
            array(array_merge($this->base_data, array('password' => '1234')), false),
            array(array_merge($this->base_data, array('password' => '')), false),
            array(array_merge($this->base_data, array('display_name' => '')), false),
            array(array_merge($this->base_data, array('biography' => '')), false),
        );
    }

    /**
     * @dataProvider validationProvider
     */
    public function testValidation($a, $expected)
    {
        $this->User->set($a);
        $result = $this->User->validates();
        $this->assertEqual($result, $expected);
    }

    /**
     * testPasswordMustBeHashed
     *
     */
    public function testPasswordMustBeHashed()
    {
        $this->User->set($this->base_data);
        $this->User->save();
        $id = $this->User->getLastInsertID();
        $data = $this->User->findById($id);
        $this->assertNotEquals($this->base_data['password'], $data['User']['password']);
    }

    /**
     * testGetConditionsToGetAllUsers
     *
     */
    public function testGetConditionsToGetAllUsers()
    {
        $cond = $this->User->get_conditions_to_get_all_users();
        $result = $this->User->find('all', $cond);

        App::uses('UserFixture', 'Test/Fixture');
        $fixture = new UserFixture();
        $expected_records = $fixture->records; // The second record in the fixture

        $this->assertEqual(count($result), count($expected_records));
    }

    /**
     * tearDown method.
     */
    public function tearDown()
    {
        unset($this->User);

        parent::tearDown();
    }
}
