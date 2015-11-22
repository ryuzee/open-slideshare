<?php

App::uses('Slide', 'Model');

/**
 * Slide Test Case.
 */
class SlideTest extends CakeTestCase
{
    /**
     * Fixtures.
     *
     * @var array
     */
    public $fixtures = array(
        'app.slide',
        'app.user',
        'app.category',
        'app.comment',
        'app.tag',
        'app.tagged',
    );

    /**
     * setUp method.
     */
    public function setUp()
    {
        parent::setUp();
        $this->Slide = ClassRegistry::init('Slide');
    }

    public function validationProvider()
    {
        $base_data = array(
            'user_id' => 1,
            'name' => 'The Slide Title',
            'descroption' => 'The Slide Description',
            'key' => '1234567890',
        );

        return array(
            array($base_data, true),
            array(array_merge($base_data, array('user_id' => null)), false),
            array(array_merge($base_data, array('name' => '')), false),
            array(array_merge($base_data, array('description' => '')),  false),
            array(array_merge($base_data, array('key' => '')), false),
        );
    }

    /**
     * @dataProvider validationProvider
     */
    public function testValidation($a, $expected)
    {
        $this->Slide->set($a);
        $result = $this->Slide->validates();
        $this->assertEqual($result, $expected);
    }
    /**
     * tearDown method.
     */
    public function tearDown()
    {
        unset($this->Slide);

        parent::tearDown();
    }

    /**
     * testCountup.
     */
    public function testCountup()
    {
        $id = 1;
        // Get current data
        $data = $this->Slide->findById($id);
        // count up
        $this->Slide->countup('download_count', $id);
        // and then get data again
        $data_new = $this->Slide->findById($id);

        $this->assertEqual($data['Slide']['download_count'] + 1, $data_new['Slide']['download_count']);
        foreach ($data['Slide'] as $key => $val) {
            if (!in_array($key, array('modified', 'download_count'))) {
                $this->assertEqual($data['Slide'][$key], $data_new['Slide'][$key]);
            }
        }

        $id = null;
        $result = $this->Slide->countup('download_count', $id);
        $this->assertFalse($result);

    }

    /**
     * testGetSlide method.
     */
    public function testGetSlide()
    {
        $id = 1;
        $data = $this->Slide->get_slide($id);

        App::uses('SlideFixture', 'Test/Fixture');
        $fixture = new SlideFixture();
        $records = $fixture->records;
        foreach ($records as $record) {
            if ($record['id'] == $id) {
                $expected_record = $record;
            }
        }
        foreach ($data['Slide'] as $k => $v) {
            if($k != "tags") {
                $this->assertEquals($data['Slide'][$k], $expected_record[$k], "The value of $k is OK?");
            }
        }
    }

    /**
     * testGetLatestSlidesInCategory method.
     */
    public function testGetLatestSlidesInCategory()
    {
        $slide_id = 1;
        $category_id = 1;
        $data = $this->Slide->get_latest_slides_in_category($category_id, $slide_id);

        App::uses('SlideFixture', 'Test/Fixture');
        $fixture = new SlideFixture();
        $expected_record = $fixture->records[1]; // The second record in the fixture
        foreach ($data[0]['Slide'] as $k => $v) {
            if($k != "tags") {
                $this->assertEquals($data[0]['Slide'][$k], $expected_record[$k], "The value of $k is OK?");
            }
        }
    }

    /**
     * testUpdateStatus
     *
     */
    public function testUpdateStatus()
    {
        $id = 1;
        $key = "4ea2abecba74eda5521fff924d9e5062";
        $this->Slide->update_status($key, -1);
        $data = $this->Slide->get_slide($id);
        $this->assertEqual($data['Slide']['convert_status'], -1);
    }

    /**
     * testUpdateExtension
     *
     */
    public function testUpdateExtension()
    {
        $id = 1;
        $key = "4ea2abecba74eda5521fff924d9e5062";
        $this->Slide->update_extension($key, '.pptx');
        $data = $this->Slide->get_slide($id);
        $this->assertEqual($data['Slide']['extension'], '.pptx');
    }

    /**
     * testUnbindFully
     */
    public function testUnbindFully()
    {
        // confirm the number of hasMany model
        $this->assertTrue(count($this->Slide->hasMany) > 0);

        $this->Slide->unbindFully();
        $this->assertTrue(count($this->Slide->hasMany) === 0);
        $this->assertTrue(count($this->Slide->belongsTo) === 0);
        $this->assertTrue(count($this->Slide->hasAndBelongsToMany) === 0);
    }

    /**
     * testGetConditionsToGetPopularSlides
     *
     */
    public function testGetConditionsToGetPopularSlides()
    {
        $cond = $this->Slide->get_conditions_to_get_popular_slides();
        $result = $this->Slide->find('all', $cond);
        $this->assertEqual($result[0]['Slide']['id'], 4);
        $this->assertEqual($result[0]['Slide']['page_view'], 9999);
    }

    /**
     * testGetConditionsToGetLatestSlides
     *
     */
    public function testGetConditionsToGetLatestSlides()
    {
        $cond = $this->Slide->get_conditions_to_get_latest_slides();
        $result = $this->Slide->find('all', $cond);
        $this->assertEqual($result[0]['Slide']['id'], 3);
        $this->assertEqual($result[0]['Slide']['created'], '2015-08-01 00:00:00');
    }

    /**
     * testGetConditionsToGetAllSlides
     *
     */
    public function testGetConditionsToGetAllSlides()
    {
        $cond = $this->Slide->get_conditions_to_get_all_slides();
        $result = $this->Slide->find('all', $cond);

        App::uses('SlideFixture', 'Test/Fixture');
        $fixture = new SlideFixture();
        $expected_records = $fixture->records; // The second record in the fixture

        $this->assertEqual(count($result), count($expected_records));
    }

    /**
     * testGetConditionsToGetSlidesInCategory
     *
     */
    public function testGetConditionsToGetSlidesInCategory()
    {
        $cond = $this->Slide->get_conditions_to_get_slides_in_category(3);
        $result = $this->Slide->find('all', $cond);
        $this->assertEqual($result[0]['Slide']['id'], 3);
        $this->assertEqual($result[0]['Slide']['category_id'], 3);
    }

    /**
     * testGetConditionsToGetSlidesByUser
     *
     */
    public function testGetConditionsToGetSlidesByUser()
    {
        $cond = $this->Slide->get_conditions_to_get_slides_by_user(1, 15, false);
        $result = $this->Slide->find('all', $cond);
        foreach ($result as $res) {
            $this->assertEqual($res['Slide']['user_id'], 1);
            $this->assertEqual($res['Slide']['convert_status'], SUCCESS_CONVERT_COMPLETED);
        }

        $cond2 = $this->Slide->get_conditions_to_get_slides_by_user(1, 15, true);
        $result2 = $this->Slide->find('all', $cond2);
        $this->assertNotEqual($result, $result2);
    }

    /**
     * testGetSlideIdByUser
     *
     */
    public function testGetSlideIdByUser()
    {
        $id_array = $this->Slide->get_slide_id_by_user(1);
        $expected = array(1,2);
        $this->assertEqual($id_array, $expected);
    }
}
