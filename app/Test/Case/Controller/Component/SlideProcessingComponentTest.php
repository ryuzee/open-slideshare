<?php
App::uses('ComponentCollection', 'Controller');
App::uses('Component', 'Controller');
App::uses('SlideProcessingComponent', 'Controller/Component');

/**
 * SlideProcessingComponent Test Case
 */
class SlideProcessingComponentTest extends CakeTestCase
{
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $Collection = new ComponentCollection();
        $this->SlideProcessing = new SlideProcessingComponent($Collection);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SlideProcessing);

        parent::tearDown();
    }

    /**
     * testLocalImages
     *
     */
    public function testLocalImages()
    {
        $method = new ReflectionMethod($this->SlideProcessing, 'list_local_images');
        $method->setAccessible(true);
        $dir = dirname(dirname(dirname(dirname(__FILE__)))) . DS . 'Data' . DS . 'tmp';
        if (! file_exists($dir)) {
            @mkdir($dir);
        }

        // There is no file.
        $result = $method->invoke($this->SlideProcessing, $dir);
        $this->assertTrue(count(iterator_to_array($result)) == 0);

        // There is no jpg file but png file
        copy(dirname($dir) . DS . 'test.png', $dir . DS . 'test.png');
        $result = $method->invoke($this->SlideProcessing, $dir);
        $this->assertTrue(count(iterator_to_array($result)) == 0);

        // There is only 1 jpg file.
        copy(dirname($dir) . DS . 'test.jpg', $dir . DS . 'test.jpg');
        $result = $method->invoke($this->SlideProcessing, $dir);
        $this->assertTrue(count(iterator_to_array($result)) == 1);

        // Delete directory
        $method = new ReflectionMethod($this->SlideProcessing, 'cleanup_working_dir');
        $method->setAccessible(true);
        $result = $method->invoke($this->SlideProcessing, $dir);
        $this->assertFalse(file_exists($dir));
    }
}
