<?php

App::uses('ComponentCollection', 'Controller');
App::uses('SlideProcessingComponent', 'Controller/Component');
App::uses('SQSListener', 'Event');

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
     public $S3;


     /**
      * SlideProcessing
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
        $this->S3 = $this->components->load('S3');
    }

    /**
     * main.
     */
    public function main()
    {
        $this->QueueWorker->getEventManager()->attach(new SQSListener());
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

        return $this->SlideProcessing->extract_images($this->S3->getClient(), $data);
    }
}
