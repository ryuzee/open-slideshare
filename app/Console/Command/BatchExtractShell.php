<?php

App::uses('ComponentCollection', 'Controller');
App::uses('S3Component', 'Controller/Component');

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
        $this->S3 = $this->components->load('S3');
    }

    /**
     * main.
     */
    public function main()
    {
        $slides = $this->Slide->find('all');
        foreach ($slides as $slide) {
            echo '----'.$slide['Slide']['key']."----\n";
            $this->S3->extract_images($slide['Slide']);
        }
    }
}
