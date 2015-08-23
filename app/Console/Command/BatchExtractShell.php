<?php

App::uses('ComponentCollection', 'Controller');
App::uses('SlideProcessingComponent', 'Controller/Component');

/**
 * Class: BatchExtractShell.
 */
class BatchExtractShell extends AppShell
{
    /**
     * uses.
     *
     * @var array
     */
    public $uses = array('Slide');

    /**
     * components.
     *
     * @var mixed
     */
    public $components;

    /**
     * S3.
     *
     * @var mixed
     */
     public $S3;

     /**
      * SlideProcessing
      *
      * @var mixed
      */
     public $SlideProcessing;

    /**
     * Slide.
     *
     * @var mixed
     */
    public $Slide;

    /**
     * startup.
     */
    public function startup()
    {
        $this->components = new ComponentCollection();
        $this->SlideProcessing = $this->components->load('SlideProcessing');
        $this->S3 = $this->components->load('S3');
    }

    /**
     * main.
     */
    public function main()
    {
        $slides = $this->Slide->find('all');
        foreach ($slides as $slide) {
            echo '----' . $slide['Slide']['key'] . "----\n";
            $this->SlideProcessing->extract_images($this->S3->getClient(), $slide['Slide']);
        }
    }
}
