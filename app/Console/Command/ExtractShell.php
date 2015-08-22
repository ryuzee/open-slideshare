<?php

App::uses('ComponentCollection', 'Controller');
App::uses('SlideProcessingComponent', 'Controller/Component');

/**
 * Class: ExtractShell.
 */
class ExtractShell extends AppShell
{
    /**
     * tasks.
     *
     * @var array
     */
    public $tasks = array('SQS.QueueWorker');

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
    public $SlideProcessing;

    /**
     * startup.
     */
    public function startup()
    {
        $this->components = new ComponentCollection();
        $this->SlideProcessing = $this->components->load('SlideProcessing');
    }

    /**
     * main.
     */
    public function main()
    {
        $this->QueueWorker->addFunction('extract', $this, 'handleJob');
        $this->QueueWorker->work();
    }

    /**
     * handleJob.
     *
     * @param mixed $data
     */
    public function handleJob($data)
    {
        echo '[LOG] Start extracting ' . $data['key'] . "\n";

        return $this->SlideProcessing->extract_images($data);
    }
}
