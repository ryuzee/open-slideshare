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
     * testGetMimeType
     *
     */
    public function testGetMimeType()
    {
        $method = new ReflectionMethod($this->SlideProcessing, 'get_mime_type');
        $method->setAccessible(true);
        $dir = dirname(dirname(dirname(dirname(__FILE__)))) . DS . "Data";

        // pptx
        $result = $method->invoke($this->SlideProcessing, $dir . DS . "test.pptx");
        $this->assertEqual('application/vnd.openxmlformats-officedocument.presentationml.presentation', $result);

        // pdf
        $result = $method->invoke($this->SlideProcessing, $dir . DS . "test.pdf");
        $this->assertEqual('application/pdf', $result);

        // ppt
        $result = $method->invoke($this->SlideProcessing, $dir . DS . "test.ppt");
        $this->assertEqual('application/vnd.ms-powerpoint', $result);
    }
}
